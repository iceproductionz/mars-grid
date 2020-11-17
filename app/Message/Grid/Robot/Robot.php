<?php

namespace App\Message\Grid\Robot;

use App\Message\Grid\Coordinate\Coordinate;

class Robot
{
    public const ORIENTATION_NORTH = 'N';
    public const ORIENTATION_EAST  = 'E';
    public const ORIENTATION_WEST  = 'W';
    public const ORIENTATION_SOUTH = 'S';

    public const ORIENTATIONS = [
        self::ORIENTATION_NORTH,
        self::ORIENTATION_EAST,
        self::ORIENTATION_SOUTH,
        self::ORIENTATION_WEST,
    ];

    private bool $lost = false;

    private Coordinate $position;

    private string $orientation;

    /**
     *
     * @param Coordinate $position
     * @param string $orientation
     */
    public function __construct(Coordinate $position, string $orientation)
    {
        $this->position = $position;
        $this->orientation = $orientation;
    }

    public function newPosition(Coordinate $position, string $orientation)
    {
        $this->position = $position;
        $this->orientation = $orientation;
    }

    public function setLost(bool $lost)
    {
        $this->lost = $lost;
    }

    public function isLost()
    {
        return $this->lost;
    }


    public function getPosition(): array
    {
        return [
            $this->position,
            $this->orientation,
        ];
    }
}

