<?php

namespace App\Libraries;

class WhatsAppService
{
    /**
     * Dapatkan konfigurasi WhatsApp saat ini (menggabungkan file json pengaturan & .env)
     *
     * @return array
     */
    public static function getSettings(): array
    {
        $settingsFile = WRITEPATH . 'whatsapp_settings.json';
        $saved = [];

        if (file_exists($settingsFile)) {
            $content = @file_get_contents($settingsFile);
            if (!empty($content)) {
                $saved = json_decode($content, true) ?: [];
            }
        }

        // Nilai fallback dari .env
        $envEnabled  = filter_var(env('whatsapp.enabled', false), FILTER_VALIDATE_BOOLEAN);
        $envToken    = trim((string) env('whatsapp.token', ''));
        $envTarget   = trim((string) env('whatsapp.target', ''));
        $envProvider = trim((string) env('whatsapp.provider', 'fonnte'));

        $enabled  = isset($saved['enabled']) ? (bool) $saved['enabled'] : $envEnabled;
        $token    = !empty($saved['token']) ? trim($saved['token']) : $envToken;
        $target   = !empty($saved['target']) ? trim($saved['target']) : $envTarget;
        $provider = !empty($saved['provider']) ? trim($saved['provider']) : ($envProvider ?: 'fonnte');

        return [
            'enabled'  => $enabled,
            'token'    => $token,
            'target'   => $target,
            'provider' => $provider,
        ];
    }

    /**
     * Simpan konfigurasi WhatsApp langsung dari Dashboard ke WRITEPATH
     *
     * @param bool $enabled
     * @param string $token
     * @param string $target
     * @param string $provider
     * @return bool
     */
    public static function saveSettings(bool $enabled, string $token, string $target, string $provider = 'fonnte'): bool
    {
        $settingsFile = WRITEPATH . 'whatsapp_settings.json';
        $data = [
            'enabled'    => $enabled,
            'token'      => trim($token),
            'target'     => self::sanitizeTarget($target),
            'provider'   => trim($provider) ?: 'fonnte',
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        return (bool) @file_put_contents($settingsFile, json_encode($data, JSON_PRETTY_PRINT));
    }

    /**
     * Bersihkan nomor target dari karakter tak perlu (spasi, tanda plus, strip)
     * Tetap menjaga ID grup WhatsApp (misal xxx@g.us)
     *
     * @param string $target
     * @return string
     */
    public static function sanitizeTarget(string $target): string
    {
        $parts = explode(',', $target);
        $cleaned = [];

        foreach ($parts as $part) {
            $p = trim($part);
            if (empty($p)) {
                continue;
            }

            // Jika merupakan format grup WhatsApp (@g.us atau @c.us)
            if (str_contains($p, '@g.us') || str_contains($p, '@c.us')) {
                $cleaned[] = $p;
                continue;
            }

            // Hapus semua selain angka
            $digits = preg_replace('/[^0-9]/', '', $p);
            if (!empty($digits)) {
                $cleaned[] = $digits;
            }
        }

        return implode(',', $cleaned);
    }

    /**
     * Kirim notifikasi WhatsApp saat ada aspirasi / pertanyaan baru masuk di forum
     *
     * @param array $question Data pertanyaan
     * @return array
     */
    public static function sendAspirationAlert(array $question): array
    {
        $settings = self::getSettings();

        if (!$settings['enabled'] || empty($settings['token']) || empty($settings['target'])) {
            return [
                'status'  => 'skipped',
                'message' => 'Notifikasi WhatsApp tidak aktif atau token/target belum diisi.',
            ];
        }

        $title    = trim($question['title'] ?? 'Aspirasi Mahasiswa');
        $category = trim($question['category_name'] ?? 'Umum');
        $author   = trim($question['author_name'] ?? 'Mahasiswa Anonim');
        $nim      = !empty($question['author_nim']) ? ' (' . trim($question['author_nim']) . ')' : '';
        $rawBody  = strip_tags(trim($question['body'] ?? ''));
        $excerpt  = mb_strlen($rawBody) > 160 ? mb_substr($rawBody, 0, 160) . '...' : $rawBody;
        $date     = date('d M Y, H:i') . ' WIB';

        $dashboardUrl = site_url('dashboard/moderasi-qa');
        if (!empty($question['id'])) {
            $dashboardUrl = site_url('dashboard/moderasi-qa/detail/' . $question['id']);
        }

        $message = "📢 *ASPIRASI / PERTANYAAN BARU MASUK!*\n"
                 . "*Portal Resmi HIMATIKA Connect*\n"
                 . "━━━━━━━━━━━━━━━━━━━━━\n"
                 . "📌 *Judul:* " . $title . "\n"
                 . "🏷️ *Kategori:* " . $category . "\n"
                 . "👤 *Pengirim:* " . $author . $nim . "\n"
                 . "🕒 *Waktu:* " . $date . "\n\n"
                 . "💬 *Isi Aspirasi:*\n"
                 . '"' . $excerpt . '"' . "\n\n"
                 . "🔗 *Tinjau & Beri Balasan Resmi:*\n"
                 . $dashboardUrl . "\n"
                 . "━━━━━━━━━━━━━━━━━━━━━\n"
                 . "_Pesan otomatis sistem HIMATIKA Hub_";

        return self::sendMessage($settings['target'], $message, $settings['token']);
    }

    /**
     * Kirim pesan teks ke target nomor WhatsApp atau Group ID via Fonnte Gateway
     *
     * @param string $target Nomor HP tujuan atau Group ID
     * @param string $message Teks pesan
     * @param string|null $token Token Fonnte
     * @return array Respon hasil kirim
     */
    public static function sendMessage(string $target, string $message, ?string $token = null): array
    {
        $settings = self::getSettings();
        $token    = !empty($token) ? trim($token) : $settings['token'];
        $cleanTarget = self::sanitizeTarget(!empty($target) ? $target : $settings['target']);

        if (empty($token)) {
            return [
                'status'  => 'error',
                'message' => 'Token WhatsApp (Fonnte API Token) masih kosong. Silakan isi token di menu pengaturan atau file .env.',
            ];
        }

        if (empty($cleanTarget)) {
            return [
                'status'  => 'error',
                'message' => 'Nomor tujuan (target) WhatsApp masih kosong.',
            ];
        }

        try {
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL            => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => '',
                CURLOPT_MAXREDIRS      => 5,
                CURLOPT_TIMEOUT        => 12, // 12 detik timeout agar stabil di koneksi apapun
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false, // Hindari kegagalan SSL CA bundle di environment lokal/Codespaces
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => 'POST',
                CURLOPT_POSTFIELDS     => [
                    'target'      => $cleanTarget,
                    'message'     => $message,
                    'countryCode' => '62',
                ],
                CURLOPT_HTTPHEADER     => [
                    'Authorization: ' . $token,
                ],
            ]);

            $response = curl_exec($curl);
            $errNo    = curl_errno($curl);
            $errStr   = curl_error($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            if ($errNo) {
                log_message('error', 'WhatsApp Gateway cURL Error (' . $errNo . '): ' . $errStr);
                return [
                    'status'  => 'error',
                    'message' => 'Koneksi ke gateway gagal (cURL error ' . $errNo . '): ' . $errStr,
                ];
            }

            $decoded = json_decode($response, true);

            if (!is_array($decoded)) {
                return [
                    'status'  => 'error',
                    'message' => 'Respons gateway tidak valid (HTTP ' . $httpCode . '): ' . mb_substr($response, 0, 200),
                ];
            }

            // Validasi status pengiriman dari Fonnte
            if (isset($decoded['status']) && $decoded['status'] === false) {
                $reason = $decoded['reason'] ?? $decoded['detail'] ?? 'Gateway menolak pengiriman.';

                // Penjelasan ramah jika terjadi kasus umum
                if (stripos($reason, 'token invalid') !== false) {
                    $reason = 'Token Fonnte tidak valid. Periksa kembali token API dari akun Fonnte Anda.';
                } elseif (stripos($reason, 'device') !== false || stripos($reason, 'disconnect') !== false) {
                    $reason = 'Perangkat WhatsApp terputus dari Fonnte. Buka dashboard fonnte.com dan scan ulang QR code.';
                } elseif (stripos($reason, 'target') !== false) {
                    $reason = 'Nomor tujuan (' . $cleanTarget . ') tidak terdaftar di WhatsApp atau format tidak sesuai.';
                }

                log_message('warning', 'WhatsApp Fonnte rejected: ' . $reason);

                return [
                    'status'  => 'error',
                    'message' => $reason,
                    'raw'     => $response,
                ];
            }

            return [
                'status'   => 'success',
                'detail'   => $decoded['detail'] ?? 'Pesan berhasil diteruskan ke antrean WhatsApp!',
                'raw'      => $response,
                'response' => $decoded,
            ];
        } catch (\Throwable $e) {
            log_message('error', 'WhatsApp Exception: ' . $e->getMessage());
            return [
                'status'  => 'error',
                'message' => 'Kesalahan sistem: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Uji kirim pesan tes
     *
     * @param string $target Nomor tujuan
     * @param string|null $token Token khusus saat uji coba
     * @return array
     */
    public static function testMessage(string $target, ?string $token = null): array
    {
        $time = date('d M Y, H:i:s');
        $msg = "✅ *KONEKSI NOTIFIKASI WHATSAPP BERHASIL!*\n\n"
             . "Sistem Portal *HIMATIKA Connect* telah berhasil mengirim pesan ke nomor ini.\n"
             . "🕒 *Waktu Uji:* {$time} WIB\n\n"
             . "Pesan ini mengonfirmasi bahwa integrasi WhatsApp Gateway (Fonnte) sudah aktif dan siap menerima notifikasi aspirasi baru.\n\n"
             . "_Pesan otomatis sistem HIMATIKA Hub_";

        return self::sendMessage($target, $msg, $token);
    }

    /**
     * Kirim tautan dan kode reset kata sandi via WhatsApp resmi ke pengurus
     *
     * @param string $target Nomor WhatsApp tujuan pengurus
     * @param string $userName Nama lengkap pengurus
     * @param string $userEmail Email akun pengurus
     * @param string $resetUrl Tautan reset unik dengan token kriptografi
     * @param string $shortCode Kode numerik 6-digit acak
     * @param int $expiresMinutes Batas waktu berlaku dalam menit
     * @return array
     */
    public static function sendPasswordResetLink(string $target, string $userName, string $userEmail, string $resetUrl, string $shortCode, int $expiresMinutes = 15): array
    {
        $time = date('H:i') . ' WIB';
        $expiresTime = date('H:i', time() + ($expiresMinutes * 60)) . ' WIB';

        $msg = "🔐 *PERMINTAAN RESET KATA SANDI*\n"
             . "*Portal Resmi HIMATIKA Connect*\n"
             . "━━━━━━━━━━━━━━━━━━━━━\n"
             . "Halo, *{$userName}*!\n\n"
             . "Kami menerima permintaan reset kata sandi untuk akun pengurus Anda:\n"
             . "📧 *Email:* {$userEmail}\n"
             . "🕒 *Diajukan:* {$time}\n\n"
             . "Silakan klik tautan resmi di bawah ini untuk membuat kata sandi baru:\n"
             . "👉 {$resetUrl}\n\n"
             . "🔢 *Kode Verifikasi Singkat:* *{$shortCode}*\n"
             . "⏳ *Masa Berlaku:* {$expiresMinutes} menit (berakhir pukul *{$expiresTime}*)\n\n"
             . "⚠️ *PERINGATAN KEAMANAN:*\n"
             . "• Tautan ini bersifat rahasia dan sekali pakai.\n"
             . "• Jangan pernah membagikan tautan atau kode ini kepada siapapun.\n"
             . "• Jika Anda *TIDAK PERNAH* meminta reset kata sandi, abaikan pesan ini. Akun Anda tetap aman.\n"
             . "━━━━━━━━━━━━━━━━━━━━━\n"
             . "_Sistem Keamanan Akun Pengurus HIMATIKA Hub_";

        return self::sendMessage($target, $msg);
    }

    /**
     * Kirim pemberitahuan keamanan setelah kata sandi berhasil diubah
     *
     * @param string $target
     * @param string $userName
     * @param string $userEmail
     * @return array
     */
    public static function sendPasswordChangedNotification(string $target, string $userName, string $userEmail): array
    {
        $time = date('d M Y, H:i') . ' WIB';

        $msg = "🔔 *PEMBERITAHUAN KEAMANAN AKUN*\n"
             . "*Portal Resmi HIMATIKA Connect*\n"
             . "━━━━━━━━━━━━━━━━━━━━━\n"
             . "Halo, *{$userName}*!\n\n"
             . "Kata sandi untuk akun pengurus Anda (*{$userEmail}*) telah *BERHASIL DIUBAH* pada {$time}.\n\n"
             . "Jika Anda yang melakukan perubahan ini, Anda dapat mengabaikan pesan ini.\n\n"
             . "🚨 *PENTING:* Jika Anda *TIDAK MERASA* mengubah kata sandi, segera hubungi Administrator BPH HIMATIKA untuk mengamankan akun Anda.\n"
             . "━━━━━━━━━━━━━━━━━━━━━\n"
             . "_Sistem Keamanan Akun HIMATIKA Hub_";

        return self::sendMessage($target, $msg);
    }
}
