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
                $forge->addKey('email');
                $forge->addKey('plain_code');
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
     * Buat token acak sementara berbasis kriptografi aman (CSPRNG)
     *
     * @param string $email
     * @param string $channel 'whatsapp' atau 'email'
     * @param string|null $target Nomor WA atau Email tujuan
     * @param int $expiresMinutes Masa berlaku dalam menit (default 15 menit)
     * @return array
     */
    public function createToken(string $email, string $channel = 'whatsapp', ?string $target = null, int $expiresMinutes = 15): array
    {
        $this->ensureTableExists();

        $now = date('Y-m-d H:i:s');
        $expiresAt = date('Y-m-d H:i:s', time() + ($expiresMinutes * 60));

        // Invalidate token sebelumnya yang belum terpakai untuk email ini
        $this->where('email', $email)
             ->where('used_at', null)
             ->set(['used_at' => $now])
             ->update();

        // 1. Generate Cryptographically Secure Random Token (32 bytes = 64 hex characters = 256 bits entropy)
        $plainToken = bin2hex(random_bytes(32));
        $tokenHash  = hash('sha256', $plainToken);

        // 2. Generate kode numerik 6 digit acak (CSPRNG) untuk opsi verifikasi cepat
        $plainCode = sprintf('%06d', random_int(100000, 999999));
        $codeHash  = hash('sha256', $plainCode);

        // 3. Simpan hash token ke database (OWASP Best Practice)
        $insertData = [
            'email'      => $email,
            'token'      => $tokenHash,
            'code'       => $codeHash,
            'plain_code' => $plainCode,
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
            'email'           => $email,
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
     * Validasi token atau kode reset kata sandi
     *
     * @param string $tokenOrCode Plain token (64 char) atau kode verifikasi (6 digit)
     * @return array
     */
    public function validateToken(string $tokenOrCode): array
    {
        $this->ensureTableExists();

        $clean = trim($tokenOrCode);
        if (empty($clean)) {
            return [
                'valid'   => false,
                'message' => 'Token reset kata sandi tidak boleh kosong.',
            ];
        }

        $now = date('Y-m-d H:i:s');
        $tokenHash = hash('sha256', $clean);

        // Cari berdasarkan hash token ATAU plain_code 6 digit
        $record = $this->groupStart()
                       ->where('token', $tokenHash)
                       ->orWhere('plain_code', $clean)
                       ->groupEnd()
                       ->orderBy('id', 'DESC')
                       ->first();

        if (!$record) {
            return [
                'valid'   => false,
                'message' => 'Tautan atau token reset kata sandi tidak valid atau tidak ditemukan.',
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

        // Hitung sisa waktu dalam menit
        $secondsRemaining = max(0, strtotime($record['expires_at']) - strtotime($now));
        $minutesRemaining = ceil($secondsRemaining / 60);

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
     * @param int $id
     * @param string $email
     * @return bool
     */
    public function consumeToken(int $id, string $email): bool
    {
        $this->ensureTableExists();
        $now = date('Y-m-d H:i:s');

        // Tandai token ini digunakan dan invalidate token lain untuk email yang sama
        return (bool) $this->where('email', $email)
                           ->where('used_at', null)
                           ->set(['used_at' => $now])
                           ->update();
    }
}
