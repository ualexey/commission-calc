<?php

namespace App\Command;

use App\Service\CommissionProcessor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


class ProcessCommissionsCommand extends Command
{

    private CommissionProcessor $processor;

    public function __construct(CommissionProcessor $processor)
    {
        parent::__construct();
        $this->processor = $processor;
    }

    protected function configure()
    {
        $this
            ->setName('process:commissions')
            ->addArgument('file', InputArgument::REQUIRED, 'Path to input file')
            ->setDescription('Process commissions from input file')
            ->setHelp('This command processes commission data from an input file.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = $input->getArgument('file');
        $io = new SymfonyStyle($input, $output);

        try {
            $this->processor->processFile($file);
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
