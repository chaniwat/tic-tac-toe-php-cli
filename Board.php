<?php

class Board
{
    private $matrixSize;
    private $p1Char = 'x';
    private $p2Char = 'o';

    private $slots = [];
    private $isFinished = false;
    private $winnerPlayer = 0;
    private $winPattern = '';

    public function __construct($matrixSize)
    {
        $this->matrixSize = $matrixSize;
        $this->initBoard();
    }

    public function isFinished()
    {
        return $this->isFinished;
    }

    public function getWinnerPlayer()
    {
        return $this->winnerPlayer;
    }

    public function getWinPattern()
    {
        return $this->winPattern;
    }

    /**
     * @return bool True if valid
     */
    public function validatePosition($v)
    {
        return $v > 0 && $v <= $this->matrixSize;
    }

    public function getSlotValue(int $x, int $y)
    {
        if (!$this->validatePosition($y)) {
            return -1;
        }
        if (!$this->validatePosition($y)) {
            return -2;
        }
        return $this->slots[$y - 1][$x - 1];
    }

    public function insertSlot(int $player, int $x, int $y)
    {
        if ($player !== 1 && $player !== 2) {
            //echo "Invalid Player".PHP_EOL;
            return 1;
        }
        if (!$this->validatePosition($y)) {
            return 2;
        }
        if (!$this->validatePosition($y)) {
            return 3;
        }
        if ($this->slots[$y - 1][$x - 1] !== 0) {
            return 4;
        }
        
        $this->slots[$y - 1][$x - 1] = $player;
        return true;
    }

    public function updateBoardState()
    {
        // check winner
        $winner = $this->calcBoardWinner();
        if ($winner !== 0) {
            $this->isFinished = true;
            $this->winnerPlayer = $winner;
        }
        if ($this->calcBoardFull()) {
            $this->isFinished = true;
            $this->winnerPlayer = 0;
            $this->winPattern = '';
        }
    }

    public function printBoard()
    {
        for ($y = 0; $y < ($this->matrixSize * 2) - 1; $y++) {
            for ($x = 0; $x < $this->matrixSize; $x++) {
                if ($y % 2 === 0) {
                    echo ($x > 0 ? '|' : '').$this->getSlotChar($this->slots[(int) floor($y / 2)][$x]);
                } else {
                    echo ($x === 0 ? '-' : ' -');
                }
            }
            echo PHP_EOL;
        }
    }

    public function resetBoard()
    {
        $this->slots = [];
        $this->isFinished = false;
        $this->winnerPlayer = '';
        $this->winPattern = '';
        $this->initBoard();
    }

    private function initBoard()
    {
        for ($y = 0; $y < $this->matrixSize; $y++) {
            $this->slots[$y] = [];
            for ($x = 0; $x < $this->matrixSize; $x++) {
                $this->slots[$y][$x] = 0;
            }
        }
    }

    private function calcBoardWinner()
    {
        // 1. by -
        for ($y = 0; $y < $this->matrixSize; $y++) {
            $p = 0;
            for ($x = 0; $x < $this->matrixSize; $x++) {
                $sp = $this->slots[$y][$x];
                if ($x === 0) {
                    $p = $sp;
                } else if ($p !== $sp) {
                    continue 2;
                }
            }
            // by - contains winner
            if ($p !== 0) {
                $this->winPattern = '-';
                return $p;
            }
        }
        // 2. by |
        for ($x = 0; $x < $this->matrixSize; $x++) {
            $p = 0;
            for ($y = 0; $y < $this->matrixSize; $y++) {
                $sp = $this->slots[$y][$x];
                if ($y === 0) {
                    $p = $sp;
                } else if ($p !== $sp) {
                    continue 2;
                }
            }
            // by | contains winner
            if ($p !== 0) {
                $this->winPattern = '|';
                return $p;
            }
        }
        // 3. by \
        $p = 0;
        for ($s = 0; $s < $this->matrixSize; $s++) {
            $sp = $this->slots[$s][$s];
            if ($s === 0) {
                $p = $sp;
            } else if ($p !== $sp) {
                $p = 0;
                break;
            }
        }
        // by \ contains winner
        if ($p !== 0) {
            $this->winPattern = '\\';
            return $p;
        }
        // 4. by /
        $p = 0;
        $y = $this->matrixSize - 1;
        for ($x = 0; $x < $this->matrixSize; $x++) {
            $sp = $this->slots[$y][$x];
            if ($x === 0 && $y === $this->matrixSize - 1) {
                $p = $sp;
            } else if ($p !== $sp) {
                $p = 0;
                break;
            }
            $y--;
        }
        // by / contains winner
        if ($p !== 0) {
            $this->winPattern = '/';
            return $p;
        }
        // no winner
        return 0;
    }

    private function calcBoardFull()
    {
        for ($x = 0; $x < $this->matrixSize; $x++) {
            for ($y = 0; $y < $this->matrixSize; $y++) {
                if ($this->slots[$y][$x] === 0) {
                    return false;
                }
            }
        }
        return true;
    }

    private function getSlotChar($type)
    {
        switch ($type) {
            case 1: return $this->p1Char;
            case 2: return $this->p2Char;
            default: return ' ';
        }
    }
}
