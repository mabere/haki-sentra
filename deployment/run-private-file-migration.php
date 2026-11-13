<?php

/*
 * Temporary cPanel runner for users without SSH.
 * Before upload, replace the placeholder secret with a unique random token.
 * Upload this file beside index.php, clear Laravel caches, run the copy phase,
 * then the delete phase, and remove the uploaded file immediately afterward.
 */
$secret = 'REPLACE_WITH_A_UNIQUE_RANDOM_SECRET';

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: no-referrer');
header("Content-Security-Policy: default-src 'none'; style-src 'unsafe-inline'; form-action 'self'; frame-ancestors 'none'");
header('Content-Type: text/html; charset=UTF-8');

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function renderPage(string $message = '', bool $success = false): never
{
    $messageHtml = $message === '' ? '' : '<pre class="result '.($success ? 'ok' : 'error').'">'.e($message).'</pre>';

    echo '<!doctype html><html lang="id"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">'
        .'<title>Pemindahan file privat</title><style>body{font:16px system-ui,sans-serif;max-width:680px;margin:40px auto;padding:0 18px;color:#172033}label{display:block;margin:18px 0 6px}input{box-sizing:border-box;width:100%;padding:11px;border:1px solid #9aa4b2;border-radius:6px}button{padding:11px 16px;margin:16px 8px 0 0;border:0;border-radius:6px;background:#174ea6;color:white;font-weight:600;cursor:pointer}.danger{background:#9e2525}.result{white-space:pre-wrap;padding:14px;border-radius:6px}.ok{background:#e7f7ed}.error{background:#fff0f0}</style>'
        .'<h1>Pemeliharaan hosting</h1><p>Jalankan berurutan: bersihkan cache, salin dan verifikasi file, lalu hapus salinan publik setelah verifikasi berhasil. Hapus file helper dari hosting setelah selesai.</p>'
        .$messageHtml
        .'<form method="post"><label for="key">Token sementara</label><input id="key" name="key" type="password" required autocomplete="off">'
        .'<button name="phase" value="cache">Bersihkan cache Laravel</button><button name="phase" value="copy">Salin dan verifikasi</button><button class="danger" name="phase" value="delete">Hapus salinan publik terverifikasi</button></form>'
        .'</html>';

    exit;
}

if ($secret === 'REPLACE_WITH_A_UNIQUE_RANDOM_SECRET') {
    http_response_code(503);
    renderPage('Helper belum diaktifkan. Tetapkan token acak pada variabel $secret sebelum mengunggah.');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    renderPage();
}

$submittedKey = $_POST['key'] ?? null;
$phase = $_POST['phase'] ?? null;

if (!is_string($submittedKey) || !hash_equals($secret, $submittedKey)) {
    http_response_code(404);
    renderPage('Tidak ditemukan.');
}

if (!in_array($phase, ['cache', 'copy', 'delete'], true)) {
    http_response_code(400);
    renderPage('Tahap yang diminta tidak dikenal.');
}

$appRoot = realpath(__DIR__.'/../../sentrahaki');

if (!$appRoot || !is_file($appRoot.'/vendor/autoload.php') || !is_file($appRoot.'/bootstrap/app.php')) {
    http_response_code(500);
    renderPage('Aplikasi Laravel tidak ditemukan. Pastikan helper berada di public_html/sentrahaki.');
}

require $appRoot.'/vendor/autoload.php';

try {
    $app = require $appRoot.'/bootstrap/app.php';
    $console = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $output = new Symfony\Component\Console\Output\BufferedOutput();
    if ($phase === 'cache') {
        $status = $console->call('optimize:clear', [], $output);
        $remainingCaches = array_filter([
            $appRoot.'/bootstrap/cache/config.php',
            $appRoot.'/bootstrap/cache/routes-v7.php',
            $appRoot.'/bootstrap/cache/events.php',
        ], 'is_file');
        if ($status === 0 && $remainingCaches === []) {
            $result = trim($output->fetch());
            http_response_code(200);
            renderPage($result."\n\nCache Laravel berhasil dibersihkan; config, route, dan event cache tidak ditemukan.", true);
        }
        $result = trim($output->fetch());
        http_response_code(500);
        renderPage($result."\n\nPembersihan cache belum terverifikasi. Periksa output dan cache di bootstrap/cache.");
    }

    $arguments = $phase === 'delete' ? ['--delete-public' => true] : [];
    $status = $console->call('security:protect-private-files', $arguments, $output);
    $result = trim($output->fetch());
    $ok = $status === 0;

    http_response_code($ok ? 200 : 500);
    renderPage($result."\n\n".($ok
        ? ($phase === 'copy'
            ? 'Tahap salin selesai. Jika tidak ada error, jalankan tahap hapus salinan publik.'
            : 'Tahap hapus selesai. Hapus helper ini dari hosting sekarang.')
        : 'Migrasi gagal atau belum lengkap. Jangan hapus file sumber secara manual; periksa pesan error.'), $ok);
} catch (Throwable $exception) {
    http_response_code(500);
    error_log('Temporary private-file migration helper failed: '.$exception->getMessage());
    renderPage('Migrasi gagal. Periksa error log hosting.');
}
