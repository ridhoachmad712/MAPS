<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Mahasiswa;
use App\Models\Portofolio;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    private User $verifikator;

    private User $admin;

    public function run(): void
    {
        $this->seedKategori();
        $this->seedPetugas();
        $this->seedMahasiswaDanPortofolio();
    }

    private function seedKategori(): void
    {
        $daftar = [
            ['kode' => 'PRES', 'nama_kategori' => 'Prestasi/Kompetisi', 'deskripsi' => 'Kejuaraan dan kompetisi akademik maupun non-akademik.'],
            ['kode' => 'PKM', 'nama_kategori' => 'Program Kreativitas Mahasiswa', 'deskripsi' => 'Keterlibatan dalam skema PKM (PKM-K, PKM-RSH, dll.).'],
            ['kode' => 'ORG', 'nama_kategori' => 'Organisasi', 'deskripsi' => 'Kepengurusan organisasi kemahasiswaan internal/eksternal kampus.'],
            ['kode' => 'MBKM', 'nama_kategori' => 'MBKM', 'deskripsi' => 'Program Merdeka Belajar Kampus Merdeka: magang, studi independen, pertukaran mahasiswa, dll.'],
            ['kode' => 'SERT', 'nama_kategori' => 'Sertifikasi', 'deskripsi' => 'Sertifikasi kompetensi dan pelatihan bersertifikat.'],
            ['kode' => 'PUB', 'nama_kategori' => 'Publikasi/Karya Ilmiah', 'deskripsi' => 'Artikel jurnal, prosiding, dan karya ilmiah lainnya.'],
            ['kode' => 'DOK', 'nama_kategori' => 'Dokumentasi Kegiatan Lain', 'deskripsi' => 'Kegiatan kemahasiswaan lain yang layak diarsipkan.'],
        ];

        foreach ($daftar as $kategori) {
            Kategori::firstOrCreate(['kode' => $kategori['kode']], $kategori);
        }
    }

    private function seedPetugas(): void
    {
        $this->admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'email' => 'admin.manajemen@unm.ac.id',
                'password_hash' => 'password',
                'role' => 'admin',
                'is_active' => true,
            ],
        );

        $this->verifikator = User::firstOrCreate(
            ['username' => 'dosen.verifikator'],
            [
                'email' => 'verifikator.manajemen@unm.ac.id',
                'password_hash' => 'password',
                'role' => 'verifikator',
                'is_active' => true,
            ],
        );
    }

    private function seedMahasiswaDanPortofolio(): void
    {
        $kategori = Kategori::pluck('kategori_id', 'kode');

        $dataMahasiswa = [
            ['nim' => '220903500001', 'nama' => 'Siti Nurhaliza', 'angkatan' => 2022, 'konsen' => true],
            ['nim' => '220903500014', 'nama' => 'Muhammad Fadel Rahman', 'angkatan' => 2022, 'konsen' => true],
            ['nim' => '230903500021', 'nama' => 'Andi Tenri Abeng', 'angkatan' => 2023, 'konsen' => true],
            ['nim' => '230903500035', 'nama' => 'Nurul Izzah Mappasessu', 'angkatan' => 2023, 'konsen' => true],
            ['nim' => '240903500008', 'nama' => 'Rifqi Aditya Pratama', 'angkatan' => 2024, 'konsen' => true],
            ['nim' => '240903500019', 'nama' => 'Dian Ayu Lestari', 'angkatan' => 2024, 'konsen' => false],
            ['nim' => '250903500003', 'nama' => 'Ahmad Fauzan Hidayat', 'angkatan' => 2025, 'konsen' => true],
            ['nim' => '250903500027', 'nama' => 'Besse Mutmainnah', 'angkatan' => 2025, 'konsen' => true],
        ];

        $mahasiswa = [];

        foreach ($dataMahasiswa as $m) {
            $user = User::firstOrCreate(
                ['username' => $m['nim']],
                [
                    'email' => $m['nim'].'@student.unm.ac.id',
                    'password_hash' => 'password',
                    'role' => 'mahasiswa',
                    'is_active' => true,
                ],
            );

            $mahasiswa[$m['nim']] = Mahasiswa::firstOrCreate(
                ['nim' => $m['nim']],
                [
                    'user_id' => $user->user_id,
                    'nama_lengkap' => $m['nama'],
                    'angkatan' => $m['angkatan'],
                    'program_studi' => 'Manajemen',
                    'konsen_publik' => $m['konsen'],
                ],
            );
        }

        // [nim, kode kategori, judul, tahun, level, penyelenggara, peran, status, is_publik, catatan verifikasi]
        $entri = [
            ['220903500001', 'PRES', 'Juara 1 Public Speaking Competition', 2025, 'nasional', 'Universitas Indonesia', 'Peserta (Juara 1)', 'dipublikasikan', true, null],
            ['220903500001', 'PRES', 'Juara 2 National Business Plan Competition', 2024, 'nasional', 'Universitas Brawijaya', 'Ketua Tim (Juara 2)', 'dipublikasikan', true, null],
            ['220903500001', 'SERT', 'Sertifikasi Digital Marketing BNSP', 2024, 'nasional', 'BNSP', 'Peserta Tersertifikasi', 'diverifikasi', true, null],
            ['220903500001', 'ORG', 'Ketua Himpunan Mahasiswa Manajemen', 2024, 'regional', 'HIMA Manajemen FEB UNM', 'Ketua Umum', 'diverifikasi', true, null],
            ['220903500001', 'PUB', 'Artikel: Strategi Pemasaran UMKM Digital di Makassar', 2025, 'nasional', 'Jurnal Manajemen dan Kewirausahaan', 'Penulis Pertama', 'diverifikasi', true, null],
            ['220903500014', 'PKM', 'PKM-K Lolos Pendanaan: Abon Ikan Terbang Khas Sulawesi', 2024, 'nasional', 'Kemdikbudristek', 'Ketua Tim', 'dipublikasikan', true, null],
            ['220903500014', 'MBKM', 'Magang Bersertifikat di Bank Indonesia KPw Sulsel', 2024, 'nasional', 'Bank Indonesia', 'Peserta Magang', 'diverifikasi', true, null],
            ['220903500014', 'PRES', 'Finalis ASEAN Young Entrepreneurs Summit', 2025, 'internasional', 'ASEAN Foundation', 'Finalis', 'dipublikasikan', true, null],
            ['220903500014', 'SERT', 'TOEFL ITP Skor 590', 2023, 'internasional', 'ETS', 'Peserta Tes', 'diverifikasi', true, null],
            ['230903500021', 'PRES', 'Juara 3 Lomba Karya Tulis Ilmiah Ekonomi', 2024, 'regional', 'Universitas Hasanuddin', 'Anggota Tim (Juara 3)', 'diverifikasi', true, null],
            ['230903500021', 'ORG', 'Sekretaris BEM FEB UNM', 2025, 'regional', 'BEM FEB UNM', 'Sekretaris Umum', 'diverifikasi', true, null],
            ['230903500021', 'MBKM', 'Pertukaran Mahasiswa Merdeka ke UGM', 2025, 'nasional', 'Kemdikbudristek', 'Peserta', 'diajukan', false, null],
            ['230903500021', 'DOK', 'Panitia Seminar Nasional Kewirausahaan 2024', 2024, 'nasional', 'FEB UNM', 'Koordinator Acara', 'diverifikasi', false, null],
            ['230903500035', 'SERT', 'Sertifikasi Analis Keuangan Junior', 2025, 'nasional', 'LSP Keuangan', 'Peserta Tersertifikasi', 'diverifikasi', true, null],
            ['230903500035', 'PUB', 'Prosiding: Literasi Keuangan Generasi Z', 2024, 'internasional', 'International Conference on Economics', 'Penulis Kedua', 'dipublikasikan', true, null],
            ['230903500035', 'PRES', 'Juara Harapan 1 Stock Trading Competition', 2025, 'nasional', 'Bursa Efek Indonesia', 'Peserta', 'diajukan', true, null],
            ['230903500035', 'PKM', 'PKM-RSH: Perilaku Konsumen Pasar Tradisional', 2025, 'nasional', 'Kemdikbudristek', 'Anggota Tim', 'revisi', false, 'Lampirkan surat keterangan lolos pendanaan dari Kemdikbudristek, bukan hanya proposal.'],
            ['240903500008', 'PRES', 'Juara 1 Debat Ekonomi Se-Sulawesi', 2025, 'regional', 'Universitas Negeri Makassar', 'Pembicara Pertama (Juara 1)', 'dipublikasikan', true, null],
            ['240903500008', 'ORG', 'Anggota Divisi Kewirausahaan HIMA Manajemen', 2025, 'regional', 'HIMA Manajemen FEB UNM', 'Anggota Divisi', 'diverifikasi', true, null],
            ['240903500008', 'SERT', 'Pelatihan Microsoft Excel Advanced', 2024, 'regional', 'Pusat Komputer UNM', 'Peserta', 'ditolak', false, 'Sertifikat tidak terbaca. Unggah ulang hasil pindaian yang jelas.'],
            ['240903500008', 'DOK', 'Relawan Pengabdian Masyarakat Desa Binaan', 2025, 'regional', 'LP2M UNM', 'Relawan', 'draft', false, null],
            ['240903500019', 'PRES', 'Juara 2 Esai Nasional Hari Koperasi', 2025, 'nasional', 'Kementerian Koperasi dan UKM', 'Peserta (Juara 2)', 'diverifikasi', true, null],
            ['240903500019', 'SERT', 'Sertifikasi Junior Office Operator', 2024, 'nasional', 'BNSP', 'Peserta Tersertifikasi', 'diverifikasi', true, null],
            ['250903500003', 'PRES', 'Medali Perak Olimpiade Ekonomi Mahasiswa', 2025, 'nasional', 'FEB Universitas Airlangga', 'Peserta (Medali Perak)', 'diajukan', true, null],
            ['250903500003', 'ORG', 'Anggota UKM Kewirausahaan UNM', 2025, 'regional', 'UKM Kewirausahaan UNM', 'Anggota', 'draft', false, null],
            ['250903500027', 'SERT', 'Pelatihan Kepemimpinan Tingkat Dasar', 2025, 'regional', 'FEB UNM', 'Peserta Terbaik', 'diverifikasi', true, null],
            ['250903500027', 'DOK', 'Delegasi Kampus pada Festival Ekonomi Kreatif', 2025, 'regional', 'Pemprov Sulawesi Selatan', 'Delegasi', 'diajukan', true, null],
            ['250903500027', 'PUB', 'Esai Populer: UMKM Naik Kelas Lewat Marketplace', 2025, 'regional', 'Media Kampus UNM', 'Penulis', 'draft', false, null],
        ];

        foreach ($entri as [$nim, $kode, $judul, $tahun, $level, $penyelenggara, $peran, $status, $isPublik, $catatan]) {
            $portofolio = Portofolio::firstOrCreate(
                [
                    'mahasiswa_id' => $mahasiswa[$nim]->mahasiswa_id,
                    'judul' => $judul,
                ],
                [
                    'kategori_id' => $kategori[$kode],
                    'deskripsi' => 'Capaian "'.$judul.'" yang diraih pada tahun '.$tahun.' tingkat '.$level.'.',
                    'tahun_pencapaian' => $tahun,
                    'level' => $level,
                    'penyelenggara' => $penyelenggara,
                    'peran_capaian' => $peran,
                    'status' => $status,
                    'is_publik' => $isPublik,
                ],
            );

            if (! $portofolio->wasRecentlyCreated) {
                continue;
            }

            // Setiap entri non-draft punya minimal satu berkas bukti dummy
            if ($status !== 'draft') {
                $this->buatBuktiDummy($portofolio);
            }

            // Riwayat verifikasi untuk entri yang sudah diproses petugas
            if (in_array($status, ['diverifikasi', 'dipublikasikan', 'ditolak', 'revisi'])) {
                $hasil = in_array($status, ['diverifikasi', 'dipublikasikan']) ? 'diverifikasi' : $status;

                $portofolio->verifikasi()->create([
                    'verifikator_id' => $this->verifikator->user_id,
                    'hasil' => $hasil,
                    'catatan' => $catatan ?? ($hasil === 'diverifikasi' ? 'Bukti lengkap dan valid.' : null),
                    'tanggal_verifikasi' => now()->subDays(rand(1, 60)),
                ]);
            }
        }
    }

    private function buatBuktiDummy(Portofolio $portofolio): void
    {
        $isiPdf = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n"
            ."2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n"
            ."3 0 obj<</Type/Page/Parent 2 0 R/MediaBox[0 0 612 792]/Contents 4 0 R/Resources<</Font<</F1 5 0 R>>>>>>endobj\n"
            ."4 0 obj<</Length 80>>stream\nBT /F1 14 Tf 72 720 Td (Bukti dummy MAPS - Portofolio #{$portofolio->portofolio_id}) Tj ET\nendstream endobj\n"
            ."5 0 obj<</Type/Font/Subtype/Type1/BaseFont/Helvetica>>endobj\n"
            ."trailer<</Root 1 0 R>>\n%%EOF";

        $path = 'bukti/'.$portofolio->portofolio_id.'/bukti-demo.pdf';
        Storage::disk('local')->put($path, $isiPdf);

        $portofolio->bukti()->create([
            'nama_file' => 'bukti-demo.pdf',
            'path_file' => $path,
            'tipe_file' => 'application/pdf',
            'uploaded_at' => now()->subDays(rand(60, 90)),
        ]);
    }
}
