<?php
header('Content-Type: application/json');

$allowedFile = 'teste.php';

if (!file_exists($allowedFile)) {
    echo json_encode(['error' => 'File not found']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $method = $_GET['method'] ?? null;
    $fileContent = file_get_contents($allowedFile);

    if ($method) {
        $pattern = "/public function $method\(\)\s*\{([\s\S]*?)\}/";
        if (preg_match($pattern, $fileContent, $matches)) {
            echo json_encode(['code' => $matches[1]]);
        } else {
            echo json_encode(['error' => 'Method not found']);
        }
    } else {
        preg_match_all("/public function (\w+)\(\)/", $fileContent, $matches);
        echo json_encode(['methods' => $matches[1]]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = $_POST['method'] ?? null;
    $newContent = $_POST['content'] ?? '';

    if (!$method) {
        echo json_encode(['error' => 'No method specified']);
        exit;
    }

    $fileContent = file_get_contents($allowedFile);
    $pattern = "/public function $method\(\)\s*\{([\s\S]*?)\}/";
    $replacement = "public function $method() {\n$newContent\n}";
    $newFileContent = preg_replace($pattern, $replacement, $fileContent);
    file_put_contents($allowedFile, $newFileContent);

    echo json_encode(['success' => "Method $method saved successfully!"]);
    exit;
}
