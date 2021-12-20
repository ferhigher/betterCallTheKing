<?php

namespace App\Tests;

use App\Model\Contract;
use App\Model\Trial;
use PHPUnit\Framework\TestCase;

class TrialTest extends TestCase
{
    public function testTrialVerdict(): void
    {
        $trial = new Trial();
        $plaintiff = new Contract('pepe', 'KK', false, false);
        $defendant = new Contract('pepe', 'KNN', false, false);
        $winner= $trial->getVerdict($plaintiff,$defendant);
        $this->assertEquals(-1, $winner,'Plaintiff wins the trial with this inputs');
    }

}