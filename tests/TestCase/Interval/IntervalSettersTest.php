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

namespace Cake\Chronos\Test\TestCase\Interval;

use Cake\Chronos\ChronosInterval;
use Cake\Chronos\Test\TestCase\TestCase;

class IntervalSettersTest extends TestCase
{
    public function testYearsSetter()
    {
        $this->deprecated(function () {
            $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10);
            $d->years = 2;
            $this->assertSame(2, $d->years);
        });
    }

    public function testMonthsSetter()
    {
        $this->deprecated(function () {
            $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10);
            $d->months = 11;
            $this->assertSame(11, $d->months);
        });
    }

    public function testWeeksSetter()
    {
        $this->deprecated(function () {
            $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10);
            $d->weeks = 11;
            $this->assertSame(11, $d->weeks);
            $this->assertSame(7 * 11, $d->dayz);
        });
    }

    public function testDayzSetter()
    {
        $this->deprecated(function () {
            $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10);
            $d->dayz = 11;
            $this->assertSame(11, $d->dayz);
            $this->assertSame(1, $d->weeks);
            $this->assertSame(4, $d->dayzExcludeWeeks);
        });
    }

    public function testHoursSetter()
    {
        $this->deprecated(function () {
            $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10);
            $d->hours = 12;
            $this->assertSame(12, $d->hours);
        });
    }

    public function testMinutesSetter()
    {
        $this->deprecated(function () {
            $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10);
            $d->minutes = 11;
            $this->assertSame(11, $d->minutes);
        });
    }

    public function testSecondsSetter()
    {
        $this->deprecated(function () {
            $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10, 123);
            $d->seconds = 34;
            $this->assertSame(34, $d->seconds);
        });
    }

    public function testMicroecondsSetter()
    {
        $this->deprecated(function () {
            $d = ChronosInterval::create(4, 5, 6, 5, 8, 9, 10, 123);
            $d->microseconds = 456;
            $this->assertSame(456, $d->microseconds);
        });
    }

    public function testFluentSetters()
    {
        $this->deprecated(function () {
            $ci = ChronosInterval::years(4)->months(2)->dayz(5)->hours(3)->minute()->seconds(59)->microseconds(123);
            $this->assertInstanceOf(ChronosInterval::class, $ci);
            $this->assertDateTimeInterval($ci, 4, 2, 5, 3, 1, 59, 123);

            $ci = ChronosInterval::years(4)->months(2)->weeks(2)->hours(3)->minute()->seconds(59)->microseconds(123);
            $this->assertInstanceOf(ChronosInterval::class, $ci);
            $this->assertDateTimeInterval($ci, 4, 2, 14, 3, 1, 59, 123);
        });
    }

    public function testFluentSettersDaysOverwritesWeeks()
    {
        $this->deprecated(function () {
            $ci = ChronosInterval::weeks(3)->days(5);
            $this->assertDateTimeInterval($ci, 0, 0, 5, 0, 0, 0);
        });
    }

    public function testFluentSettersWeeksOverwritesDays()
    {
        $this->deprecated(function () {
            $ci = ChronosInterval::days(5)->weeks(3);
            $this->assertDateTimeInterval($ci, 0, 0, 3 * 7, 0, 0, 0);
        });
    }

    public function testFluentSettersWeeksAndDaysIsCumulative()
    {
        $this->deprecated(function () {
            $ci = ChronosInterval::year(5)->weeksAndDays(2, 6);
            $this->assertDateTimeInterval($ci, 5, 0, 20, 0, 0, 0);
            $this->assertSame(20, $ci->dayz);
            $this->assertSame(2, $ci->weeks);
            $this->assertSame(6, $ci->dayzExcludeWeeks);
        });
    }
}
