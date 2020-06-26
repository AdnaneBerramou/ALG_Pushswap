<?php

require('./sort_funcs.php');

function pushSwap($la) {
    $sortFuncs = ['sa', 'sb', 'sc', 'pa', 'pb', 'ra', 'rb', 'rr', 'rra', 'rrb', 'rrr'];
    $uselessVal = array_shift($la);
    $lb = [];
    $sortedLa = $la;
    sort($sortedLa, SORT_NUMERIC);
    $commands = [];
    if ($sortedLa == $la) {
    } else {
        $la = array_map(function($n) {
            return intval($n);
        }, $la);
        while (!empty($la)) {
            $count = count($la);
            for ($i = array_key_first($la); $i < $count ; $i++) {
                $min = min($la);
                $current = $la[$i];
                if ($min == $current) {
                    if ($i == 0) {
                        $ls = pb($la, $lb);
                        $la = array_values($ls['la']);
                        $lb = array_values($ls['lb']);
                        array_push($commands, 'pb');
                    } elseif ($i == array_key_last($la)) {
                        $ls = rra($la, $lb);
                        $ls = pb($ls['la'], $ls['lb']);
                        $la = array_values($ls['la']);
                        $lb = array_values($ls['lb']);
                        array_push($commands, 'rra', 'pb');
                    } elseif ($i == 1) {
                        $ls = sa($la, $lb);
                        $ls = pb($ls['la'], $ls['lb']);
                        $la = array_values($ls['la']);
                        $lb = array_values($ls['lb']);
                        array_push($commands, 'sa', 'pb');
                    } else {
                        $ls = ['la' => $la, 'lb' => $lb];
                        for ($j = 0; $j < ($i + 1) ; $j++) {
                            $ls = pb($ls['la'], $ls['lb']);
                            array_push($commands, 'pb');
                        }
                        $ls = rb($ls['la'], $ls['lb']);
                        array_push($commands, 'rb');
                        for ($j = 0; $j < $i ; $j++) {
                            $ls = pa($ls['la'], $ls['lb']);
                            array_push($commands, 'pa');
                        }
                        $ls = rrb($ls['la'], $ls['lb']);
                        $la = array_values($ls['la']);
                        $lb = array_values($ls['lb']);
                        array_push($commands, 'rrb');
                    }
                }
                $count = count($la);
            }
        }
        while(!empty($lb)) {
            $ls = ['la' => $la, 'lb' => $lb];
            $ls = pa($ls['la'], $ls['lb']);
            array_push($commands, 'pa');
            $la = $ls['la'];
            $lb = $ls['lb'];
        }
        echo implode(' ', $commands) . "\n";
    }
}

pushSwap($argv);