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

class CreateFromTimestampTest extends TestCase
{
    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateReturnsDatingInstance($class)
    {
        $d = $class::createFromTimestamp($class::create(1975, 5, 21, 22, 32, 5)->timestamp);
        $this->assertDateTime($d, 1975, 5, 21, 22, 32, 5);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromTimestampUsesDefaultTimezone($class)
    {
        $d = $class::createFromTimestamp(0);

        // We know Toronto is -5 since no DST in Jan
        $this->assertSame(1969, $d->year);
        $this->assertSame(-5 * 3600, $d->offset);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromTimestampWithDateTimeZone($class)
    {
        $d = $class::createFromTimestamp(0, new \DateTimeZone('UTC'));
        $this->assertSame('UTC', $d->tzName);
        $this->assertDateTime($d, 1970, 1, 1, 0, 0, 0);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromTimestampWithString($class)
    {
        $d = $class::createFromTimestamp(0, 'UTC');
        $this->assertDateTime($d, 1970, 1, 1, 0, 0, 0);
        $this->assertSame(0, $d->offset);
        $this->assertSame('UTC', $d->tzName);
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testCreateFromTimestampGMTDoesNotUseDefaultTimezone($class)
    {
        $this->deprecated(function () use ($class) {
            $d = $class::createFromTimestampUTC(0);
            $this->assertDateTime($d, 1970, 1, 1, 0, 0, 0);
            $this->assertSame(0, $d->offset);
        });
    }
}
