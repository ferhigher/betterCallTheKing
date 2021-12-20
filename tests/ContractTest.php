<?php

namespace App\Tests;

use App\Model\Contract;
use PHPUnit\Framework\TestCase;

class ContractTest extends TestCase
{
    public function testContractTotalValue(): void
    {
        $contract = new Contract('pepe', 'NN', false, false);
        $value = $contract->getTotalValue();
        $this->assertEquals(4, $value, 'eo');
    }

    public function testKingInvalidatesValidator(): void
    {
        $contract = new Contract('pepe', 'KNV', false, false);
        $value = $contract->getTotalValue();
        $this->assertNotEquals(8, $value, 'King nullifies Validator');
    }

}
