<?php

require dirname(__DIR__) . '/includes/spintext.class.php';
require dirname(__DIR__) . '/includes/wordlist.class.php';

$data = json_decode(file_get_contents("php://input"), true);

$spinText = new SpinText(
    $data['article'],
    $data['stop_word'],
    new WordList()
);

header("Content-Type: application/json");
try {
    echo json_encode(
        array(
            'status' => true,
            'article' => $spinText->spin()
        )
    );
    die;
} catch ( \Exception $e) {
    echo json_encode(
        array(
            'status' => false,
            'message' => $e->getMessage()
        )
    );
    die;
} 