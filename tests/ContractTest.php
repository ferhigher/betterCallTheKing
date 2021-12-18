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
        $plaintiff = new Contract('pepe', 'KNV', false, false);
        $defendant = new Contract('pepe', 'KNN', false, false);
        $trial->getVerdict($plaintiff,$defendant);

        // y aqui la he cagao pq me toca rehacer todo el metodo pq no hay manera de evaluar los dumps

    }
}
