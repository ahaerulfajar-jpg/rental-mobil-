<?php
// Base URL aplikasi (untuk url_gambar, link, dll.)
// Set via environment BASE_URL atau auto-detect dari request
if (!defined('BASE_URL')) {
    $base = getenv('BASE_URL');
    if ($base !== false && $base !== '') {
        define('BASE_URL', rtrim($base, '/'));
    } else {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        define('BASE_URL', $protocol . '://' . $host);
    }
}
 