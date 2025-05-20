<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>OTP Login</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f8f9fa; }
        .otp-box { background: white; border-radius: 8px; padding: 20px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .otp { font-size: 36px; color: #007bff; letter-spacing: 8px; }
    </style>
</head>
<body>
    <div class="otp-box">
        <h2>Verifikasi OTP Login</h2>
        <p>Kode OTP Anda:</p>
        <div class="otp">{{ $otp }}</div>
        <p>Berlaku selama 10 menit.</p>
    </div>
</body>
</html>
