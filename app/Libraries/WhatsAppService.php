<?php

namespace App\Libraries;

class WhatsAppService
{
    /**
     * Kirim notifikasi WhatsApp saat ada aspirasi / pertanyaan baru masuk di forum
     *
     * @param array $question Data pertanyaan (title, body, author_name, author_nim, category_name, id, etc.)
     * @return array Status dan respons gateway
     */
    public static function sendAspirationAlert(array $question): array
    {
        $enabled = filter_var(env('whatsapp.enabled', false), FILTER_VALIDATE_BOOLEAN);
        $token   = trim(env('whatsapp.token', ''));
        $target  = trim(env('whatsapp.target', ''));

        if (!$enabled || empty($token) || empty($target)) {
            return [
                'status'  => 'skipped',
                'message' => 'Notifikasi WhatsApp tidak diaktifkan atau konfigurasi whatsapp.token / whatsapp.target di .env belum diisi.',
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

        return self::sendMessage($target, $message, $token);
    }

    /**
     * Kirim pesan teks ke target nomor WhatsApp atau Group ID via Fonnte Gateway
     *
     * @param string $target Nomor HP tujuan (misal: 08123456789 atau ID grup 1203630xxx@g.us)
     * @param string $message Teks pesan
     * @param string|null $token Token Fonnte
     * @return array Respon hasil kirim
     */
    public static function sendMessage(string $target, string $message, ?string $token = null): array
    {
        $token = $token ?: trim(env('whatsapp.token', ''));

        if (empty($token) || empty($target)) {
            return [
                'status'  => 'error',
                'message' => 'Token atau target WhatsApp belum ditentukan.',
            ];
        }

        // Fonnte API mendukung multiple target dipisah koma
        try {
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL            => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => '',
                CURLOPT_MAXREDIRS      => 5,
                CURLOPT_TIMEOUT        => 4, // 4 detik batas timeout agar halaman web tetap cepat
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => 'POST',
                CURLOPT_POSTFIELDS     => [
                    'target'      => $target,
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
            curl_close($curl);

            if ($errNo) {
                log_message('error', 'WhatsApp Gateway Error (' . $errNo . '): ' . $errStr);
                return [
                    'status'  => 'error',
                    'message' => 'Gagal koneksi ke gateway WA: ' . $errStr,
                ];
            }

            $decoded = json_decode($response, true);

            return [
                'status'   => 'success',
                'raw'      => $response,
                'response' => $decoded,
            ];
        } catch (\Throwable $e) {
            log_message('error', 'WhatsApp Exception: ' . $e->getMessage());
            return [
                'status'  => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Uji kirim pesan tes
     */
    public static function testMessage(string $target): array
    {
        $time = date('d M Y, H:i:s');
        $msg = "✅ *KONEKSI NOTIFIKASI WHATSAPP BERHASIL!*\n\n"
             . "Sistem HIMATIKA Connect telah terhubung ke nomor WhatsApp ini.\n"
             . "🕒 Waktu tes: {$time} WIB\n\n"
             . "_Pesan pengujian integrasi gateway Fonnte._";

        return self::sendMessage($target, $msg);
    }
}
