<?php

namespace App\Command;

use App\Aplication\StopTask;
use App\Domain\NoActiveTask;
use App\Domain\TaskAlreadyStopped;
use App\Repository\BasicTimeRepository;
use App\Repository\SymfonyTaskRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:stop',
    description: 'Add a short description for your command',
)]
class StopCommand extends Command
{
    private SymfonyTaskRepository $taskRepository;

    public function __construct(SymfonyTaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $startTask = new StopTask(
            $this->taskRepository,
            new BasicTimeRepository()
        );


        try {
            $startTask->execute();
            $io->success('Task stopped successfully');
            return Command::SUCCESS;

        } catch (NoActiveTask $e) {
            $io->error('Task Not Started');
            return Command::FAILURE;
        }catch (TaskAlreadyStopped $e) {
            $io->error('Task Already Stopped');
            return Command::FAILURE;
        }
    }
}
