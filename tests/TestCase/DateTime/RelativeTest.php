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

class RelativeTest extends TestCase
{
    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSecondsSinceMidnight($class)
    {
        $d = $class::today()->addSeconds(30);
        $this->assertSame(30, $d->secondsSinceMidnight());

        $d = $class::today()->addDays(1);
        $this->assertSame(0, $d->secondsSinceMidnight());

        $d = $class::today()->addDays(1)->addSeconds(120);
        $this->assertSame(120, $d->secondsSinceMidnight());

        $d = $class::today()->addMonths(3)->addSeconds(42);
        $this->assertSame(42, $d->secondsSinceMidnight());
    }

    /**
     * @dataProvider classNameProvider
     * @return void
     */
    public function testSecondsUntilEndOfDay($class)
    {
        $d = $class::today()->endOfDay();
        $this->assertSame(0, $d->secondsUntilEndOfDay());

        $d = $class::today()->endOfDay()->subSeconds(60);
        $this->assertSame(60, $d->secondsUntilEndOfDay());

        $d = $class::create(2014, 10, 24, 12, 34, 56);
        $this->assertSame(41103, $d->secondsUntilEndOfDay());

        $d = $class::create(2014, 10, 24, 0, 0, 0);
        $this->assertSame(86399, $d->secondsUntilEndOfDay());
    }
}
