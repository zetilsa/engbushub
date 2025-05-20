<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Alert</title>
    <style>
        body { font-family: sans-serif; background: #f8f9fa; padding: 20px; }
        .box { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="box">
        <h2>Hai {{ $user->name }}</h2>
        <p>Ada aktivitas login ke akun Anda.</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p>Jika ini bukan Anda, segera ubah password Anda!</p>
        <p><small>Waktu: {{ now()->format('d M Y, H:i') }}</small></p>
    </div>
</body>
</html>
