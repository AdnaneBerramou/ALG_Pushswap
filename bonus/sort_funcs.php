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