<?php
declare(strict_types=1);
require_once __DIR__ . '/catalog.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if (!in_array($method, ['GET', 'HEAD'], true)) {
    header('Allow: GET, HEAD');
    http_response_code(405);
    exit('Method not allowed.');
}
$id = $_GET['file'] ?? null;
$files = catalog();
if (!is_string($id) || !isset($files[$id])) {
    http_response_code(404);
    exit('File not found.');
}
$path = archive_path($files[$id]['file']);
if ($path === null || ($handle = fopen($path, 'rb')) === false) {
    http_response_code(404);
    exit('File not found.');
}
$stat = fstat($handle);
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . basename($path) . '"');
header('Content-Length: ' . $stat['size']);
header('X-Content-Type-Options: nosniff');
header('Cache-Control: public, max-age=0, must-revalidate');
if ($method === 'GET') {
    fpassthru($handle);
}
fclose($handle);
