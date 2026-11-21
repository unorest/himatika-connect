<?php

namespace App\Models;

use CodeIgniter\Model;

class PasswordResetModel extends Model
{
    protected $table            = 'hmt_password_resets';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'email',
        'token',
        'code',
        'plain_code',
        'channel',
        'target',
        'expires_at',
        'used_at',
        'created_at',
    ];

    protected $useTimestamps = false;

    /**
     * Pastikan tabel hmt_password_resets dan kolom pendukung siap di database
     */
    public function ensureTableExists(): void
    {
        $db = \Config\Database::connect();
        try {
            $tableExists = $db->tableExists($this->table);
            if (!$tableExists) {
                $forge = \Config\Database::forge();
                $forge->addField([
                    'id' => [
                        'type'           => 'INT',
                        'constraint'     => 10,
                        'unsigned'       => true,
                        'auto_increment' => true,
                    ],
                    'email' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 150,
                        'null'       => false,
                    ],
                    'token' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 64,
                        'null'       => false,
                    ],
                    'code' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 64,
                        'null'       => true,
                    ],
                    'plain_code' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 10,
                        'null'       => true,
                    ],
                    'channel' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 20,
                        'default'    => 'whatsapp',
                    ],
                    'target' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 100,
                        'null'       => true,
                    ],
                    'expires_at' => [
                        'type' => 'DATETIME',
                        'null' => false,
                    ],
                    'used_at' => [
                        'type' => 'DATETIME',
                        'null' => true,
                    ],
                    'created_at' => [
                        'type' => 'DATETIME',
                        'null' => false,
                    ],
                ]);

                $forge->addPrimaryKey('id');
                $forge->addKey('token');
                $forge->addKey('code');
                $forge->addKey('email');
                $forge->addKey('expires_at');
                $forge->createTable($this->table, true);
            }

            // Tambah kolom phone ke hmt_users jika belum ada
            if ($db->tableExists('hmt_users') && !$db->fieldExists('phone', 'hmt_users')) {
                $forge = \Config\Database::forge();
                $forge->addColumn('hmt_users', [
                    'phone' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 30,
                        'null'       => true,
                        'after'      => 'email',
                    ],
                ]);
            }
        } catch (\Throwable $e) {
            log_message('error', 'PasswordResetModel::ensureTableExists error: ' . $e->getMessage());
        }
    }

    /**
     * Buat token acak sementara berbasis kriptografi aman (CSPRNG).
     * Hanya hash SHA-256 dari token dan kode OTP yang disimpan di database.
     *
     * @param string      $email
     * @param string      $channel        'whatsapp' atau 'email'
     * @param string|null $target         Nomor WA terdaftar atau Email tujuan
     * @param int         $expiresMinutes Masa berlaku dalam menit (default 15 menit)
     * @return array
     */
    public function createToken(string $email, string $channel = 'whatsapp', ?string $target = null, int $expiresMinutes = 15): array
    {
        $this->ensureTableExists();

        $normalizedEmail = strtolower(trim($email));
        $now             = date('Y-m-d H:i:s');
        $expiresAt       = date('Y-m-d H:i:s', time() + ($expiresMinutes * 60));

        // Invalidate token sebelumnya yang belum terpakai untuk email ini
        $this->where('email', $normalizedEmail)
             ->where('used_at', null)
             ->set(['used_at' => $now])
             ->update();

        // 1. Generate Cryptographically Secure Random Token (32 bytes = 64 hex chars = 256-bit entropy)
        $plainToken = bin2hex(random_bytes(32));
        $tokenHash  = hash('sha256', $plainToken);

        // 2. Generate kode numerik 6 digit acak (CSPRNG), hanya simpan hash-nya (terikat ke email)
        $plainCode = sprintf('%06d', random_int(100000, 999999));
        $codeHash  = hash('sha256', $normalizedEmail . '|' . $plainCode);

        // 3. Simpan hanya hash ke database (tanpa plain_code demi keamanan)
        $insertData = [
            'email'      => $normalizedEmail,
            'token'      => $tokenHash,
            'code'       => $codeHash,
            'plain_code' => null,
            'channel'    => $channel,
            'target'     => $target,
            'expires_at' => $expiresAt,
            'used_at'    => null,
            'created_at' => $now,
        ];

        $this->insert($insertData);
        $insertId = $this->getInsertID();

        return [
            'id'              => $insertId,
            'email'           => $normalizedEmail,
            'plain_token'     => $plainToken,
            'plain_code'      => $plainCode,
            'token_hash'      => $tokenHash,
            'expires_at'      => $expiresAt,
            'expires_minutes' => $expiresMinutes,
            'channel'         => $channel,
            'target'          => $target,
        ];
    }

    /**
     * Validasi token (64-char hex) atau kode OTP 6-digit (wajib disertai email).
     *
     * @param string      $tokenOrCode Plain token (64 hex char) atau kode verifikasi (6 digit)
     * @param string|null $email       Email pengurus (wajib saat memverifikasi kode 6-digit)
     * @return array
     */
    public function validateToken(string $tokenOrCode, ?string $email = null): array
    {
        $this->ensureTableExists();

        $clean = trim($tokenOrCode);
        if (empty($clean)) {
            return [
                'valid'   => false,
                'message' => 'Token atau kode reset kata sandi tidak boleh kosong.',
            ];
        }

        $now    = date('Y-m-d H:i:s');
        $record = null;

        // Mode 1: Token tautan 256-bit (64 karakter hex)
        if (strlen($clean) === 64 && ctype_xdigit($clean)) {
            $tokenHash = hash('sha256', $clean);
            $record    = $this->where('token', $tokenHash)
                              ->orderBy('id', 'DESC')
                              ->first();
        }
        // Mode 2: Kode verifikasi 6-digit (WAJIB terikat dengan email pengurus)
        elseif (preg_match('/^\d{6}$/', $clean)) {
            $normalizedEmail = strtolower(trim((string) $email));
            if (empty($normalizedEmail)) {
                return [
                    'valid'   => false,
                    'message' => 'Untuk verifikasi menggunakan kode 6-digit, alamat email pengurus wajib diisi.',
                ];
            }

            $codeHash = hash('sha256', $normalizedEmail . '|' . $clean);
            $record   = $this->where('email', $normalizedEmail)
                             ->where('code', $codeHash)
                             ->orderBy('id', 'DESC')
                             ->first();
        }

        if (!$record) {
            return [
                'valid'   => false,
                'message' => 'Tautan atau kode reset kata sandi tidak valid atau tidak ditemukan.',
            ];
        }

        if (!empty($record['used_at'])) {
            return [
                'valid'   => false,
                'message' => 'Tautan reset kata sandi ini sudah pernah digunakan sebelumnya. Silakan ajukan reset baru jika diperlukan.',
            ];
        }

        if (strtotime($record['expires_at']) < strtotime($now)) {
            return [
                'valid'   => false,
                'message' => 'Tautan reset kata sandi telah kedaluwarsa (masa berlaku telah habis). Silakan minta tautan baru.',
            ];
        }

        $secondsRemaining = max(0, strtotime($record['expires_at']) - strtotime($now));
        $minutesRemaining = (int) ceil($secondsRemaining / 60);

        return [
            'valid'             => true,
            'record'            => $record,
            'seconds_remaining' => $secondsRemaining,
            'minutes_remaining' => $minutesRemaining,
        ];
    }

    /**
     * Tandai token sebagai telah digunakan dan selesaikan sesi reset
     *
     * @param int    $id
     * @param string $email
     * @return bool
     */
    public function consumeToken(int $id, string $email): bool
    {
        $this->ensureTableExists();
        $now = date('Y-m-d H:i:s');

        return (bool) $this->where('email', strtolower(trim($email)))
                           ->where('used_at', null)
                           ->set(['used_at' => $now])
                           ->update();
    }
}
