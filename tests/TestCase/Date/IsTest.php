<?php
declare(strict_types=1);

/**
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace Cake\Chronos\Test\TestCase\Date;

use Cake\Chronos\MutableDate;
use Cake\Chronos\Test\TestCase\TestCase;

class IsTest extends TestCase
{
    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testIsTodayTrue($class)
    {
        $this->assertTrue($class::now()->isToday());
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testIsTodayOtherTimezone($class)
    {
        $this->withTimezone('Asia/Tokyo', function () use ($class) {
            $today = $class::today();
            $this->assertSame('Asia/Tokyo', $today->tzName);
            $this->assertTrue($today->isToday());
        });
    }

    /**
     * @dataProvider dateClassProvider
     * @return void
     */
    public function testIsTodayFalseWithYesterday($class)
    {
        $scenario = function () use ($class) {
            $this->assertFalse($class::now()->subDays(1)->endOfDay()->isToday());
        };
        if ($class === MutableDate::class) {
            $scenario();
        } else {
            $this->deprecated($scenario);
        }
    }
}
