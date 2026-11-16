<?php

namespace App\Controllers;

use App\Models\CertificateModel;
use App\Models\EventModel;
use App\Libraries\CertificatePdf;

class CertificateController extends BaseController
{
    protected CertificateModel $certModel;
    protected EventModel $eventModel;

    public function __construct()
    {
        $this->certModel  = new CertificateModel();
        $this->eventModel = new EventModel();
    }

    /**
     * Halaman Publik: Form Feedback Kegiatan & Klaim E-Sertifikat
     */
    public function index()
    {
        // Ambil daftar event aktif / terbaru untuk opsi dropdown
        $events = $this->eventModel->orderBy('start_datetime', 'DESC')->findAll(10);

        $selectedEventId = $this->request->getGet('event_id');

        $data = [
            'title'           => 'Form Feedback Kegiatan & Unduh E-Sertifikat — HIMATIKA Hub',
            'events'          => $events,
            'selectedEventId' => $selectedEventId,
        ];

        return view('certificate/index', $data);
    }

    /**
     * Proses Validasi & Penyimpanan Feedback ke MySQL
     */
    public function submit()
    {
        // Anti-spam honeypot
        if (!empty($this->request->getPost('website_url'))) {
            return redirect()->to(site_url('sertifikat'))->with('error', 'Spam terdeteksi.');
        }

        $rules = [
            'participant_name' => 'required|min_length[3]|max_length[100]',
            'identifier'       => 'permit_empty|max_length[50]',
            'email'            => 'required|valid_email',
            'institution'      => 'permit_empty|max_length[150]',
            'event_title'      => 'required|min_length[3]|max_length[255]',
            'rating'           => 'required|in_list[1,2,3,4,5]',
            'feedback'         => 'required|min_length[5]',
            'suggestions'      => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $participantName = trim($this->request->getPost('participant_name'));
        $identifier      = trim($this->request->getPost('identifier'));
        $email           = strtolower(trim($this->request->getPost('email')));
        $institution     = trim($this->request->getPost('institution')) ?: 'Civitas Akademika';
        $eventTitle      = trim($this->request->getPost('event_title'));
        $eventId         = $this->request->getPost('event_id') ? (int)$this->request->getPost('event_id') : null;
        $rating          = (int)$this->request->getPost('rating');
        $feedback        = trim($this->request->getPost('feedback'));
        $suggestions     = trim($this->request->getPost('suggestions'));

        // Cek apakah peserta sudah pernah mengisi feedback untuk event yang sama
        $existing = $this->certModel->where('email', $email)
                                    ->where('event_name', $eventTitle)
                                    ->first();

        if ($existing) {
            // Update feedback terbaru jika sudah pernah ada, lalu arahkan ke halaman download
            $this->certModel->update($existing['id'], [
                'participant_name' => $participantName,
                'identifier'       => $identifier,
                'institution'      => $institution,
                'rating'           => $rating,
                'feedback'         => $feedback,
                'suggestions'      => $suggestions,
                'ip_address'       => $this->request->getIPAddress(),
            ]);

            return redirect()->to(site_url('sertifikat/sukses/' . $existing['verification_code']))
                             ->with('info', 'Feedback Anda telah diperbarui. Silakan unduh sertifikat resmi Anda di bawah ini.');
        }

        // Generate nomor sertifikat dan kode verifikasi unik
        $certNo = $this->certModel->generateCertificateNo();
        $verifyCode = $this->certModel->generateVerificationCode();

        $data = [
            'certificate_no'    => $certNo,
            'verification_code' => $verifyCode,
            'event_id'          => $eventId,
            'event_name'        => $eventTitle,
            'participant_name'  => $participantName,
            'identifier'        => $identifier,
            'email'             => $email,
            'institution'       => $institution,
            'role'              => 'Peserta',
            'rating'            => $rating,
            'feedback'          => $feedback,
            'suggestions'       => $suggestions,
            'download_count'    => 0,
            'ip_address'        => $this->request->getIPAddress(),
            'user_agent'        => $this->request->getUserAgent()->getAgentString(),
        ];

        $this->certModel->insert($data);

        return redirect()->to(site_url('sertifikat/sukses/' . $verifyCode))
                         ->with('success', 'Terima kasih atas feedback yang Anda berikan! E-Sertifikat resmi Anda telah berhasil diterbitkan.');
    }

    /**
     * Halaman Thank You: Menampilkan Pratinjau & Tombol Unduh Sertifikat
     */
    public function sukses(string $code)
    {
        $cert = $this->certModel->where('verification_code', $code)->first();

        if (!$cert) {
            return redirect()->to(site_url('sertifikat'))->with('error', 'Sertifikat dengan kode verifikasi tersebut tidak ditemukan.');
        }

        $data = [
            'title'       => 'Sertifikat Siap Diunduh — ' . esc($cert['participant_name']),
            'certificate' => $cert,
        ];

        return view('certificate/thankyou', $data);
    }

    /**
     * Pemrosesan PDF: Force Download File PDF ke Komputer Pengguna
     */
    public function download(string $code)
    {
        $cert = $this->certModel->where('verification_code', $code)->first();

        if (!$cert) {
            return redirect()->to(site_url('sertifikat'))->with('error', 'Sertifikat tidak ditemukan.');
        }

        // Catat statistik unduhan
        $this->certModel->update($cert['id'], [
            'download_count'     => (int)$cert['download_count'] + 1,
            'last_downloaded_at' => date('Y-m-d H:i:s'),
        ]);

        $pdfService = new CertificatePdf();
        $pdfService->generate($cert, 'D'); // Mode 'D' = Force Download
        exit;
    }

    /**
     * Pemrosesan PDF: Preview Inline di Browser Tab Baru
     */
    public function preview(string $code)
    {
        $cert = $this->certModel->where('verification_code', $code)->first();

        if (!$cert) {
            return redirect()->to(site_url('sertifikat'))->with('error', 'Sertifikat tidak ditemukan.');
        }

        $pdfService = new CertificatePdf();
        $pdfService->generate($cert, 'I'); // Mode 'I' = Inline Preview
        exit;
    }

    /**
     * Halaman Verifikasi Keaslian Sertifikat (Security Check)
     */
    public function verify(string $code = '')
    {
        $searchCode = $code ?: $this->request->getGet('code');
        $certificate = null;

        if (!empty($searchCode)) {
            $certificate = $this->certModel->where('verification_code', strtoupper(trim($searchCode)))
                                           ->orWhere('certificate_no', trim($searchCode))
                                           ->first();
        }

        $data = [
            'title'       => 'Verifikasi Keaslian E-Sertifikat — HIMATIKA Hub',
            'code'        => $searchCode,
            'certificate' => $certificate,
        ];

        return view('certificate/verify', $data);
    }

    /**
     * Area Dashboard Pengurus: Pantau Seluruh Feedback & Rekap Sertifikat
     */
    public function adminIndex()
    {
        $eventId = $this->request->getGet('event_id');
        $rating  = $this->request->getGet('rating');
        $search  = $this->request->getGet('q');

        $builder = $this->certModel->orderBy('created_at', 'DESC');

        if (!empty($eventId)) {
            $builder->where('event_id', (int)$eventId);
        }
        if (!empty($rating)) {
            $builder->where('rating', (int)$rating);
        }
        if (!empty($search)) {
            $builder->groupStart()
                    ->like('participant_name', $search)
                    ->orLike('email', $search)
                    ->orLike('certificate_no', $search)
                    ->orLike('event_name', $search)
                    ->groupEnd();
        }

        $certificates = $builder->findAll();
        $events       = $this->eventModel->orderBy('start_datetime', 'DESC')->findAll();

        // Hitung statistik
        $all = $this->certModel->findAll();
        $totalCerts = count($all);
        $avgRating = $totalCerts > 0 ? array_sum(array_column($all, 'rating')) / $totalCerts : 0;
        $totalDownloads = array_sum(array_column($all, 'download_count'));

        $data = [
            'title'          => 'Rekap Feedback & E-Sertifikat — Workspace Pengurus',
            'certificates'   => $certificates,
            'events'         => $events,
            'selectedEvent'  => $eventId,
            'selectedRating' => $rating,
            'searchQuery'    => $search,
            'stats'          => [
                'total'          => $totalCerts,
                'avg_rating'     => round($avgRating, 1),
                'total_downloads'=> $totalDownloads,
            ]
        ];

        return view('dashboard/certificates/index', $data);
    }
}
