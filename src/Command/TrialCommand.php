<?php

namespace App\Command;

use App\Model\Contract;
use App\Model\Trial;
use App\View\TrialResultView;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:Trial',
    description: 'Add a short description for your command',
)]
class TrialCommand extends Command
{
    const MAX = 3; // max number of signatures per contract
    private Trial $trial;
    private TrialResultView $trialResultView;

    public function __construct(Trial $trial, TrialResultView $trialResultView)
    {
        parent::__construct();
        $this->trial = $trial;
        $this->trialResultView = $trialResultView;
    }


    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::REQUIRED, 'Plaintiff signatures')
            ->addArgument('arg2', InputArgument::REQUIRED, 'Defendant signatures')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');
        $plaintiff = new Contract('Plaintiff', $arg1);
        $arg2 = $input->getArgument('arg2');
        $defendant = new Contract('Defendant', $arg2);


        if (!$this->validateSignature($arg1)) {
            $output->writeln("First argument is not valid. Maximum 3 signatures per contract");
            return Command::INVALID;

        }
        if (!$this->validateSignature($arg2)) {
            $output->writeln("Second argument is not valid. Maximum 3 signatures per contract");
            return Command::INVALID;
        }

        if ($arg1) {
            $io->note(sprintf('You passed Plaintiff contract: %s', $arg1));
            $io->note(sprintf('%s signatures are worth: %s points', $plaintiff->getName(), $plaintiff->getTotalValue()));
        }
        if ($arg2) {
            $io->note(sprintf('You passed Defendant contract: %s', $arg2));
            $io->note(sprintf('%s signatures are worth: %s points', $defendant->getName(), $defendant->getTotalValue()));

        }

        $winner = $this->trial->getVerdict($plaintiff, $defendant);

        $message = $this->trialResultView->prepareVerdict($winner, $plaintiff, $defendant);
        $conjecture = $this->hashedNeeds($plaintiff, $this->trial, $defendant);

        $io->note(sprintf('Final verdict: %s', $message));
        $io->note(sprintf('Conjecture to win: %s', $conjecture));

        return Command::SUCCESS;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        echo "Initializing Trial...\n\r";
    }

    private function validateSignature($arg): bool
    {
        $length = strlen($arg);
        if ($length > self::MAX) {
            return false;
        }
        return true;
    }

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
