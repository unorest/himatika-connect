<?php

namespace App\Libraries;

class EmailService
{
    /**
     * Kirim email resmi instruksi reset kata sandi dengan template HTML responsif
     *
     * @param string $toEmail Email penerima
     * @param string $userName Nama penerima
     * @param string $resetUrl URL reset lengkap dengan token acak
     * @param string $shortCode Kode numerik 6 digit
     * @param int $expiresMinutes Masa berlaku dalam menit
     * @return array
     */
    public static function sendPasswordResetEmail(string $toEmail, string $userName, string $resetUrl, string $shortCode, int $expiresMinutes = 15): array
    {
        try {
            $email = \Config\Services::email();

            $fromEmail = env('email.fromEmail', 'no-reply@himatika.org');
            $fromName  = env('email.fromName', 'HIMATIKA Connect Security');

            $email->setFrom($fromEmail, $fromName);
            $email->setTo($toEmail);
            $email->setSubject('🔐 Reset Kata Sandi Akun HIMATIKA Connect');

            $expiresTime = date('H:i', time() + ($expiresMinutes * 60)) . ' WIB';

            $message = '
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Kata Sandi</title>
</head>
<body style="margin: 0; padding: 0; background-color: #0f172a; font-family: \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif; color: #334155;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #0f172a; padding: 30px 15px;">
        <tr>
            <td align="center">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 540px; background-color: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.3);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #2563eb, #4f46e5); padding: 35px 30px; text-align: center;">
                            <div style="font-size: 24px; font-weight: 800; color: #ffffff; letter-spacing: -0.5px;">
                                HIMATIKA <span style="color: #93c5fd;">Hub</span>
                            </div>
                            <div style="font-size: 11px; color: #bfdbfe; text-transform: uppercase; letter-spacing: 2px; margin-top: 4px; font-weight: 600;">
                                Sistem Keamanan Akun Pengurus
                            </div>
                        </td>
                    </tr>
                    <!-- Body -->
                    <tr>
                        <td style="padding: 35px 30px;">
                            <h2 style="font-size: 20px; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 12px;">
                                Permintaan Reset Kata Sandi
                            </h2>
                            <p style="font-size: 14px; line-height: 1.6; color: #475569; margin-bottom: 20px;">
                                Halo, <strong>' . htmlspecialchars($userName) . '</strong>! Kami menerima permintaan untuk mengatur ulang kata sandi akun HIMATIKA Connect Anda (<code>' . htmlspecialchars($toEmail) . '</code>).
                            </p>
                            <p style="font-size: 14px; line-height: 1.6; color: #475569; margin-bottom: 25px;">
                                Silakan klik tombol aman di bawah ini untuk membuat kata sandi baru Anda:
                            </p>
                            <!-- Button CTA -->
                            <div style="text-align: center; margin: 30px 0;">
                                <a href="' . $resetUrl . '" style="display: inline-block; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #ffffff; font-size: 14px; font-weight: 700; text-decoration: none; padding: 14px 32px; border-radius: 12px; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);">
                                    Atur Ulang Kata Sandi
                                </a>
                            </div>
                            <!-- Verification Code Box -->
                            <div style="background-color: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 12px; padding: 15px; text-align: center; margin-bottom: 25px;">
                                <div style="font-size: 11px; color: #64748b; font-weight: 600; text-transform: uppercase;">Kode Verifikasi Cepat:</div>
                                <div style="font-size: 24px; font-weight: 800; letter-spacing: 4px; color: #1e293b; margin-top: 4px; font-family: monospace;">' . $shortCode . '</div>
                            </div>
                            <!-- Expiration notice -->
                            <div style="background-color: #fef3c7; border: 1px solid #fde68a; border-radius: 10px; padding: 12px 16px; margin-bottom: 25px;">
                                <div style="font-size: 12px; color: #92400e; font-weight: 600;">
                                    ⏳ Masa Berlaku Terbatas: ' . $expiresMinutes . ' Menit
                                </div>
                                <div style="font-size: 11px; color: #b45309; margin-top: 2px;">
                                    Tautan dan kode ini akan kedaluwarsa pada pukul <strong>' . $expiresTime . '</strong> demi alasan keamanan.
                                </div>
                            </div>
                            <!-- Fallback Link -->
                            <p style="font-size: 12px; line-height: 1.5; color: #64748b; margin-bottom: 8px;">
                                Jika tombol di atas tidak berfungsi, salin dan tempel URL berikut di browser Anda:
                            </p>
                            <p style="font-size: 11px; line-height: 1.5; color: #2563eb; word-break: break-all; margin-bottom: 25px;">
                                <a href="' . $resetUrl . '" style="color: #2563eb;">' . $resetUrl . '</a>
                            </p>
                            <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 25px 0;">
                            <p style="font-size: 11px; line-height: 1.5; color: #94a3b8; margin: 0;">
                                ⚠️ <strong>Peringatan Keamanan:</strong> Jangan berikan tautan atau kode ini kepada siapapun. Jika Anda tidak pernah meminta reset kata sandi, abaikan email ini dan akun Anda tetap aman.
                            </p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px 30px; text-align: center;">
                            <div style="font-size: 11px; color: #94a3b8;">
                                &copy; ' . date('Y') . ' HIMATIKA Connect. Portal Resmi Organisasi Mahasiswa Informatika & Matematika.
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>';

            $email->setMessage($message);
            $email->setMailType('html');

            if ($email->send(false)) {
                return [
                    'status'  => 'success',
                    'message' => 'Email verifikasi reset kata sandi berhasil dikirim ke ' . $toEmail,
                ];
            }

            $errorMsg = $email->printDebugger(['headers']);
            log_message('warning', 'EmailService::sendPasswordResetEmail failed: ' . $errorMsg);

            return [
                'status'  => 'error',
                'message' => 'Gagal mengirim email (periksa konfigurasi SMTP): ' . strip_tags($errorMsg),
            ];
        } catch (\Throwable $e) {
            log_message('error', 'EmailService Exception: ' . $e->getMessage());
            return [
                'status'  => 'error',
                'message' => 'Kesalahan layanan email: ' . $e->getMessage(),
            ];
        }
    }
}
