<?php

namespace App\View;

use App\Model\Contract;

class TrialResultView
{
    public function prepareVerdict(int $winner, Contract $plaintiff, Contract $defendant): string
    {
        return match ($winner) {
            -1 => sprintf('Plaintiff (%s points) wins the trial over defendant ( %s  points)', $plaintiff->getTotalValue(), $defendant->getTotalValue()),
            1 => sprintf('Defendant (%s points) wins the trial over plaintiff ( %s  points)', $defendant->getTotalValue(), $plaintiff->getTotalValue()),
            0 => sprintf('It\'s a draw. The score was %s all.', $plaintiff->getTotalValue()),
            default => '',
        };
    }
}