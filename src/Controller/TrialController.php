<?php

namespace App\Controller;

use App\Model\Contract;
use App\Model\Trial;
use App\View\TrialResultView;
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
    public function trial(Request $request, Trial $trial): JsonResponse
    {
        $plaintiff = new Contract('plaintiff', $request->query->get('plaintiff'));
        $defendant = new Contract('defendant', $request->query->get('defendant'));


        $winner = $trial->getVerdict($plaintiff, $defendant);

        $trialResult = new TrialResultView();
        $message = $trialResult->prepareVerdict($winner, $plaintiff, $defendant);


        $conjecture = $this->hashedNeeds($plaintiff, $trial, $defendant);

        return new JsonResponse(['Status' => 'Trial finished',
            'Verdict' => $message,
            'Conjecture to win:' => $conjecture
        ], Response::HTTP_OK);
    }

    /**
     * @param Contract $plaintiff
     * @param Trial $trial
     * @param Contract $defendant
     * @return string
     */
    public function hashedNeeds(Contract $plaintiff, Trial $trial, Contract $defendant): string
    {
        $message = '';
        $trialResult = new TrialResultView();

        if ($plaintiff->getHasHash() && !$defendant->getHasHash()) {
            $message = $trialResult->getMessage($trial, $plaintiff, $defendant);
        }
        if ($defendant->getHasHash() && !$plaintiff->getHasHash()) {
            $message = $trialResult->getMessage($trial, $defendant, $plaintiff);
        }
        return $message;
    }

}
