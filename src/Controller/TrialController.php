<?php

namespace App\Controller;

use App\Entity\Contract;
use App\Entity\Trial;
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
        $plaintiff = new Contract('plaintiff', $request->query->get('plaintiff'));
        $defendant = new Contract('defendant', $request->query->get('defendant'));

        $trial = new Trial(); // Todo: pelear la inyeccion de dependencia
        $trial->getVerdict($plaintiff, $defendant);

        if ($plaintiff->getHasHash()) {
            $diff = $trial->needMore($plaintiff, $defendant);
            dump('Plaintiff at least needs ' . ($diff + 1) . ' points to win this trial');
        }
        if ($defendant->getHasHash()) {
            $diff = $trial->needMore($defendant, $plaintiff);
            dump('Defendant at least needs ' . ($diff + 1) . ' points to win this trial');
        }

        return new JsonResponse(['status' => 'trial finished', Response::HTTP_OK]);
    }

}
