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

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterval;
use Cake\Chronos\Test\TestCase\TestCase;
use DateInterval;

class IntervalAddTest extends TestCase
{
    public function testAdd()
    {
        $this->deprecated(function () {
            $ci = ChronosInterval::create(4, 3, 6, 7, 8, 10, 11)->add(new DateInterval('P2Y1M5DT22H33M44S'));
            $this->assertDateTimeInterval($ci, 6, 4, 54, 30, 43, 55);
        });
    }

    public function testAddWithDiffDateInterval()
    {
        $this->deprecated(function () {
            $now = Chronos::now();
            $diff = $now->diff($now->addWeeks(3));
            $ci = ChronosInterval::create(4, 3, 6, 7, 8, 10, 11, 123)->add($diff);
            $this->assertDateTimeInterval($ci, 4, 3, 70, 8, 10, 11, 123);
        });
    }

    public function testAddWithNegativeDiffDateInterval()
    {
        $this->deprecated(function () {
            $now = Chronos::now();
            $diff = $now->diff($now->subWeeks(3));
            $ci = ChronosInterval::create(4, 3, 6, 7, 8, 10, 11, 123)->add($diff);
            $this->assertDateTimeInterval($ci, 4, 3, 28, 8, 10, 11, 123);
        });
    }
}
