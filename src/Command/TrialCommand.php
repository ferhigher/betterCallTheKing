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
    private Trial $trial;

    public function __construct(Trial $trial)
    {
        parent::__construct();
        $this->trial = $trial;
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

        if ($arg1) {
            $io->note(sprintf('You passed Plaintiff contract: %s', $arg1));
            $io->note(sprintf('%s signatures are worth: %s points', $plaintiff->getName(), $plaintiff->getTotalValue()));
        }
        if ($arg2) {
            $io->note(sprintf('You passed Defendant contract: %s', $arg2));
            $io->note(sprintf('%s signatures are worth: %s points', $defendant->getName(), $defendant->getTotalValue()));

        }

        $winner = $this->trial->getVerdict($plaintiff, $defendant);

        $trialResult = new TrialResultView();
        $message = $trialResult->prepareVerdict($winner, $plaintiff, $defendant);

        $io->note(sprintf('Final verdict: %s', $message));

        return Command::SUCCESS;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        echo "Initializing Trial...\n\r";
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $arg1 = $input->getArgument('arg1');
        $arg2 = $input->getArgument('arg2');

        if (!$this->validateSignature($arg1)) {
            $output->writeln("First argument is not valid. Maximum 3 signatures per contract");
            exit();
//            return Command::INVALID;

        }
        if (!$this->validateSignature($arg2)) {
            $output->writeln("Second argument is not valid. Maximum 3 signatures per contract");
            exit();
//            return Command::FAILURE;
        }
    }

    private function validateSignature($arg): bool
    {
        $length = strlen($arg);
        if ($length > 3) {
            return false;
        }
        return true;
    }


}
