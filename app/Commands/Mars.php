<?php

namespace App\Commands;

use App\Message\Grid\Grid as GridGrid;
use App\Services\Grid\Grid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class Mars extends Command
{
    private Grid $gridService;

    private GridGrid $grid;

    public function __construct(Grid $gridService)
    {
        $this->gridService = $gridService;

        parent::__construct('mars');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $this->grid = $this->gridService->generateFromString(
            $helper->ask($input, $output, new Question('Gird Size: '))
        );

        for ($i=0; $i<3; $i++) {
            $robot = $this->gridService->createRobotFromString(
                $helper->ask($input, $output, new Question('Robot Start: '))
            );
            $this->gridService->processMovesFromString(
                $this->grid,
                $robot,
                $helper->ask($input, $output, new Question('Robot Actions: '))
            );
            $output->writeln('');

            $this->grid->addRobot($robot);
        }

        foreach($this->grid->getRobots() as $robot) {
            $positions = $robot->getPosition();
            $coordinate = $positions[0];

            $output->write($coordinate->getX() . ' ' . $coordinate->getY());
            $output->write(' ' . $positions[1]);

            if ($robot->isLost()) {
                $output->write(' LOST');
            }
            $output->writeln('');
        }

        return Command::SUCCESS;
    }
}

