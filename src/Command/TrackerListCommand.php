<?php

namespace App\Command;

use App\Aplication\ListTasks;
use App\Repository\BasicTimeRepository;
use App\Repository\SymfonyTaskRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:trackerList',
    description: 'Add a short description for your command',
)]
class TrackerListCommand extends Command
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
        $listTasks = new ListTasks(
            $this->taskRepository,
            new BasicTimeRepository()
        );
        $tasks = $listTasks->getTodayTasks();

        $io->text(' name, status, start_time, end_time, total_elapsed_time');
        foreach ($tasks as $task) {
            $msg = $task->getName()->name() . ', ';
            $msg .= ($task->isRunning() ? 'Running' : 'Stopped') . ', ';
            $msg .= $task->getStart()->format('Y-m-d H:i:s') . ', ';
            $msg .= (is_null($task->getEnd()) ? '' : $task->getEnd()->format('Y-m-d H:i:s')) . ', ';
            $msg .= (is_null($task->getEnd()) ? '' : (
                $task->getDateRange()->elapsedDatetime()->format('%Y-%m-%d %H:%i:%s')
                )) . ', ';
            $io->text($msg);
        }

        return Command::SUCCESS;

    }
}
