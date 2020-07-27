<?php


function sa($ls) {
    if (isset($ls['la'][0]) && isset($ls['la'][1])) {
        $first = $ls['la'][0];
        $ls['la'][0] = $ls['la'][1];
        $ls['la'][1] = $first;
    }
    return ['la' => array_values($ls['la']), 'lb' => array_values($ls['lb'])];
}

function sb($ls) {
    if (isset($ls['lb'][0]) && isset($ls['lb'][1])) {
        $first = $ls['lb'][0];
        $ls['lb'][0] = $ls['lb'][1];
        $ls['lb'][1] = $first;
    }
    return ['la' => array_values($ls['la']), 'lb' => array_values($ls['lb'])];
}

function sc($ls) {
    return ['la' => sa($ls)['la'], 'lb' => sb($ls)['lb']];
}

function pa($ls) {
    if (isset($ls['lb'][array_key_first($ls['lb'])])) {
        $value = $ls['lb'][array_key_first($ls['lb'])];
        unset($ls['lb'][array_key_first($ls['lb'])]);
        array_unshift($ls['la'], $value);
    }
    return ['la' => array_values($ls['la']), 'lb' => array_values($ls['lb'])];
}

function pb($ls) {
    if (isset($ls['la'][array_key_first($ls['la'])])) {
        $value = $ls['la'][array_key_first($ls['la'])];
        unset($ls['la'][array_key_first($ls['la'])]);
        array_unshift($ls['lb'], $value);
    }
    return ['la' => array_values($ls['la']), 'lb' => array_values($ls['lb'])];
}

function ra($ls) {
    if (isset($ls['la'][array_key_first($ls['la'])])) {
        $value = $ls['la'][array_key_first($ls['la'])];
        unset($ls['la'][array_key_first($ls['la'])]);
        array_push($ls['la'], $value);
    }
    return ['la' => array_values($ls['la']), 'lb' => array_values($ls['lb'])];
}

function rb($ls) {
    if (isset($ls['lb'][array_key_first($ls['lb'])])) {
        $value = $ls['lb'][array_key_first($ls['lb'])];
        unset($ls['lb'][array_key_first($ls['lb'])]);
        array_push($ls['lb'], $value);
    }
    return ['la' => array_values($ls['la']), 'lb' => array_values($ls['lb'])];
}

function rr($ls) {
    return ['la' => ra($ls)['la'], 'lb' => rb($ls)['lb']];
}

function rra($ls) {
    if (isset($ls['la'][array_key_last($ls['la'])])) {
        $value = $ls['la'][array_key_last($ls['la'])];
        unset($ls['la'][array_key_last($ls['la'])]);
        array_unshift($ls['la'], $value);
    }
    return ['la' => array_values($ls['la']), 'lb' => array_values($ls['lb'])];
}

function rrb($ls) {
    if (isset($ls['lb'][array_key_last($ls['lb'])])) {
        $value = $ls['lb'][array_key_last($ls['lb'])];
        unset($ls['lb'][array_key_last($ls['lb'])]);
        array_unshift($ls['lb'], $value);
    }
    return ['la' => array_values($ls['la']), 'lb' => array_values($ls['lb'])];
}

function rrr($ls) {
    return ['la' => rra($ls)['la'], 'lb' => rrb($ls)['lb']];
}

function beta($la) {
    $commands = [];
    $la = array_map(function($n) {
        return intval($n);
    }, $la);
    $ls = ['la' => $la, 'lb' => []];
    while (!empty($ls['la'])) {
        $sortedLa = $ls['la'];
        \sort($sortedLa, SORT_NUMERIC);
        if ($sortedLa == $ls['la'])
    break;
        $min = min($ls['la']);
        $i = array_search($min, $ls['la']);
        if ($i == array_key_first($ls['la'])) {
            $ls = pb($ls);
            array_push($commands, 'pb');
        } elseif ($i == array_key_last($ls['la'])) {
            $ls = rra($ls);
            if ($sortedLa == $ls['la']) {
                array_push($commands, 'rra');
        break;
        }
            $ls = pb($ls);
            array_push($commands, 'rra', 'pb');
        } elseif ($i == 1) {
            $ls = sa($ls);
            if ($sortedLa == $ls['la']) {
                array_push($commands, 'sa');
        break;
        }
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
    return $commands;
}

function bestPb($list, $chunk) {
    $move = false;
    foreach ($chunk as $value) {
        $key = array_search($value, $list);
        $score = [];
        if ($key !== false) {
            $ra = intval(substr(strval(array_key_first($list) - $key), 1));
            $rra = intval(array_key_last($list)) - $key + 1;
            if ($ra < $rra) {
                $score = ['ra', $ra, $key, $value];
            } else {
                $score = ['rra', $rra, $key, $value];
            }
            if ($move == false || $score[1] < $move[1]) {
                $move = $score;
            }
        }
    }
    return $move;
}

function wherePb($list, $val) {
    if (count($list) == 0 || count($list) == 1) {
        return null;
    }
    $wherePb = null;
    $sorted = array_merge($list, [$val]);
    rsort($sorted, SORT_NUMERIC);
    $key = array_search($val, $sorted);
    $prevNext = [null, null];
    if ($key == array_key_first($sorted)) {
        $prev = $sorted[array_key_last($sorted)];
        $next = $sorted[$key + 1];
        $prevNext[0] = [array_search($prev, $list), $prev];
        $prevNext[1] = [array_search($next, $list), $next];
    } elseif ($key == array_key_last($sorted)) {
        $prev = $sorted[$key - 1];
        $next = $sorted[array_key_first($sorted)];
        $prevNext[0] = [array_search($prev, $list), $prev];
        $prevNext[1] = [array_search($next, $list), $next];
    } else {
        $prev = $sorted[$key - 1];
        $next = $sorted[$key + 1];
        $prevNext[0] = [array_search($prev, $list), $prev];
        $prevNext[1] = [array_search($next, $list), $next];
    }
    foreach ($prevNext as $k => $value) {
        if ($k == 0) {
            $adjust = [1, 0];
        } else {
            $adjust = [0, 1];
        }
        $rb = intval(substr(strval(array_key_first($list) - $value[0]), 1)) + $adjust[0];
        $rrb = intval(array_key_last($list)) - $value[0] + $adjust[1];
        if ($rb < $rrb) {
            $score = ['rb', $rb, $value[0], $value[1]];
        } else {
            $score = ['rrb', $rrb, $value[0], $value[1]];
        }
        if ($wherePb == null || $score[1] < $wherePb[1]) {
            $wherePb = $score;
        }
    }

    return $wherePb;
}

function lessCommands($commands) {
    foreach ($commands as $key => $value) {
        if (isset($commands[$key + 1])) {
            $currPrefix = substr($value, 0, -1);
            $currSuffix = substr($value, -1);
            $nextPrefix = substr($commands[$key + 1], 0, -1);
            $nextSuffix = substr($commands[$key + 1], -1);
            if ($currPrefix == $nextPrefix && $currSuffix != $nextSuffix) {
                if ($currPrefix == 's') {
                    $commands[$key] = $currPrefix . 'c';
                    $commands[$key + 1] = 'null';
                } elseif ($currPrefix == 'r' || $currPrefix == 'rr') {
                    $commands[$key] = $currPrefix . 'r';
                    $commands[$key + 1] = 'null';
                }
            }
        }
    }
    $commands = array_filter($commands, function($val) {
        return $val != 'null';
    });
    return $commands;
}

function pushSwap($la, $sortedLa, $nbChunks) {
    $commands = [];
    $ls = ['la' => $la, 'lb' => []];
    $chunks = array_chunk($sortedLa, ceil(count($sortedLa) / $nbChunks));
    $o = 0;
    while (!empty($ls['la'])) {
        $bestPb = bestPb($ls['la'], $chunks[$o]);
        if ($bestPb == false) {
            $o++;
        } else {
            for ($i=0; $i < $bestPb[1] ; $i++) {
                $op = $bestPb[0];
                $ls = $op($ls);
                array_push($commands, $op);
            }
            $wherePb = wherePb($ls['lb'], $bestPb[3]);
            if ($wherePb != null) {
                for ($i=0; $i < $wherePb[1] ; $i++) {
                    $op = $wherePb[0];
                    $ls = $op($ls);
                    array_push($commands, $op);
                }
            }
            $ls = pb($ls);
            array_push($commands, 'pb');
        }
    }
    $max = max($ls['lb']);
    $key = array_search($max, $ls['lb']);
    if ($key != 0) {
        $rb = intval(substr(strval(array_key_first($ls['lb']) - $key), 1));
        $rrb = intval(array_key_last($ls['lb'])) - $key + 1;
        if ($rb < $rrb) {
            $score = ['rb', $rb];
        } else {
            $score = ['rrb', $rrb];
        }
        for ($i=0; $i < $score[1] ; $i++) {
            $op = $score[0];
            $ls = $op($ls);
            array_push($commands, $op);
        }
    }
    while (!empty($ls['lb'])) {
        $ls = pa($ls);
        array_push($commands, 'pa');
    }
    return lessCommands($commands);
}

namespace Contest;

function sort($list){
    $count = count($list);
    $chunks = [
        [[0, 100], [1, 13]],
        [[101, 200], [5, 13]],
        [[201, 400], [7, 13]],
        [[401, 500], [10, 17]],
        [[501, 600], [13, 17]],
        [[601, 900], [15, 19]],
        [[901, 1000], [17, 20]]
    ];
    $list = array_map(function($n) { return intval($n);}, $list);
    $sorted = $list;
    \sort($sorted, SORT_NUMERIC);
    if ($sorted == $list) {
        echo "\n";
    } elseif ($count == 2) {
        echo "sa\n";
    } elseif ($count == 3) {
        if ($list[0] == min($list)) {
            if ($list[1] == max($list)) {
                echo "rra sa\n";
            } else {
                echo "sa\n";
            }
        } elseif ($list[0] == max($list)) {
            if ($list[1] == min($list)) {
                echo "ra\n";
            } else {
                echo "ra sa\n";
            }
        } else {
            if ($list[1] == min($list)) {
                echo "sa\n";
            } else {
                echo "rra\n";
            }
        }
    } else {
        $nbCommands = INF;
        $commands = [];
        foreach ($chunks as $key => $chunk) {
            if ($count < 25) {
                $score = beta($list);
                $if = $score;
                if (count($score) < $nbCommands) {
                    $nbCommands = count($score);
                    $commands = $score;
                }
            }
            if ($count >= $chunk[0][0] && $count <= $chunk[0][1]) {
                for ($i=$chunk[1][0]; $i <= $chunk[1][1] ; $i++) {
                    $score = pushSwap($list, $sorted, $i);
                    if (count($score) < $nbCommands) {
                        $nbCommands = count($score);
                        $commands = $score;
                    }
                }
            break;
            }
        }
        // echo "\033[32m".count($commands)."\033[0m\n";
        echo implode(' ', $commands) . "\n";
    }
}

$uselessVal = array_shift($argv);
sort($argv);