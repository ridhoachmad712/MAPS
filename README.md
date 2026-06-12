# MAPS — Management Student Achievement Portfolio System

Sistem informasi arsip dan showcase portofolio capaian mahasiswa
**Program Studi Manajemen, Fakultas Ekonomi dan Bisnis, Universitas Negeri Makassar**.

Dibangun dengan **Laravel 13 + MySQL + Blade + TailwindCSS 4 + Alpine.js + Chart.js** (aset dikompilasi dengan Vite).

## Fitur

- **Autentikasi & otorisasi 3 peran**: admin prodi, verifikator (dosen), mahasiswa. Kata sandi di-hash (bcrypt), rate limiting login, middleware peran.
- **Modul portofolio mahasiswa**: CRUD entri 7 kategori (PRES, PKM, ORG, MBKM, SERT, PUB, DOK), unggah multi-bukti (PDF/JPG/PNG maks 5 MB), pengajuan verifikasi.
- **Alur verifikasi**: Draft → Diajukan → (Diverifikasi / Perlu Revisi / Ditolak) → Dipublikasikan. Catatan wajib saat menolak/meminta revisi; riwayat verifikasi tersimpan.
- **Dashboard statistik** (semua dihitung COUNT/GROUP BY, tidak ada angka manual): total mahasiswa berprestasi, total per kategori, distribusi per angkatan & level, tren per tahun, top 10 mahasiswa.
- **Showcase publik** (tanpa login): filter tahun/level/kategori + pencarian, profil publik mahasiswa dengan NIM disamarkan (`2209***001`).
- **Privasi**: entri tampil publik hanya jika **terverifikasi** DAN **is_publik** (persetujuan per entri) DAN **konsen_publik** (persetujuan profil mahasiswa). Berkas bukti tersimpan di disk privat; akses dikontrol per permintaan.

## Menjalankan

Prasyarat: PHP ≥ 8.2 (ekstensi pdo_mysql, gd, fileinfo), Composer, MySQL, Node.js ≥ 20.

```bash
composer install
npm install
npm run build                 # kompilasi TailwindCSS + Alpine via Vite
copy .env.example .env        # lalu atur DB_DATABASE=maps_db dst.
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
```

Saat pengembangan front-end, jalankan `npm run dev` (Vite dev server dengan hot-reload) berdampingan dengan `php artisan serve`.

Buka <http://127.0.0.1:8000>.

## Akun Demo (hasil seeder)

| Peran | Username | Kata sandi |
|---|---|---|
| Admin Prodi | `admin` | `password` |
| Verifikator (dosen) | `dosen.verifikator` | `password` |
| Mahasiswa (contoh) | `220903500001` (Siti Nurhaliza) | `password` |

Seluruh 8 akun mahasiswa demo memakai NIM sebagai username dengan kata sandi `password`.

## Panel Data Master & Laporan (Filament)

Admin prodi memiliki panel tambahan di **`/master`** (Filament v5) untuk:

- **Mahasiswa** — CRUD akun (pembuatan user otomatis: username = NIM) + **impor massal CSV** (kolom: nim, nama_lengkap, angkatan; opsional: program_studi, email — kata sandi awal = NIM)
- **Kategori** dan **Akun Petugas** (admin/verifikator)
- **Laporan Portofolio** — tabel baca-saja dengan filter status/level/kategori/tahun/angkatan + **ekspor Excel/CSV** untuk akreditasi
- **Pengaturan Tampilan** — ubah identitas (nama, logo), empat warna tema (primer/hero/navbar/footer dengan kontras teks otomatis), teks & foto latar hero, tampil/sembunyi + judul seksi beranda, isi footer, dan konten halaman Tentang — semuanya berlaku langsung tanpa build ulang (tabel `pengaturan` + model `Setting` + injeksi CSS variable di `partials/tema.blade.php`)
- Dashboard widget statistik

Alur verifikasi (antrian → pemeriksaan bukti → keputusan → publikasi) tetap di halaman custom `/admin/*`. Panel `/master` hanya bisa diakses peran admin (lihat `User::canAccessPanel()`).

> Catatan teknis: karena PK `users` bernama `user_id` (bukan `id`), migrasi tabel `imports`/`exports` terbitan Filament disesuaikan — kolom FK-nya `user_user_id` mengikuti konvensi penamaan relasi Eloquent.

## Struktur Basis Data

`users` 1:1 `mahasiswa` · `mahasiswa` 1:N `portofolio` · `kategori` 1:N `portofolio` ·
`portofolio` 1:N `bukti` · `portofolio` 1:N `verifikasi` · `users` 1:N `verifikasi` (sebagai verifikator).

Migrasi ada di `database/migrations/`, seeder data contoh di `database/seeders/DatabaseSeeder.php`.

## Catatan Pengembangan

- Berkas bukti: `storage/app/private/bukti/{portofolio_id}/` — diakses lewat route `/bukti/{id}` dengan otorisasi.
- Foto profil: `storage/app/public/foto/` — publik via symlink `public/storage`.
- Styling: TailwindCSS 4 — design system (warna `navy`, komponen `.btn-*`, `.card`, `.badge-*`, `.form-*`, `.table-maps`) didefinisikan di `resources/css/app.css`.
- Interaktivitas (menu mobile, dropdown, modal, tutup notifikasi): Alpine.js, diimpor di `resources/js/app.js`.
- Font Plus Jakarta Sans di-self-host otomatis oleh plugin Vite Laravel; Bootstrap Icons & Chart.js dimuat via CDN.
- Untuk deploy: jalankan `npm run build` lalu unggah folder `public/build/` bersama kode (server produksi tidak butuh Node).
