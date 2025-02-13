<?php
declare(strict_types=1);

/**
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @copyright     Copyright (c) Brian Nesbitt <brian@nesbot.com>
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Chronos\Test\TestCase;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosDate;
use Cake\Chronos\ChronosInterval;
use Cake\Chronos\Date;
use Cake\Chronos\MutableDate;
use Cake\Chronos\MutableDateTime;
use Closure;
use DateInterval;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    private $saveTz;

    protected function setUp(): void
    {
        //save current timezone
        $this->saveTz = date_default_timezone_get();

        date_default_timezone_set('America/Toronto');
    }

    protected function tearDown(): void
    {
        date_default_timezone_set($this->saveTz);
        MutableDateTime::setTestNow(null);
        Chronos::setTestNow(null);
        MutableDate::setTestNow(null);
        ChronosDate::setTestNow(null);
    }

    public function classNameProvider()
    {
        return [
            'mutable' => [MutableDateTime::class],
            'immutable' => [Chronos::class],
        ];
    }

    public function dateClassProvider()
    {
        return [
            'mutable' => [MutableDate::class],
            'immutable' => [ChronosDate::class],
            'immutableAlias' => [Date::class],
        ];
    }

    protected function assertTime($d, $hour, $minute, $second = null, $microsecond = null)
    {
        $this->assertSame($hour, $d->hour, 'Chronos->hour');
        $this->assertSame($minute, $d->minute, 'Chronos->minute');

        if ($second !== null) {
            $this->assertSame($second, $d->second, 'Chronos->second');
        }

        if ($microsecond !== null) {
            $this->assertSame($microsecond, $d->microsecond, 'Chronos->microsecond');
        }
    }

    protected function assertDateTime($d, $year, $month, $day, $hour = null, $minute = null, $second = null, $microsecond = null)
    {
        $this->assertSame($year, $d->year, 'Chronos->year');
        $this->assertSame($month, $d->month, 'Chronos->month');
        $this->assertSame($day, $d->day, 'Chronos->day');

        if ($hour !== null) {
            $this->assertSame($hour, $d->hour, 'Chronos->hour');
        }

        if ($minute !== null) {
            $this->assertSame($minute, $d->minute, 'Chronos->minute');
        }

        if ($second !== null) {
            $this->assertSame($second, $d->second, 'Chronos->second');
        }

        if ($microsecond !== null) {
            $this->assertSame($microsecond, $d->microsecond, 'Chronos->microsecond');
        }
    }

    protected function assertDateTimeInterval(ChronosInterval $ci, $years, $months = null, $days = null, $hours = null, $minutes = null, $seconds = null, $microseconds = null)
    {
        $this->assertSame($years, $ci->years, 'ChronosInterval->years');

        if ($months !== null) {
            $this->assertSame($months, $ci->months, 'ChronosInterval->months');
        }

        if ($days !== null) {
            $this->assertSame($days, $ci->dayz, 'ChronosInterval->dayz');
        }

        if ($hours !== null) {
            $this->assertSame($hours, $ci->hours, 'ChronosInterval->hours');
        }

        if ($minutes !== null) {
            $this->assertSame($minutes, $ci->minutes, 'ChronosInterval->minutes');
        }

        if ($seconds !== null) {
            $this->assertSame($seconds, $ci->seconds, 'ChronosInterval->seconds');
        }

        if ($microseconds !== null) {
            $this->assertSame($microseconds, $ci->microseconds, 'ChronosInterval->microseconds');
        }
    }

    protected function assertDateInterval(DateInterval $interval, $years = null, $months = null, $days = null, $hours = null, $minutes = null, $seconds = null, $microseconds = null)
    {
        if ($years !== null) {
            $this->assertSame($years, $interval->y, 'DateInterval->y');
        }

        if ($months !== null) {
            $this->assertSame($months, $interval->m, 'DateInterval->m');
        }

        if ($days !== null) {
            $this->assertSame($days, $interval->d, 'DateInterval->d');
        }

        if ($hours !== null) {
            $this->assertSame($hours, $interval->h, 'DateInterval->h');
        }

        if ($minutes !== null) {
            $this->assertSame($minutes, $interval->i, 'DateInterval->i');
        }

        if ($seconds !== null) {
            $this->assertSame($seconds, $interval->s, 'DateInterval->s');
        }

        if ($microseconds !== null) {
            $this->assertSame($microseconds, $interval->f, 'DateInterval->f');
        }
    }

    protected function assertDate($d, $year, $month, $day)
    {
        $this->assertSame($year, $d->year, 'ChronosDate->year');
        $this->assertSame($month, $d->month, 'ChronosDate->month');
        $this->assertSame($day, $d->day, 'ChronosDate->day');
    }

    protected function wrapWithTestNow(Closure $func, $dt = null)
    {
        Chronos::setTestNow($dt ?? Chronos::now());
        $func();
        Chronos::setTestNow();
    }

    protected function withTimezone(string $tz, Closure $cb)
    {
        $restore = date_default_timezone_get();
        date_default_timezone_set($tz);
        try {
            $cb();
        } finally {
            date_default_timezone_set($restore);
        }
    }

    /**
     * Helper method for check deprecation methods
     *
     * @param \Closure $callable callable function that will receive asserts
     * @return void
     */
    public function deprecated(Closure $callable): void
    {
        /** @var bool $deprecation Expand type for psalm */
        $deprecation = false;

        $previousHandler = set_error_handler(
            function ($code, $message, $file, $line, $context = null) use (&$previousHandler, &$deprecation): bool {
                if ($code == E_USER_DEPRECATED) {
                    $deprecation = true;

                    return true;
                }
                if ($previousHandler) {
                    return $previousHandler($code, $message, $file, $line, $context);
                }

                return false;
            }
        );
        try {
            $callable();
        } finally {
            restore_error_handler();
        }
        $this->assertTrue($deprecation, 'Should have at least one deprecation warning');
    }
}
