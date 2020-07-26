<?php

require('./sort_funcs.php');

function colors($list) {
    $rules = [];
    $colors = [["\033[92m", "\033[32m", "\033[93m", "\033[33m", "\033[91m", "\033[31m", "\033[95m", "\033[35m", "\033[94m", "\033[34m"], ["\033[32m", "\033[33m", "\033[31m", "\033[35m", "\033[34m"], ["\033[32m", "\033[33m", "\033[31m"]];
    $count = count($list);
    if ($count == 2) {
        $rules = [$list[0] => $colors[2][0], $list[1] => $colors[2][1]] ;
    } else {
        if ($count >= 3 && $count < 10) {
            $p = [$count / ($count -1), 1];
        } elseif ($count >= 10) {
            $p = [$count / 10, 0];
        }
        $chunks = array_chunk($list, ceil($p[0]));
        foreach ($chunks as $key => $chunk) {
            foreach ($chunk as $keyII => $value) {
                $rules[$value] = $colors[$p[1]][$key];
            }
        }
    }
    return $rules;
}

function display($ls, $length, $colors) {
    $key = max(array_key_last($ls['la']), array_key_last($ls['lb']));
    $blank = str_repeat(' ', $length + 4);
    $line = "+" . str_repeat('-', $length + 4) . "+" . str_repeat('-', $length + 4) . "+\n";
    $toEcho = $line . "|  \033[32mLA\033[0m" . str_repeat(' ', ($length)) . "|  \033[32mLB\033[0m"  . str_repeat(' ', ($length)) . "|\n" . $line;
    for ($i=0; $i <= $key; $i++) {
        $a = '|' . $blank . '|';
        $b = $blank . "|\n";
        if (isset($ls['la'][$i])) {
            $a = strval($ls['la'][$i]);
            $aLen = strlen($a);
            $a = "|" . $colors[$a] . str_repeat(' ', ($length - $aLen + 2)) . $a . "  \033[0m|";
        }
        if (isset($ls['lb'][$i])) {
            $b = strval($ls['lb'][$i]);
            $bLen = strlen($b);
            $b = $colors[$b] . str_repeat(' ', ($length - $bLen + 2)) . $b . "  \033[0m|\n";
        }
        $toEcho .= $a.$b;
    }
    $toEcho .= $line;
    system("clear");
    echo $toEcho;
}

function main($list) {
    $s = $list;
    sort($s, SORT_NUMERIC);
    if ($s == $list) {
        echo "\033[35mAlready sorted.\033[0m\n";
    } else {
        $colors = colors($s);
        exec("php ../push_swap.php " . implode(" ", $list), $out);
        $operations = [];
        foreach ($out as $key => $value) {
            $operations = array_merge($operations, explode(' ', $value));
        }
        $ls = ['la' => $list, 'lb' => []];
        $longestNumber = strval(max($ls['la']));
        display($ls, strlen($longestNumber), $colors);
        foreach ($operations as $key => $op) {
            $ls = $op($ls);
            // usleep(1000);
            display($ls, strlen($longestNumber), $colors);
        }
    }
}


// unset($argv[0]);
$argv = [];
for ($i=0; $i < 150; $i++) {
    repeat:
    $n = rand(1, 10000);
    if (in_array($n, $argv)){
        goto repeat;
    } else{
        $argv[] = $n;
    }
}

main($argv);