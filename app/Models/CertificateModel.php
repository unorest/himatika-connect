<?php

namespace App\Models;

use CodeIgniter\Model;

class CertificateModel extends Model
{
    protected $table            = 'hmt_certificates';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'certificate_no',
        'verification_code',
        'event_id',
        'event_name',
        'participant_name',
        'identifier',
        'email',
        'institution',
        'role',
        'rating',
        'feedback',
        'suggestions',
        'download_count',
        'last_downloaded_at',
        'ip_address',
        'user_agent',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    /**
     * Generate unique Certificate Number: CERT/HMT/YEAR/ROMAN_MONTH/0001
     */
    public function generateCertificateNo(): string
    {
        $year = date('Y');
        $month = (int)date('n');
        $romans = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'];
        $romanMonth = $romans[$month] ?? 'I';

        $countThisYear = $this->where('YEAR(created_at)', $year, false)->countAllResults();
        $sequence = str_pad((string)($countThisYear + 1), 4, '0', STR_PAD_LEFT);

        return "CERT/HMT/{$year}/{$romanMonth}/{$sequence}";
    }

    /**
     * Generate unique verification code: HMT-XXXXXX
     */
    public function generateVerificationCode(): string
    {
        do {
            $code = 'HMT-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
            $exists = $this->where('verification_code', $code)->first();
        } while (!empty($exists));

        return $code;
    }
}
