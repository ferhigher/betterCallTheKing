<?php

namespace App\Model;


class Trial
{
    public function getVerdict(Contract $plaintiff, Contract $defendant): int
    {
        if ($plaintiff->getTotalValue() > $defendant->getTotalValue()) {
            return -1;
        }
        if ($defendant->getTotalValue() > $plaintiff->getTotalValue()) {
            return 1;
        }
        if ($defendant->getTotalValue() == $plaintiff->getTotalValue()) {
            return 0;
        }
    }

    public function needMore(Contract $hashed, Contract $complete): int
    {
        if ($hashed->getTotalValue() > $complete->getTotalValue()) {
            // entonces ya ha ganado aun teniendo un hash
            return -1;
        }
        if ($hashed->getTotalValue() == $complete->getTotalValue()) {
            return 0;
        }
        if ($hashed->getTotalValue() < $complete->getTotalValue()) {
            return $complete->getTotalValue() - $hashed->getTotalValue();
        }
    }

}