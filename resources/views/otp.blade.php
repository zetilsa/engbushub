<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kode OTP Reset Password</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding: 30px 0;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); overflow: hidden;">
                    <tr>
                        <td style="background-color: #0d6efd; padding: 20px; color: white; text-align: center;">
                            <h2 style="margin: 0;">Reset Password</h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 30px;">
                            <p style="font-size: 16px; color: #333333; margin-bottom: 20px;">
                                Hai! Kami menerima permintaan untuk mereset password kamu.
                            </p>
                            <p style="font-size: 16px; color: #333333; margin-bottom: 30px;">
                                Berikut adalah kode OTP kamu:
                            </p>
                            <div style="text-align: center; margin-bottom: 30px;">
                                <span style="display: inline-block; background-color: #e9ecef; padding: 15px 30px; font-size: 24px; font-weight: bold; letter-spacing: 4px; color: #0d6efd; border-radius: 8px;">
                                    {{ $otp }}
                                </span>



                                
                            </div>
                            <p style="font-size: 14px; color: #666666;">
                                Kode ini berlaku selama 10 menit. Jika kamu tidak meminta reset password, abaikan email ini.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #f1f1f1; text-align: center; padding: 20px; font-size: 12px; color: #888888;">
                            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
