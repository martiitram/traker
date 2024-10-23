<?php

namespace App\Command;

use App\Aplication\StartTask;
use App\Domain\StartTaskAlreadyExistException;
use App\Repository\BasicTimeRepository;
use App\Repository\SymfonyTaskRepository;
use App\Repository\UuidV7IdGeneratorRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:start',
    description: 'Start a task with the given name',
)]
class StartCommand extends Command
{
    private SymfonyTaskRepository $taskRepository;

    public function __construct(SymfonyTaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::OPTIONAL, 'task name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');

        if (!$name) {
            $io->error('You should provide a name for the task ');
            return Command::INVALID;
        }

        $startTask = new StartTask(
            $this->taskRepository,
            new BasicTimeRepository(),
            new UuidV7IdGeneratorRepository()
        );

        try {
            $startTask->execute($name);
            $io->success('Task Started');
            return Command::SUCCESS;

        } catch (StartTaskAlreadyExistException $e) {
            $io->error('You have already a started task');
            return Command::FAILURE;
        }
    }
}
