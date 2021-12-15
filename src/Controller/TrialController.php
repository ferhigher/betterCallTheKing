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

        $this->getVerdict($plaintiff->getTotalValue(),$defendant->getTotalValue());

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
}
