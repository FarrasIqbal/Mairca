<?php

// Pastikan direktori temporer ada di Vercel untuk compile view dan cache
$dirs = [
    '/tmp/storage/bootstrap/cache',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/logs',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Konfigurasi dinamis untuk serverless Vercel
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
$_ENV['SESSION_DRIVER'] = 'cookie';
$_ENV['LOG_CHANNEL'] = 'stderr'; // Arahkan log ke stdout/stderr Vercel

require __DIR__ . '/../public/index.php';
