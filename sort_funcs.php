<?php

function sa($la) {
    if (isset($la[0]) && isset($la[1])) {
        $first = $la[0];
        $la[0] = $la[1];
        $la[1] = $first;
    }
    return $la;
}

function sb($lb) {
    if (isset($lb[0]) && isset($lb[1])) {
        $first = $lb[0];
        $lb[0] = $lb[1];
        $lb[1] = $first;
    }
    return $lb;
}

function sc($la, $lb) {
    return ['la' => sa($la), 'lb' => sb($lb)];
}

function pa($la, $lb) {
    if (isset($lb[array_key_first($lb)])) {
        $value = $lb[array_key_first($lb)];
        unset($lb[array_key_first($lb)]);
        array_unshift($la, $value);
    }
    return ['la' => $la, 'lb' => $lb];
}

function pb($la, $lb) {
    if (isset($la[array_key_first($la)])) {
        $value = $la[array_key_first($la)];
        unset($la[array_key_first($la)]);
        array_unshift($lb, $value);
    }
    return ['la' => $la, 'lb' => $lb];
}

function ra($la) {
    if (isset($la[array_key_first($la)])) {
        $value = $la[array_key_first($la)];
        unset($la[array_key_first($la)]);
        array_push($la, $value);
    }
    return $la;
}

function rb($lb) {
    if (isset($lb[array_key_first($lb)])) {
        $value = $lb[array_key_first($lb)];
        unset($lb[array_key_first($lb)]);
        array_push($lb, $value);
    }
    return $lb;
}

function rr($la, $lb) {
    return ['la' => ra($la), 'lb' => rb($lb)];
}

function rra($la) {
    if (isset($la[array_key_last($la)])) {
        $value = $la[array_key_last($la)];
        unset($la[array_key_last($la)]);
        array_unshift($la, $value);
    }
    return $la;
}

function rrb($lb) {
    if (isset($lb[array_key_last($lb)])) {
        $value = $lb[array_key_last($lb)];
        unset($lb[array_key_last($lb)]);
        array_unshift($lb, $value);
    }
    return $lb;
}

function rrr($la, $lb) {
    return ['la' => rra($la), 'lb' => rrb($lb)];
}