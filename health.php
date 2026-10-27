<?php

header('Content-Type: text/plain; charset=utf-8');

try {
    include __DIR__ . '/admin/includes/database.php';
    $connect->query('SELECT 1');
    echo "ok\n";
} catch (PDOException $exception) {
    error_log('Database health check failed: ' . $exception->getMessage());
    http_response_code(503);
    echo "database unavailable\n";
}
