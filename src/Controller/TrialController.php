<?php

namespace App\Controller;

use App\Entity\Contract;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrialController extends AbstractController
{
    /**
     * @Route ("/trial", name="trial", methods={"GET"})
     */
    public function trial(Request $request): JsonResponse
    {
        $plaintiff = new Contract('plaintiff', $request->query->get('plaintiff'), false, false);
        $defendant = new Contract('defendant', $request->query->get('defendant'), false, false);

        $this->getVerdict($plaintiff->getTotalValue(), $defendant->getTotalValue());

        if ($plaintiff->getHasHash()) {
            $diff = $this->needMore($plaintiff, $defendant);
            dump('Plaintiff at least needs ' . ($diff +1) . ' points to win this trial');
        }
        if($defendant->getHasHash()){
            $diff = $this->needMore($defendant, $plaintiff);
            dump('Defendant at least needs ' . ($diff +1) . ' points to win this trial');
        }

        return new JsonResponse(['status' => 'trial finished', Response::HTTP_OK]);

    }

    public function getVerdict(int $plaintiff, int $defendant): void
    {
        if ($plaintiff > $defendant) {
            dump('Plaintiff (' . $plaintiff . ' points) wins the trial over defendant (' . $defendant . ' points)');
        }
        if ($defendant > $plaintiff) {
            dump('Defendant (' . $defendant . ' points) wins the trial over plaintiff (' . $plaintiff . ' points)');
        }
        if ($defendant == $plaintiff) {
            dump('It\'s a draw. The score was ' . $plaintiff . ' all ');
        }
    }

    private function needMore(Contract $hashed, Contract $complete): int
    {

        if($hashed->getTotalValue() > $complete->getTotalValue()){
            // entonces ya ha ganado aun teniendo un hash
            return -1;
        }
        if($hashed->getTotalValue() == $complete->getTotalValue()){
            return 0;
        }
        if($hashed->getTotalValue() < $complete->getTotalValue()){
            return $complete->getTotalValue() - $hashed->getTotalValue();
        }

        // TODO falta decir que firmas serían necesarias para ganar pq ademas habria que comprobar la presencia a no de rey en la firma,
        // en ese caso una V no valdria en el teorico caso de que haga falta un punto para ganar. En ese caso haría falta una N, que son dos puntos.
    }
}
