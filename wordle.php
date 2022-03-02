<?php

$dailyWord = 'sadik';

$word = strtolower($_GET['word']) ?? '';
$letters = [];
// mime type
header('Content-type: application/json');

if (strlen($word) === 5) {
    for ($i = 0; $i < 5; $i++) {
        if ($word[$i] === $dailyWord[$i]) {
            $state = 2;
        } else if (stripos($dailyWord, $word[$i]) !== false) {
            $state = 1;
        } else {
            $state = 0;
        }
        $letters[$word[$i]] = $state;
    }
    echo json_encode([
        'status' => true,
        'word' => $word,
        'letters' => $letters
    ]);
} else {
    echo json_encode([
        'status' => false,
        'message' => 'Word not accepted'
    ]);
}