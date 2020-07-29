<?php

require_once('./cli.php');
require_once('./board.php');

echo "Welcome to tic-tac-toe game".PHP_EOL;

// game loop
$abort = false;
while (!$abort) {
    // setup phase
    $inputMatrixSize = waitAndReadInput("Please enter square matrix size: ");
    if (!ctype_digit($inputMatrixSize)) {
        echo "Invalid matrix size, try again".PHP_EOL;
        continue;
    }
    $inputMatrixSize = (int) $inputMatrixSize;

    // pre-game phase
    $board = new Board($inputMatrixSize);
    $currentPlayer = 1;

    echo 'Start!'.PHP_EOL;
    $board->printBoard();
    while (!$board->isFinished()) {
        $inputPositionX = 0; $inputPositionY = 0;
        // input position y
        while (true) {
            $inputPositionY = waitAndReadInput("Input position Y for player {$currentPlayer}: ");
            if (!ctype_digit($inputPositionY) || !$board->validatePosition((int) $inputPositionY)) {
                echo "Invalid position, try again".PHP_EOL;
                continue;
            }
            $inputPositionY = (int) $inputPositionY;
            break;
        }
        // input position x
        while (true) {
            $inputPositionX = waitAndReadInput("Input position X for player {$currentPlayer}: ");
            if (!ctype_digit($inputPositionX) || !$board->validatePosition((int) $inputPositionX)) {
                echo "Invalid position, try again".PHP_EOL;
                continue;
            }
            $inputPositionX = (int) $inputPositionX;
            break;
        }
        // insert to board
        $err = $board->insertSlot($currentPlayer, $inputPositionX, $inputPositionY);
        // handle insert result
        if ($err === true) {
            // swap player
            $currentPlayer = $currentPlayer === 1 ? 2 : 1;
            // update board state
            $board->updateBoardState();
            // print board
            $board->printBoard();
        } else {
            // handle set error
            switch ($err) {
                case 2:
                    echo "Invalid Y position".PHP_EOL;
                    break;
                case 3:
                    echo "Invalid X position".PHP_EOL;
                    break;
                case 4:
                    $p = $board->getSlotValue($inputPositionX, $inputPositionY);
                    echo "Position x={$inputPositionX}, y={$inputPositionY} already insert by player {$p}".PHP_EOL;
                    break;
            }
        }
    }

    // finish
    if ($board->getWinnerPlayer() !== 0) {
        echo "Game finished, player {$board->getWinnerPlayer()} win by pattern {$board->getWinPattern()}".PHP_EOL;
    } else {
        echo "Game finished, no player win".PHP_EOL;
    }

    $continue = waitAndReadInput("Play again? [Y/N]: ");
    if (strtolower($continue) !== 'y') {
        $abort = true;
    }
}
