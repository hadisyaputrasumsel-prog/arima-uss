<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Scheme;
use App\Models\Proposal;
use App\Models\ProposalMember;
use App\Models\ProposalRab;
use App\Models\ProposalDocument;
use App\Models\ReviewerAssignment;
use App\Models\Review;
use App\Models\Contract;
use App\Models\Disbursement;
use App\Models\ProgressReport;
use App\Models\Output;
use App\Models\ActivityLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Users for all roles
        $adminTi = User::create([
            'name' => 'Administrator TI USS',
            'email' => 'admin.ti@uss.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin_ti',
            'nidn' => '0001019001',
            'nip' => '199001012015011001',
            'jabatan_fungsional' => 'Tenaga Pengajar',
            'fakultas' => 'Teknologi Informasi',
            'program_studi' => 'Sistem Informasi',
            'no_hp' => '081234567890',
        ]);

        $adminLppm = User::create([
            'name' => 'LPPM Universitas Sumatera Selatan',
            'email' => 'lppm@uss.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin_lppm',
            'nidn' => '0211047801',
            'nip' => '197804112003121002',
            'jabatan_fungsional' => 'Lektor Kepala',
            'fakultas' => 'LPPM',
            'program_studi' => 'Pengelola Hibah Internal',
            'no_hp' => '081298765432',
        ]);

        $dosen1 = User::create([
            'name' => 'Dr. Ahmad Fauzi, M.Kom.',
            'email' => 'dosen1@uss.ac.id',
            'password' => Hash::make('password'),
            'role' => 'dosen',
            'nidn' => '0214058201',
            'nip' => '198205142008121001',
            'jabatan_fungsional' => 'Lektor',
            'fakultas' => 'Ilmu Komputer',
            'program_studi' => 'Teknik Informatika',
            'no_hp' => '081377889901',
            'bank_name' => 'Bank Sumsel Babel',
            'bank_account_number' => '1400982711',
            'bank_account_holder' => 'Ahmad Fauzi',
        ]);

        $dosen2 = User::create([
            'name' => 'Siti Rahma, S.T., M.T.',
            'email' => 'dosen2@uss.ac.id',
            'password' => Hash::make('password'),
            'role' => 'dosen',
            'nidn' => '0221088902',
            'nip' => '198908212014042001',
            'jabatan_fungsional' => 'Asisten Ahli',
            'fakultas' => 'Teknik',
            'program_studi' => 'Teknik Elektro',
            'no_hp' => '081377889902',
            'bank_name' => 'Bank Mandiri',
            'bank_account_number' => '1130018827162',
            'bank_account_holder' => 'Siti Rahma',
        ]);

        $reviewer1 = User::create([
            'name' => 'Prof. Dr. Hendra Wijaya, M.Sc.',
            'email' => 'reviewer1@uss.ac.id',
            'password' => Hash::make('password'),
            'role' => 'reviewer',
            'nidn' => '0205037501',
            'nip' => '197503052000031001',
            'jabatan_fungsional' => 'Guru Besar',
            'fakultas' => 'Pertanian',
            'program_studi' => 'Agroteknologi',
            'no_hp' => '08117123456',
        ]);

        $reviewer2 = User::create([
            'name' => 'Dr. Nurul Hidayati, M.Si.',
            'email' => 'reviewer2@uss.ac.id',
            'password' => Hash::make('password'),
            'role' => 'reviewer',
            'nidn' => '0218118003',
            'nip' => '198011182005012003',
            'jabatan_fungsional' => 'Lektor Kepala',
            'fakultas' => 'Ekonomi dan Bisnis',
            'program_studi' => 'Manajemen',
            'no_hp' => '08127345678',
        ]);

        $keuangan = User::create([
            'name' => 'Bagian Keuangan USS',
            'email' => 'keuangan@uss.ac.id',
            'password' => Hash::make('password'),
            'role' => 'keuangan',
            'nidn' => null,
            'nip' => '198506122009021003',
            'jabatan_fungsional' => 'Staf Keuangan',
            'fakultas' => 'Biro Administrasi Keuangan',
            'program_studi' => 'Perbendaharaan',
            'no_hp' => '081366554433',
        ]);

        $pimpinan = User::create([
            'name' => 'Prof. Dr. Ir. M. Yusuf, M.T. (Wakil Rektor I)',
            'email' => 'pimpinan@uss.ac.id',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
            'nidn' => '0209076801',
            'nip' => '196807091994031002',
            'jabatan_fungsional' => 'Guru Besar',
            'fakultas' => 'Rektorat USS',
            'program_studi' => 'Wakil Rektor I Bidang Akademik & Riset',
            'no_hp' => '08117890123',
        ]);

        // 2. Create Schemes
        $schemePenelitian = Scheme::create([
            'nama_skema' => 'Hibah Penelitian Dosen Pemula & Terapan USS',
            'tahun_anggaran' => '2026',
            'kategori' => 'penelitian',
            'pagu_anggaran' => 250000000,
            'maks_dana_usulan' => 25000000,
            'tgl_mulai_pengusulan' => now()->subDays(20),
            'tgl_akhir_pengusulan' => now()->addDays(25),
            'tgl_mulai_review' => now()->addDays(26),
            'tgl_akhir_review' => now()->addDays(36),
            'tgl_pengumuman' => now()->addDays(40),
            'tgl_monev' => now()->addMonths(4),
            'tgl_laporan_akhir' => now()->addMonths(7),
            'deskripsi' => 'Skema hibah pendanaan internal penelitian bagi dosen Universitas Sumatera Selatan untuk mempercepat publikasi bereputasi dan kesiapan teknologi.',
            'is_active' => true,
        ]);

        $schemePengabdian = Scheme::create([
            'nama_skema' => 'Hibah Pengabdian kepada Masyarakat Berbasis Kemitraan USS',
            'tahun_anggaran' => '2026',
            'kategori' => 'pengabdian',
            'pagu_anggaran' => 150000000,
            'maks_dana_usulan' => 20000000,
            'tgl_mulai_pengusulan' => now()->subDays(20),
            'tgl_akhir_pengusulan' => now()->addDays(25),
            'tgl_mulai_review' => now()->addDays(26),
            'tgl_akhir_review' => now()->addDays(36),
            'tgl_pengumuman' => now()->addDays(40),
            'tgl_monev' => now()->addMonths(3),
            'tgl_laporan_akhir' => now()->addMonths(6),
            'deskripsi' => 'Skema hibah pengabdian internal berkolaborasi dengan kelompok masyarakat sasaran atau UMKM di wilayah Sumatera Selatan.',
            'is_active' => true,
        ]);

        // 3. Create Sample Proposal 1 (Running / In Progress)
        $prop1 = Proposal::create([
            'scheme_id' => $schemePenelitian->id,
            'user_id' => $dosen1->id,
            'judul' => 'Pengembangan Sistem Cerdas Deteksi Dini Penyakit Tanaman Padi Berbasis Computer Vision di Lahan Rawa Pasang Surut Sumsel',
            'kategori' => 'penelitian',
            'bidang_fokus' => 'Teknologi Informasi dan Komunikasi / Ketahanan Pangan',
            'tkt' => 3,
            'lama_kegiatan' => 6,
            'lokasi_kegiatan' => 'Kabupaten Banyuasin, Sumatera Selatan',
            'total_dana_usulan' => 24000000,
            'dana_disetujui' => 24000000,
            'ringkasan' => 'Penelitian ini mengembangkan model computer vision berbasis deep learning untuk mendeteksi hama dan penyakit pada daun padi di lahan rawa pasang surut Sumsel secara real-time via aplikasi mobile.',
            'status' => 'in_progress',
            'sk_penetapan_no' => 'SK/014/REK-USS/HIBAH/2026',
            'sk_penetapan_tgl' => now()->subDays(15),
        ]);

        // Members for prop1
        ProposalMember::create([
            'proposal_id' => $prop1->id,
            'user_id' => $dosen1->id,
            'nama_lengkap' => $dosen1->name,
            'nidn_nip' => $dosen1->nidn,
            'program_studi' => $dosen1->program_studi,
            'fakultas' => $dosen1->fakultas,
            'peran_tim' => 'Ketua Pengusul',
            'tugas_tim' => 'Koordinator penelitian, perancangan model AI, dan penyusunan artikel publikasi.',
            'status_konfirmasi' => 'approved',
            'tgl_konfirmasi' => now()->subDays(20),
        ]);

        ProposalMember::create([
            'proposal_id' => $prop1->id,
            'user_id' => $dosen2->id,
            'nama_lengkap' => $dosen2->name,
            'nidn_nip' => $dosen2->nidn,
            'program_studi' => $dosen2->program_studi,
            'fakultas' => $dosen2->fakultas,
            'peran_tim' => 'Anggota Dosen',
            'tugas_tim' => 'Integrasi hardware sensor lapang dan pengujian keandalan perangkat.',
            'status_konfirmasi' => 'approved',
            'tgl_konfirmasi' => now()->subDays(19),
        ]);

        // RAB for prop1 (Total: 24.000.000, compliant with max %)
        // Honor: 6.000.000 (25% <= 30%)
        ProposalRab::create([
            'proposal_id' => $prop1->id,
            'kategori_biaya' => 'honorarium',
            'item_biaya' => 'Honorarium Ketua Peneliti (6 bulan)',
            'volume' => 1,
            'satuan' => 'Paket',
            'harga_satuan' => 3600000,
            'total_biaya' => 3600000,
            'keterangan' => 'Standar Biaya Masukan USS',
        ]);
        ProposalRab::create([
            'proposal_id' => $prop1->id,
            'kategori_biaya' => 'honorarium',
            'item_biaya' => 'Honorarium Anggota Peneliti (6 bulan)',
            'volume' => 1,
            'satuan' => 'Paket',
            'harga_satuan' => 2400000,
            'total_biaya' => 2400000,
            'keterangan' => 'Standar Biaya Masukan USS',
        ]);
        // Bahan: 8.000.000 (33.3% <= 40%)
        ProposalRab::create([
            'proposal_id' => $prop1->id,
            'kategori_biaya' => 'bahan_peralatan',
            'item_biaya' => 'Komponen IoT ESP32 & Modul Kamera Edge',
            'volume' => 4,
            'satuan' => 'Set',
            'harga_satuan' => 1500000,
            'total_biaya' => 6000000,
            'keterangan' => 'Sensor lapangan pasang surut',
        ]);
        ProposalRab::create([
            'proposal_id' => $prop1->id,
            'kategori_biaya' => 'bahan_peralatan',
            'item_biaya' => 'Bahan Habis Pakai & Reagen Lapangan',
            'volume' => 1,
            'satuan' => 'Paket',
            'harga_satuan' => 2000000,
            'total_biaya' => 2000000,
            'keterangan' => 'Pengambilan sampel daun padi',
        ]);
        // Perjalanan: 4.500.000 (18.75% <= 20%)
        ProposalRab::create([
            'proposal_id' => $prop1->id,
            'kategori_biaya' => 'perjalanan',
            'item_biaya' => 'Transportasi dan Akomodasi Lapang Banyuasin',
            'volume' => 3,
            'satuan' => 'Kunjungan',
            'harga_satuan' => 1500000,
            'total_biaya' => 4500000,
            'keterangan' => 'Pengambilan data primer petani',
        ]);
        // Publikasi: 3.500.000 (14.58% <= 15%)
        ProposalRab::create([
            'proposal_id' => $prop1->id,
            'kategori_biaya' => 'publikasi_luaran',
            'item_biaya' => 'Biaya Publikasi Jurnal Terakreditasi SINTA 2 & Pendaftaran HKI',
            'volume' => 1,
            'satuan' => 'Artikel',
            'harga_satuan' => 3500000,
            'total_biaya' => 3500000,
            'keterangan' => 'Target luaran wajib',
        ]);
        // Lain-lain: 2.000.000 (8.33% <= 10%)
        ProposalRab::create([
            'proposal_id' => $prop1->id,
            'kategori_biaya' => 'lain_lain',
            'item_biaya' => 'ATK, Penggandaan Berkas, dan Laporan Monev/Akhir',
            'volume' => 1,
            'satuan' => 'Paket',
            'harga_satuan' => 2000000,
            'total_biaya' => 2000000,
            'keterangan' => 'Administrasi dan penjilidan',
        ]);

        // Reviewer assignment for prop1 (2 reviewers lintas fakultas)
        $asg1 = ReviewerAssignment::create([
            'proposal_id' => $prop1->id,
            'reviewer_id' => $reviewer1->id,
            'assigned_by' => $adminLppm->id,
            'status' => 'completed',
        ]);

        $asg2 = ReviewerAssignment::create([
            'proposal_id' => $prop1->id,
            'reviewer_id' => $reviewer2->id,
            'assigned_by' => $adminLppm->id,
            'status' => 'completed',
        ]);

        Review::create([
            'assignment_id' => $asg1->id,
            'proposal_id' => $prop1->id,
            'reviewer_id' => $reviewer1->id,
            'skor_metodologi' => 5,
            'catatan_metodologi' => 'Pendekatan computer vision relevan dengan kondisi lapangan pertanian Sumsel.',
            'skor_rab' => 4,
            'catatan_rab' => 'RAB wajar dan mematuhi plafon.',
            'skor_luaran' => 5,
            'catatan_luaran' => 'Target SINTA 2 dan HKI sangat menjanjikan.',
            'skor_rekam_jejak' => 4,
            'catatan_rekam_jejak' => 'Tim memiliki publikasi relevan sebelumnya.',
            'nilai_akhir' => Review::calculateScore(5, 4, 5, 4), // 92.0
            'rekomendasi' => 'didanai',
            'catatan_umum' => 'Sangat layak didanai. Pastikan dataset mencakup variasi pencahayaan rawa.',
        ]);

        Review::create([
            'assignment_id' => $asg2->id,
            'proposal_id' => $prop1->id,
            'reviewer_id' => $reviewer2->id,
            'skor_metodologi' => 4,
            'catatan_metodologi' => 'Metode terstruktur rapi.',
            'skor_rab' => 5,
            'catatan_rab' => 'Rincian anggaran transparan dan proporsional.',
            'skor_luaran' => 4,
            'catatan_luaran' => 'Luaran artikel dan HKI realistis.',
            'skor_rekam_jejak' => 4,
            'catatan_rekam_jejak' => 'Komposisi tim solid.',
            'nilai_akhir' => Review::calculateScore(4, 5, 4, 4), // 84.0
            'rekomendasi' => 'didanai',
            'catatan_umum' => 'Direkomendasikan untuk didanai dengan alokasi penuh.',
        ]);

        // Contract for prop1
        Contract::create([
            'proposal_id' => $prop1->id,
            'nomor_kontrak' => '042/LPPM-USS/SPK-HI/2026',
            'tanggal_kontrak' => now()->subDays(12),
            'nilai_kontrak' => 24000000,
            'status_ttd' => 'active',
            'ttd_pengusul_tgl' => now()->subDays(11),
            'ttd_lppm_tgl' => now()->subDays(10),
            'catatan' => 'Perjanjian penugasan pelaksanaan penelitian internal.',
        ]);

        // Disbursements for prop1 (Termin 1 70% paid, Termin 2 30% pending)
        Disbursement::create([
            'proposal_id' => $prop1->id,
            'termin' => 'termin_1',
            'persentase' => 70,
            'nominal' => 16800000,
            'status' => 'paid',
            'tanggal_cair' => now()->subDays(8),
            'nomor_transaksi' => 'TRF-202610-00192',
            'bukti_transfer' => 'transfer_termin1_prop1.pdf',
            'catatan' => 'Pencairan termin 1 (70%) ke rekening Bank Sumsel Babel pengusul.',
            'processed_by' => $keuangan->id,
        ]);

        Disbursement::create([
            'proposal_id' => $prop1->id,
            'termin' => 'termin_2',
            'persentase' => 30,
            'nominal' => 7200000,
            'status' => 'pending',
            'catatan' => 'Menunggu pelaksanaan monev, penyerahan laporan akhir dan validasi luaran wajib.',
        ]);

        // Outputs for prop1
        Output::create([
            'proposal_id' => $prop1->id,
            'jenis_luaran' => 'Artikel Ilmiah di Jurnal Nasional Terakreditasi SINTA 2',
            'target_uraian' => 'Artikel berjudul deteksi penyakit padi lahan rawa disubmit ke Jurnal Nasional SINTA 2',
            'status_capaian' => 'submitted',
            'tautan_luaran' => 'https://journal.example.ac.id/index.php/jurnal/article/view/120',
            'keterangan' => 'Status Under Review',
            'is_verified' => false,
        ]);
        Output::create([
            'proposal_id' => $prop1->id,
            'jenis_luaran' => 'Sertifikat Hak Cipta / HKI',
            'target_uraian' => 'Hak Cipta program komputer deteksi visual penyakit padi',
            'status_capaian' => 'draft',
            'keterangan' => 'Sedang menyiapkan berkas pendaftaran ke DJKI',
            'is_verified' => false,
        ]);

        // Sample Proposal 2 (Submitted - Admin Review)
        $prop2 = Proposal::create([
            'scheme_id' => $schemePengabdian->id,
            'user_id' => $dosen2->id,
            'judul' => 'Pendampingan Digitalisasi Pemasaran dan Pembukuan Keuangan bagi Paguyuban Perajin Pempek Tradisional di Kawasan Seberang Ulu Palembang',
            'kategori' => 'pengabdian',
            'bidang_fokus' => 'Ekonomi Kreatif dan Pemberdayaan Masyarakat',
            'tkt' => null,
            'lama_kegiatan' => 6,
            'lokasi_kegiatan' => 'Kecamatan Seberang Ulu II, Palembang',
            'total_dana_usulan' => 18500000,
            'ringkasan' => 'Program pengabdian masyarakat ini bertujuan meningkatkan daya saing 25 UMKM kuliner pempek di Palembang melalui pelatihan digital marketing, katalog online, dan aplikasi pembukuan kas UMKM.',
            'status' => 'submitted',
        ]);

        ProposalMember::create([
            'proposal_id' => $prop2->id,
            'user_id' => $dosen2->id,
            'nama_lengkap' => $dosen2->name,
            'nidn_nip' => $dosen2->nidn,
            'program_studi' => $dosen2->program_studi,
            'fakultas' => $dosen2->fakultas,
            'peran_tim' => 'Ketua Pengusul',
            'tugas_tim' => 'Penanggung jawab kegiatan pelatihan dan monitoring mitra.',
            'status_konfirmasi' => 'approved',
            'tgl_konfirmasi' => now()->subDays(2),
        ]);

        // Proposal 3 (Draft by dosen1)
        $prop3 = Proposal::create([
            'scheme_id' => $schemePenelitian->id,
            'user_id' => $dosen1->id,
            'judul' => 'Rancang Bangun Perangkat Telemetri IoT Pemantauan Kualitas Air Sungai Musi untuk Budidaya Ikan Keramba Jaring Apung',
            'kategori' => 'penelitian',
            'bidang_fokus' => 'Lingkungan Hidup dan Kemaritiman',
            'tkt' => 2,
            'lama_kegiatan' => 6,
            'lokasi_kegiatan' => 'Sungai Musi, Palembang',
            'total_dana_usulan' => 20000000,
            'ringkasan' => 'Draf usulan penelitian pemantauan parameter pH, suhu, dan oksigen terlarut Sungai Musi berbasis IoT.',
            'status' => 'draft',
        ]);

        ProposalMember::create([
            'proposal_id' => $prop3->id,
            'user_id' => $dosen1->id,
            'nama_lengkap' => $dosen1->name,
            'nidn_nip' => $dosen1->nidn,
            'program_studi' => $dosen1->program_studi,
            'fakultas' => $dosen1->fakultas,
            'peran_tim' => 'Ketua Pengusul',
            'tugas_tim' => 'Penyusunan usulan dan desain sistem.',
            'status_konfirmasi' => 'approved',
            'tgl_konfirmasi' => now(),
        ]);

        // Activity Logs
        ActivityLog::create([
            'user_id' => $dosen1->id,
            'proposal_id' => $prop1->id,
            'aksi' => 'Pengusulan Proposal',
            'keterangan' => 'Proposal diajukan setelah konfirmasi anggota tim selesai.',
        ]);
        ActivityLog::create([
            'user_id' => $adminLppm->id,
            'proposal_id' => $prop1->id,
            'aksi' => 'Verifikasi Administrasi',
            'keterangan' => 'Proposal dinyatakan lengkap dan lolos verifikasi administrasi.',
        ]);
        ActivityLog::create([
            'user_id' => $adminLppm->id,
            'proposal_id' => $prop1->id,
            'aksi' => 'Penetapan Pemenang',
            'keterangan' => 'Proposal ditetapkan didanai berdasarkan SK Rektor.',
        ]);
        ActivityLog::create([
            'user_id' => $keuangan->id,
            'proposal_id' => $prop1->id,
            'aksi' => 'Pencairan Dana Tahap I',
            'keterangan' => 'Pencairan termin 1 sebesar Rp 16.800.000 telah ditransfer.',
        ]);
    }
}
