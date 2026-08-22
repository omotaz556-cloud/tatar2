<?php
if (PHP_SAPI !== 'cli') { http_response_code(403); exit('CLI only'); }
chdir(__DIR__); include_once 'GameEngine/config.php';
$dir = defined('BACKUP_DIR') ? BACKUP_DIR : (__DIR__ . '/var/backups');
if (!is_dir($dir)) mkdir($dir, 0750, true);
$file = rtrim($dir, '/\\') . DIRECTORY_SEPARATOR . 'novaterra_' . date('Y-m-d_His') . '.sql';
$cmd = 'mysqldump --single-transaction --routines --triggers --host=' . escapeshellarg(SQL_HOST) . ' --user=' . escapeshellarg(SQL_USER) . ' --password=' . escapeshellarg(SQL_PASS) . ' ' . escapeshellarg(SQL_DB) . ' > ' . escapeshellarg($file);
$exit = 0; system($cmd, $exit);
if ($exit !== 0 || !is_file($file) || filesize($file) === 0) { @unlink($file); fwrite(STDERR, "backup failed\n"); exit(1); }
echo $file . "\n";