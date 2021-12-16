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

        // TODO falta decir que firmas serían necesarias para ganar pq ademas habria que comprobar la presencia a no de rey en la firma,
        // en ese caso una V no valdria en el teorico caso de que haga falta un punto para ganar. En ese caso haría falta una N, que son dos puntos.
    }

}