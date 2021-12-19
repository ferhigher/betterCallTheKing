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

        if ($plaintiff->getHasHash() && !$defendant->getHasHash()) {
            $message = $this->getMessage($trial, $plaintiff, $defendant);
        }
        if ($defendant->getHasHash() && !$plaintiff->getHasHash()) {
            $message = $this->getMessage($trial, $defendant, $plaintiff);
        }
        return $message;
    }

    /**
     * @param Trial $trial
     * @param Contract $defendant
     * @param Contract $plaintiff
     * @return string
     */
    public function getMessage(Trial $trial, Contract $defendant, Contract $plaintiff): string
    {
        $diff = $trial->needMore($defendant, $plaintiff);
        $message = sprintf('Defendant at least needs %s points to win this trial. That means: ', ($diff + 1));
        $conjecture = $defendant->valueToSignatures($diff + 1, $defendant->getHasKing());

        foreach ($conjecture as $item) {
            $letter = array_search($item, $conjecture);
            $submessage = sprintf('%s signatures type %s, ', $item, $letter);
            $message .= $submessage;
        }
        return $message;
    }

}
