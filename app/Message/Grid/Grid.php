<?php

namespace App\Message\Grid;

use App\Message\Grid\Coordinate\Coordinate;
use App\Message\Grid\Robot\Robot;

class Grid
{
    private array $list;

    private array $robots;

    public function __construct(Coordinate ...$list)
    {
        $this->list = $list;
    }

    public function addRobot(Robot $robot) 
    {
        $this->robots[] = $robot;
    }

    public function getRobots() 
    {
        return $this->robots;
    }

    public function isValidCoordinate(Coordinate $position, string $orientation) 
    {
        foreach ($this->list as $gridPosition) {
            if ($gridPosition->getX() === $position->getX() && $gridPosition->getY() === $position->getY()) {
                return true;
            }
        }

        return false;
    }
}

