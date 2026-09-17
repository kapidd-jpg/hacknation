<?php
/** @var int $code */
/** @var string $title */
/** @var string $message */
$code = $code ?? 500;
$title = $title ?? 'Terjadi Kesalahan';
$message = $message ?? 'Mohon coba beberapa saat lagi.';
$msgTitle = $msgTitle ?? null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $code }} · {{ $title }} - PintarKuy</title>
    <link rel="icon" href="{{ asset('assets/images/logopintar.png') }}">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif;background:#FBFAF5;color:#101A2E;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px}
        .err-card{max-width:480px;width:100%;text-align:center;background:#fff;border:1px solid rgba(16,26,46,.12);border-radius:14px;padding:48px 32px;box-shadow:0 10px 30px rgba(16,26,46,.06);position:relative}
        .err-logo{display:inline-flex;align-items:center;gap:10px;margin-bottom:28px;text-decoration:none}
        .err-logo img{height:30px;width:auto}
        .err-logo span{font-size:13px;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:#101A2E}
        .err-code{font-size:72px;font-weight:900;line-height:1;letter-spacing:-.02em;background:linear-gradient(120deg,#B45309,#EAB308);-webkit-background-clip:text;background-clip:text;color:transparent}
        .err-title{font-size:20px;font-weight:800;margin-top:12px}
        .err-msg{margin-top:10px;font-size:14px;line-height:1.7;color:#4B5563}
        .err-actions{margin-top:28px;display:flex;gap:12px;justify-content:center;flex-wrap:wrap}
        .err-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:11px 20px;border-radius:10px;font-size:14px;font-weight:700;text-decoration:none;transition:transform .15s ease,box-shadow .15s ease}
        .err-btn--primary{background:#101A2E;color:#fff;box-shadow:0 4px 0 #000}
        .err-btn--primary:hover{transform:translateY(-1px);box-shadow:0 6px 0 #000}
        .err-btn--ghost{border:1px solid rgba(16,26,46,.25);color:#101A2E}
        .err-btn--ghost:hover{border-color:#101A2E}
        @media (max-width:420px){.err-card{padding:36px 20px}.err-code{font-size:56px}}
    </style>
</head>
<body>
    <div class="err-card" role="alert">
        <a href="{{ url('/') }}" class="err-logo">
            <img src="{{ asset('assets/images/logopintar.png') }}" alt="PintarKuy">
            <span>PintarKuy</span>
        </a>
        <div class="err-code">{{ $code }}</div>
        <h1 class="err-title">{{ $title }}</h1>
        @if ($msgTitle)
            <p class="err-msg" style="margin-top:4px;font-size:13px;font-weight:700;color:#B45309">{{ $msgTitle }}</p>
        @endif
        <p class="err-msg">{{ $message }}</p>
        <div class="err-actions">
            <a href="{{ url('/') }}" class="err-btn err-btn--primary">Kembali ke Beranda</a>
            <a href="javascript:history.length > 1 ? history.back() : location.href = '/'" class="err-btn err-btn--ghost">Kembali</a>
        </div>
    </div>
</body>
</html>