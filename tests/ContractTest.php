<?php

namespace App\Tests;

use App\Model\Trial;
use App\Model\Contract;
use PHPUnit\Framework\TestCase;

class ContractTest extends TestCase
{
    public function testContractTotalValue(): void
    {
        $contract = new Contract('pepe', 'NN', false, false);
        $value = $contract->getTotalValue();
        $this->assertEquals(4,$value,'eo');
    }

    public function testKingInvalidatesValidator(): void
    {
        $contract = new Contract('pepe', 'KNV', false, false);
        $value = $contract->getTotalValue();
        $this->assertNotEquals(8,$value, 'King nullifies Validator');
    }

    public function testTrialVerdict(): void
    {
        $trial = new Trial();
        $plaintiff = new Contract('pepe', 'KK', false, false);
        $defendant = new Contract('pepe', 'KNN', false, false);
        $winner= $trial->getVerdict($plaintiff,$defendant);
        $this->assertEquals(-1, $winner,'Plaintiff wins the trial with this inputs');
    }
}
