<?php

namespace App\Libraries;

use App\Libraries\FPDF;

class CertificatePdf
{
    protected FPDF $pdf;

    public function __construct()
    {
        // A4 Landscape: 297 mm x 210 mm
        $this->pdf = new FPDF('L', 'mm', 'A4');
        $this->pdf->SetAutoPageBreak(false);
    }

    /**
     * Generate the official PDF certificate
     *
     * @param array $data Certificate and event details
     * @param string $outputMode 'D' for force download, 'I' for inline preview, 'S' for string
     * @return string
     */
    public function generate(array $data, string $outputMode = 'D'): string
    {
        $this->pdf->AddPage('L', 'A4');

        $participantName = strtoupper(trim($data['participant_name'] ?? 'NAMA PESERTA'));
        $identifier      = trim($data['identifier'] ?? '');
        $institution     = trim($data['institution'] ?? 'Civitas Akademika');
        $eventName       = trim($data['event_name'] ?? 'Kegiatan HIMATIKA Connect');
        $certificateNo   = trim($data['certificate_no'] ?? 'CERT/HMT/' . date('Y') . '/001');
        $verificationCode= trim($data['verification_code'] ?? 'HMT-' . strtoupper(substr(md5($certificateNo), 0, 8)));
        $role            = strtoupper(trim($data['role'] ?? 'PESERTA'));
        $dateFormatted   = date('d F Y', strtotime($data['created_at'] ?? 'now'));

        // Check if an image template exists
        $templateFound = false;
        $basePath = defined('FCPATH') ? FCPATH : dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR;
        $possibleTemplates = [
            $basePath . 'images' . DIRECTORY_SEPARATOR . 'certificate_template.png',
            $basePath . 'images' . DIRECTORY_SEPARATOR . 'certificate_template.jpg',
            $basePath . 'assets' . DIRECTORY_SEPARATOR . 'certificate_template.jpg',
        ];

        foreach ($possibleTemplates as $tmpl) {
            if (file_exists($tmpl)) {
                $this->pdf->Image($tmpl, 0, 0, 297, 210);
                $templateFound = true;
                break;
            }
        }

        // If no template image is uploaded, render a beautiful official vector certificate
        if (!$templateFound) {
            $this->drawVectorCertificate($certificateNo, $dateFormatted);
        }

        // 1. Participant Name at regulated coordinates (X=0, Y=98, Width=297, Height=14)
        $this->pdf->SetFont('Helvetica', 'B', 24);
        $this->pdf->SetTextColor(30, 58, 138); // Deep Royal Blue (#1E3A8A)
        $this->pdf->SetXY(0, 96);
        $this->pdf->Cell(297, 14, $participantName, 0, 1, 'C');

        // Golden underline under name
        $this->pdf->SetDrawColor(217, 119, 6); // Amber Gold
        $this->pdf->SetLineWidth(0.8);
        $this->pdf->Line(68, 112, 229, 112);

        // 2. Identifier / Institution
        if (!empty($identifier) || !empty($institution)) {
            $this->pdf->SetFont('Helvetica', '', 10);
            $this->pdf->SetTextColor(100, 116, 139); // Slate 500
            $this->pdf->SetXY(0, 113.5);
            $idText = trim(($identifier ? "NIM/ID: {$identifier}" : '') . ($identifier && $institution ? ' | ' : '') . ($institution ?: ''));
            $this->pdf->Cell(297, 6, $idText, 0, 1, 'C');
        }

        // 3. Role & Event Title
        $this->pdf->SetFont('Helvetica', '', 11);
        $this->pdf->SetTextColor(71, 85, 105); // Slate 600
        $this->pdf->SetXY(0, 122);
        $this->pdf->Cell(297, 6, "Atas partisipasi aktif dan kontribusinya sebagai {$role} dalam kegiatan:", 0, 1, 'C');

        $this->pdf->SetFont('Helvetica', 'B', 15);
        $this->pdf->SetTextColor(15, 23, 42); // Slate 900
        $this->pdf->SetXY(20, 129);
        $this->pdf->Cell(257, 8, '"' . $eventName . '"', 0, 1, 'C');

        // 4. Verification Box on the left
        $this->pdf->SetDrawColor(226, 232, 240);
        $this->pdf->SetFillColor(248, 250, 252);
        $this->pdf->Rect(22, 166, 68, 24, 'FD');

        $this->pdf->SetFont('Helvetica', 'B', 7.5);
        $this->pdf->SetTextColor(30, 41, 59);
        $this->pdf->SetXY(24, 168);
        $this->pdf->Cell(64, 4, 'SECURITY VERIFICATION', 0, 1, 'L');

        $this->pdf->SetFont('Helvetica', '', 7);
        $this->pdf->SetTextColor(100, 116, 139);
        $this->pdf->SetXY(24, 172.5);
        $this->pdf->Cell(64, 4, 'Kode: ' . $verificationCode, 0, 1, 'L');
        $this->pdf->SetXY(24, 177);
        $this->pdf->Cell(64, 4, 'Cek Keaslian: himatika.org/verify', 0, 1, 'L');
        $this->pdf->SetXY(24, 181.5);
        $this->pdf->Cell(64, 4, 'Status: Terverifikasi Digital Resmi', 0, 1, 'L');

        // 5. Signatures on the right
        $this->pdf->SetFont('Helvetica', 'B', 10);
        $this->pdf->SetTextColor(30, 41, 59);

        // Signer 1: Ketum HIMATIKA
        $this->pdf->SetXY(120, 162);
        $this->pdf->Cell(65, 5, 'Ketua Umum HIMATIKA', 0, 1, 'C');
        $this->pdf->SetFont('Helvetica', 'I', 8.5);
        $this->pdf->SetTextColor(100, 116, 139);
        $this->pdf->SetXY(120, 167);
        $this->pdf->Cell(65, 4, '[Signed Digitally]', 0, 1, 'C');
        $this->pdf->SetFont('Helvetica', 'B', 9.5);
        $this->pdf->SetTextColor(15, 23, 42);
        $this->pdf->SetXY(120, 182);
        $this->pdf->Cell(65, 5, 'Admin HIMATIKA', 0, 1, 'C');
        $this->pdf->SetFont('Helvetica', '', 8);
        $this->pdf->SetTextColor(100, 116, 139);
        $this->pdf->SetXY(120, 186.5);
        $this->pdf->Cell(65, 4, 'NIM. 22010001', 0, 1, 'C');

        // Signer 2: Ketua Pelaksana / Kadiv
        $this->pdf->SetFont('Helvetica', 'B', 10);
        $this->pdf->SetTextColor(30, 41, 59);
        $this->pdf->SetXY(210, 162);
        $this->pdf->Cell(65, 5, 'Ketua Pelaksana Kegiatan', 0, 1, 'C');
        $this->pdf->SetFont('Helvetica', 'I', 8.5);
        $this->pdf->SetTextColor(100, 116, 139);
        $this->pdf->SetXY(210, 167);
        $this->pdf->Cell(65, 4, '[Signed Digitally]', 0, 1, 'C');
        $this->pdf->SetFont('Helvetica', 'B', 9.5);
        $this->pdf->SetTextColor(15, 23, 42);
        $this->pdf->SetXY(210, 182);
        $this->pdf->Cell(65, 5, 'Devina Putri', 0, 1, 'C');
        $this->pdf->SetFont('Helvetica', '', 8);
        $this->pdf->SetTextColor(100, 116, 139);
        $this->pdf->SetXY(210, 186.5);
        $this->pdf->Cell(65, 4, 'NIM. 22010005', 0, 1, 'C');

        $cleanFilename = 'Sertifikat_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $participantName) . '.pdf';
        return $this->pdf->Output($outputMode, $cleanFilename);
    }

    /**
     * Draw an executive vector certificate layout with borders and badges
     */
    protected function drawVectorCertificate(string $certificateNo, string $dateFormatted): void
    {
        // 1. Background fill
        $this->pdf->SetFillColor(255, 255, 255);
        $this->pdf->Rect(0, 0, 297, 210, 'F');

        // 2. Outer Border (Royal Deep Navy #1E3A8A)
        $this->pdf->SetDrawColor(30, 58, 138);
        $this->pdf->SetLineWidth(2.5);
        $this->pdf->Rect(8, 8, 281, 194, 'D');

        // 3. Inner Gold Border (#D97706)
        $this->pdf->SetDrawColor(217, 119, 6);
        $this->pdf->SetLineWidth(0.8);
        $this->pdf->Rect(12, 12, 273, 186, 'D');

        // 4. Subtle background tint
        $this->pdf->SetFillColor(248, 250, 252);
        $this->pdf->Rect(13, 13, 271, 184, 'F');

        // 5. Header Ribbon Banner
        $this->pdf->SetFillColor(30, 58, 138);
        $this->pdf->Rect(13, 13, 271, 16, 'F');

        $this->pdf->SetFont('Helvetica', 'B', 8.5);
        $this->pdf->SetTextColor(255, 255, 255);
        $this->pdf->SetXY(13, 18);
        $this->pdf->Cell(271, 6, 'HIMPUNAN MAHASISWA INFORMATIKA & MATEMATIKA — PORTAL RESMI HIMATIKA CONNECT', 0, 1, 'C');

        // 6. Organization Logo / Badge Text
        $this->pdf->SetFont('Helvetica', 'B', 12);
        $this->pdf->SetTextColor(217, 119, 6);
        $this->pdf->SetXY(0, 36);
        $this->pdf->Cell(297, 6, 'DEWAN PENGURUS HIMATIKA', 0, 1, 'C');

        // 7. Title "SERTIFIKAT PENGHARGAAN"
        $this->pdf->SetFont('Helvetica', 'B', 28);
        $this->pdf->SetTextColor(15, 23, 42); // Slate 900
        $this->pdf->SetXY(0, 44);
        $this->pdf->Cell(297, 12, 'SERTIFIKAT PENGHARGAAN', 0, 1, 'C');

        $this->pdf->SetFont('Helvetica', 'I', 12);
        $this->pdf->SetTextColor(100, 116, 139);
        $this->pdf->SetXY(0, 57);
        $this->pdf->Cell(297, 6, 'Certificate of Participation & Appreciation', 0, 1, 'C');

        // 8. Certificate Number
        $this->pdf->SetFont('Helvetica', 'B', 8.5);
        $this->pdf->SetTextColor(71, 85, 105);
        $this->pdf->SetXY(0, 66);
        $this->pdf->Cell(297, 5, 'Nomor: ' . $certificateNo, 0, 1, 'C');

        // 9. Statement "Diberikan dengan bangga kepada:"
        $this->pdf->SetFont('Helvetica', '', 11);
        $this->pdf->SetTextColor(51, 65, 85);
        $this->pdf->SetXY(0, 84);
        $this->pdf->Cell(297, 6, 'Diberikan dengan bangga kepada:', 0, 1, 'C');
    }
}
