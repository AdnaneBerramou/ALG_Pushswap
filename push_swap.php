<?php

require('./sort_funcs.php');

function pushSwap($la) {
    $uselessVal = array_shift($la);
    $sortedLa = $la;
    sort($sortedLa, SORT_NUMERIC);
    if ($sortedLa == $la) {
        echo "\n";
    } else {
        $commands = [];
        $la = array_map(function($n) {
            return intval($n);
        }, $la);
        $ls = ['la' => $la, 'lb' => []];
        while (!empty($ls['la'])) {
            $sortedLa = $la;
            sort($sortedLa, SORT_NUMERIC);
            if ($sortedLa == $la)
        break;
            $min = min($ls['la']);
            $i = array_search($min, $ls['la']);
            if ($i == array_key_first($ls['la'])) {
                $ls = pb($ls);
                array_push($commands, 'pb');
            } elseif ($i == array_key_last($ls['la'])) {
                $ls = rra($ls);
                $ls = pb($ls);
                array_push($commands, 'rra', 'pb');
            } elseif ($i == 1) {
                $ls = sa($ls);
                $ls = pb($ls);
                array_push($commands, 'sa', 'pb');
            } else {
                $op = 'ra';
                $ra = intval(substr(strval(array_key_first($ls['la']) - $i), 1));
                $rra = intval(array_key_last($ls['la'])) - $i;
                if ($rra < $ra) {
                    $op = 'rra';
                    $i = $rra + 1;
                }
                for ($j = 0; $j < $i ; $j++) {
                    $ls = $op($ls);
                    array_push($commands, $op);
                }
                $ls = pb($ls);
                array_push($commands, 'pb');
            }
        }
        while(!empty($ls['lb'])) {
            $ls = pa($ls);
            array_push($commands, 'pa');
        }
        echo implode(' ', $commands) . "\n";
    }
}

pushSwap($argv);