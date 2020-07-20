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
                        $op = 'ra';
                        $ra = intval(substr(strval(array_key_first($ls['la']) - $i), 1));
                        $rra = intval(array_key_last($ls['la'])) - $i;
                        if ($rra < $ra) {
                            $op = 'rra';
                            $i = $rra + 1;
                        }
                        for ($j = 0; $j < $i ; $j++) {
                            $ls = $op($ls['la'], $ls['lb']);
                            array_push($commands, $op);
                        }
                        $ls = pb($ls['la'], $ls['lb']);
                        $la = array_values($ls['la']);
                        $lb = array_values($ls['lb']);
                        array_push($commands, 'pb');
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
        echo implode(' ', $ls['la']) . "\n";
        echo implode(' ', $ls['lb']) . "\n";
    }
}

pushSwap($argv);