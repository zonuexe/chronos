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

namespace Cake\Chronos\Test\TestCase\DateTime;

use Cake\Chronos\Test\TestCase\TestCase;

class ConstructTest extends TestCase
{
    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromTimestamp($class)
    {
        $ts = 1454284800;

        $time = new $class($ts);
        $this->assertSame('+00:00', $time->tzName);
        $this->assertSame('2016-02-01 00:00:00', $time->format('Y-m-d H:i:s'));
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreatesAnInstanceDefaultToNow($class)
    {
        $c = new $class();
        $now = $class::now();
        $this->assertInstanceOf($class, $c);
        $this->assertSame($now->tzName, $c->tzName);
        $this->assertDateTime($c, $now->year, $now->month, $now->day, $now->hour, $now->minute, $now->second);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testParseCreatesAnInstanceDefaultToNow($class)
    {
        $c = $class::parse();
        $now = $class::now();
        $this->assertInstanceOf($class, $c);
        $this->assertSame($now->tzName, $c->tzName);
        $this->assertDateTime($c, $now->year, $now->month, $now->day, $now->hour, $now->minute, $now->second);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testWithFancyString($class)
    {
        $c = new $class('first day of January 2008');
        $this->assertDateTime($c, 2008, 1, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testParseWithFancyString($class)
    {
        $c = $class::parse('first day of January 2008');
        $this->assertDateTime($c, 2008, 1, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testDefaultTimezone($class)
    {
        $c = new $class('now');
        $this->assertSame('America/Toronto', $c->tzName);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testConstructWithMicrosecondsAndOffset($class)
    {
        $c = new $class('2014-09-29 18:24:54.591767+02:00');
        $this->assertDateTime($c, 2014, 9, 29, 18, 24, 54);
        $this->assertSame(591767, $c->micro);
        $this->assertSame('+02:00', $c->getTimezone()->getName());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testParseWithDefaultTimezone($class)
    {
        $c = $class::parse('now');
        $this->assertSame('America/Toronto', $c->tzName);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSettingTimezone($class)
    {
        $timezone = 'Europe/London';
        $dtz = new \DateTimeZone($timezone);
        $dt = new \DateTime('now', $dtz);
        $dayLightSavingTimeOffset = (int)$dt->format('I');

        $c = new $class('now', $dtz);
        $this->assertSame($timezone, $c->tzName);
        $this->assertSame($dayLightSavingTimeOffset, $c->offsetHours);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testParseSettingTimezone($class)
    {
        $timezone = 'Europe/London';
        $dtz = new \DateTimeZone($timezone);
        $dt = new \DateTime('now', $dtz);
        $dayLightSavingTimeOffset = (int)$dt->format('I');

        $c = $class::parse('now', $dtz);
        $this->assertSame($timezone, $c->tzName);
        $this->assertSame($dayLightSavingTimeOffset, $c->offsetHours);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSettingTimezoneWithString($class)
    {
        $timezone = 'Asia/Tokyo';
        $dtz = new \DateTimeZone($timezone);
        $dt = new \DateTime('now', $dtz);
        $dayLightSavingTimeOffset = (int)$dt->format('I');

        $c = new $class('now', $timezone);
        $this->assertSame($timezone, $c->tzName);
        $this->assertSame(9 + $dayLightSavingTimeOffset, $c->offsetHours);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testParseSettingTimezoneWithString($class)
    {
        $timezone = 'Asia/Tokyo';
        $dtz = new \DateTimeZone($timezone);
        $dt = new \DateTime('now', $dtz);
        $dayLightSavingTimeOffset = (int)$dt->format('I');

        $c = $class::parse('now', $timezone);
        $this->assertSame($timezone, $c->tzName);
        $this->assertSame(9 + $dayLightSavingTimeOffset, $c->offsetHours);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromExistingInstance($class)
    {
        $existingClass = new $class();
        $this->assertInstanceOf($class, $existingClass);

        $newClass = new $class($existingClass);
        $this->assertInstanceOf($class, $newClass);
        $this->assertSame((string)$existingClass, (string)$newClass);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromDateTimeInterface($class)
    {
        $existingClass = new \DateTimeImmutable();
        $newClass = new $class($existingClass);
        $this->assertSame($existingClass->format('Y-m-d H:i:s.u'), $newClass->format('Y-m-d H:i:s.u'));

        $existingClass = new \DateTime();
        $newClass = new $class($existingClass);
        $this->assertSame($existingClass->format('Y-m-d H:i:s.u'), $newClass->format('Y-m-d H:i:s.u'));

        $existingClass = new \DateTime('2019-01-15 00:15:22.139302');
        $newClass = new $class($existingClass);
        $this->assertDateTime($newClass, 2019, 01, 15, 0, 15, 22);
        $this->assertSame(139302, $newClass->micro);
    }
}
