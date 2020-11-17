<?php

namespace App\Services\Grid;

use App\Exception\PositionOutOfBoundException;
use App\Message\Grid\Coordinate\Coordinate;
use App\Message\Grid\Grid as GridGrid;
use App\Message\Grid\Robot\Robot;

class Grid
{
    public function generateFromString(string $inputCoordinate): GridGrid
    {
        $coordiantes = explode(' ', $inputCoordinate);

        return $this->generate(trim($coordiantes[0]), trim($coordiantes[1]));
    }

    public function generate(int $maxX, int $maxY): GridGrid
    {
        $list = [];
        if ($maxX >= 50) {
            throw new PositionOutOfBoundException('x position not valid: ' . $maxX);
        }
        if ($maxY >= 50) {
            throw new PositionOutOfBoundException('y position not valid: ' . $maxY);
        }
        for($x=0; $x<$maxX; $x++) {
            for($y=0; $y<$maxY; $y++) {
                $list[] = new Coordinate($x, $y);
            }
        }

        return new GridGrid(...$list);
    }

    public function createRobotFromString(string $input)
    {
        $dataPoints = explode(' ', $input);

        return $this->createRobot(
            (int)$dataPoints[0],
            (int)$dataPoints[1],
            $dataPoints[2]
        );
    }

    public function createRobot(int $xPos, int $yPos, string $orientation): Robot
    {
        return new Robot(
            new Coordinate($xPos, $yPos),
            $orientation
        );
    }

    /**
     * @param GridGrid $grid
     * @param Robot $robot
     * @param string $moves
     * @return void
     */
    public function processMovesFromString(GridGrid $grid, Robot $robot, string $moves)
    {
        return $this->processMoves(
            $grid,
            $robot,
            str_split($moves),
        );
    }

    public function processMoves(GridGrid $grid, Robot $robot, array $moves)
    {
        foreach ($moves as $move) {
            $this->move($grid, $robot, $move);
        }
    }

    public function move(GridGrid $grid, Robot $robot, string $action)
    {
        if ($action === 'R') {
            $newRobot = $this->rotateRight($robot);
        }

        if ($action === 'L') {
            $newRobot = $this->rotateLeft($robot);
        }

        if ($action === 'F') {
            $newRobot = $this->moveForward($robot);
        }

        $result = $grid->isValidCoordinate(...$newRobot->getPosition());
        if ($robot->isLost() === false) {
            if ($result === true) {
                $robot->newPosition(...$newRobot->getPosition());
            }
        }
        

        if ($result === false && $robot->isLost() === false) {
            $robot->newPosition(...$newRobot->getPosition());
            $robot->setLost(true);
        }
    }

    public function moveForward(Robot $robot): Robot
    {
        $position    = $robot->getPosition();
        $coordinate  = $position[0];
        $orientation = $position[1];

        if ($orientation === Robot::ORIENTATION_NORTH) {
            $newCoordinate = new Coordinate($coordinate->getX(), $coordinate->getY() + 1);
        }

        if ($orientation === Robot::ORIENTATION_EAST) {
            $newCoordinate = new Coordinate($coordinate->getX() + 1, $coordinate->getY());
        }

        if ($orientation === Robot::ORIENTATION_SOUTH) {
            $newCoordinate = new Coordinate($coordinate->getX(), $coordinate->getY() - 1);
        }

        if ($orientation === Robot::ORIENTATION_WEST) {
            $newCoordinate = new Coordinate($coordinate->getX() - 1, $coordinate->getY());
        }

        return new Robot($newCoordinate, $orientation);
    }

    public function rotateRight(Robot $robot): Robot
    {
        $position    = $robot->getPosition();
        $coordinate  = $position[0];
        $orientation = $position[1];

        $key = array_search($orientation, Robot::ORIENTATIONS); 
        $newKey = $key + 1;
        if ($newKey === 4) {
            $newKey = 0;
        }

        return new Robot($coordinate, Robot::ORIENTATIONS[$newKey]);
    }
    
    public function rotateLeft(Robot $robot): Robot
    {
        $position    = $robot->getPosition();
        $coordinate  = $position[0];
        $orientation = $position[1];

        $key = array_search($orientation, Robot::ORIENTATIONS); 

        $newKey = $key - 1;
        if ($newKey === -1) {
            $newKey = 3;
        }

        return new Robot($coordinate, Robot::ORIENTATIONS[$newKey]);
    }
}