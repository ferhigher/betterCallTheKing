<?php

namespace App\Entity;

class Trial
{
    public function getVerdict(Contract $plaintiff, Contract $defendant): void
    {
        if ($plaintiff->getTotalValue() > $defendant->getTotalValue()) {
            dump('Plaintiff (' . $plaintiff->getTotalValue() . ' points) wins the trial over defendant (' . $defendant->getTotalValue() . ' points)');
        }
        if ($defendant->getTotalValue() > $plaintiff->getTotalValue()) {
            dump('Defendant (' . $defendant->getTotalValue() . ' points) wins the trial over plaintiff (' . $plaintiff->getTotalValue() . ' points)');
        }
        if ($defendant->getTotalValue() == $plaintiff->getTotalValue()) {
            dump('It\'s a draw. The score was ' . $plaintiff->getTotalValue() . ' all ');
        }
    }

}