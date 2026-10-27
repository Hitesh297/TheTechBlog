<?php

$environment = [];
$environmentFile = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . '.env';

if (is_file($environmentFile)) {
    $lines = file($environmentFile, FILE_IGNORE_NEW_LINES);
    if ($lines === false) {
        throw new RuntimeException('Unable to read the SQL Server environment file.');
    }

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') {
            continue;
        }

        if (!preg_match('/^([A-Z][A-Z0-9_]*)\s*=\s*(.*)$/', $line, $matches)) {
            throw new RuntimeException('Invalid setting in the SQL Server environment file.');
        }

        $value = trim($matches[2]);
        if (strlen($value) >= 2 && (($value[0] === '"' && $value[-1] === '"') || ($value[0] === "'" && $value[-1] === "'"))) {
            $value = substr($value, 1, -1);
        }

        $environment[$matches[1]] = $value;
    }
}

$getSetting = static function (string $name) use ($environment): ?string {
    $value = getenv($name);
    if ($value !== false) {
        return $value;
    }

    return $environment[$name] ?? null;
};

$host = $getSetting('SQLSERVER_HOST');
$database = $getSetting('SQLSERVER_DATABASE');
$username = $getSetting('SQLSERVER_USERNAME');
$password = $getSetting('SQLSERVER_PASSWORD');

if ($host === null || $host === '' || $database === null || $database === '' ||
    $username === null || $username === '' || $password === null || $password === '') {
    throw new RuntimeException('SQL Server connection settings are incomplete.');
}

$connect = new PDO(
    "sqlsrv:Server={$host};Database={$database};Encrypt=yes",
    $username,
    $password,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
);
