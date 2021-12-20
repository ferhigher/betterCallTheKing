<?php

namespace App\View;

use App\Model\Contract;
use App\Model\Trial;

class TrialResultView
{
    public function prepareVerdict(int $winner, Contract $plaintiff, Contract $defendant): string
    {
        return match ($winner) {
            -1 => sprintf('Plaintiff (%s points) wins the trial over defendant (%s points).', $plaintiff->getTotalValue(), $defendant->getTotalValue()),
            1 => sprintf('Defendant (%s points) wins the trial over plaintiff (%s points).', $defendant->getTotalValue(), $plaintiff->getTotalValue()),
            0 => sprintf('It is a draw. The score was %s all.', $plaintiff->getTotalValue()),
            default => '',
        };
    }

    /**
     * @param Trial $trial
     * @param Contract $first
     * @param Contract $second
     * @return string
     */
    public function getMessage(Trial $trial, Contract $first, Contract $second): string
    {
        $diff = $trial->needMore($first, $second);
        $message = sprintf('%s at least needs %s points to win this trial. That means: ',$first->getName(), ($diff + 1));
        $conjecture = $first->valueToSignatures($diff + 1, $first->getHasKing());

        $message .= sprintf('%s signatures type K, ', $conjecture['K']);
        $message .= sprintf('%s signatures type N, ', $conjecture['N']);
        $message .= sprintf(' and %s signatures type V. ', $conjecture['V']);

        return $message;
    }
}