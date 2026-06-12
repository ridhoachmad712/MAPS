<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

/**
 * @property-read Schema $form
 */
class PengaturanTampilan extends Page
{
    protected string $view = 'filament.pages.pengaturan-tampilan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPaintBrush;

    protected static ?string $navigationLabel = 'Pengaturan Tampilan';

    protected static ?string $title = 'Pengaturan Tampilan';

    protected static ?int $navigationSort = 5;

    /** Kunci pengaturan bertipe sakelar (disimpan '1'/'0'). */
    private const SAKELAR = ['sorotan_tampil', 'galeri_tampil', 'grafik_tampil', 'cta_tampil'];

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $nilai = Setting::semua();

        foreach (self::SAKELAR as $kunci) {
            $nilai[$kunci] = $nilai[$kunci] === '1';
        }

        $this->form->fill($nilai);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas')
                    ->description('Nama dan logo yang tampil di navbar, footer, login, dan tab browser.')
                    ->columns(2)
                    ->components([
                        TextInput::make('nama_aplikasi')->label('Nama Aplikasi')->required()->maxLength(50),
                        TextInput::make('nama_pemilik')->label('Nama Prodi/Pemilik')->required()->maxLength(100)
                            ->helperText('Baris kedua pada brand navbar.'),
                        TextInput::make('tagline')->label('Tagline')->maxLength(150)->columnSpanFull(),
                        FileUpload::make('logo')->label('Logo')
                            ->disk('public')->directory('pengaturan')->image()->maxSize(2048)
                            ->helperText('PNG/JPG/SVG maks 2 MB. Kosongkan untuk memakai logo bawaan berwarna tema.')
                            ->columnSpanFull(),
                    ]),

                Section::make('Warna')
                    ->description('Berlaku langsung tanpa build ulang. Warna teks menyesuaikan kontras secara otomatis.')
                    ->columns(4)
                    ->components([
                        ColorPicker::make('warna_primer')->label('Primer (tombol & aksen)')->required(),
                        ColorPicker::make('warna_hero')->label('Hero (latar gradasi)')->required(),
                        ColorPicker::make('warna_navbar')->label('Navbar')->required(),
                        ColorPicker::make('warna_footer')->label('Footer')->required(),
                    ]),

                Section::make('Hero Beranda')
                    ->columns(2)
                    ->components([
                        TextInput::make('hero_eyebrow')->label('Teks Kecil di Atas Judul')->maxLength(150),
                        TextInput::make('hero_judul')->label('Judul Utama')->required()->maxLength(100),
                        Textarea::make('hero_deskripsi')->label('Deskripsi')->rows(2)->maxLength(300)->columnSpanFull(),
                        TextInput::make('hero_placeholder')->label('Placeholder Kotak Pencarian')->maxLength(150),
                        TextInput::make('hero_overlay')->label('Ketebalan Overlay Foto (%)')
                            ->numeric()->minValue(0)->maxValue(95)
                            ->helperText('Hanya berlaku bila foto latar diunggah.'),
                        FileUpload::make('hero_foto')->label('Foto Latar Hero')
                            ->disk('public')->directory('pengaturan')->image()->maxSize(4096)
                            ->helperText('Kosongkan untuk memakai gradasi warna hero.')
                            ->columnSpanFull(),
                    ]),

                Section::make('Seksi Beranda')
                    ->description('Atur tampil/sembunyi serta judul tiap seksi.')
                    ->columns(2)
                    ->components([
                        Toggle::make('sorotan_tampil')->label('Tampilkan Sorotan Capaian'),
                        Toggle::make('galeri_tampil')->label('Tampilkan Mahasiswa Berprestasi'),
                        TextInput::make('sorotan_judul')->label('Judul Sorotan')->maxLength(100),
                        TextInput::make('galeri_judul')->label('Judul Galeri')->maxLength(100),
                        TextInput::make('sorotan_sub')->label('Subjudul Sorotan')->maxLength(200),
                        TextInput::make('galeri_sub')->label('Subjudul Galeri')->maxLength(200),
                        Toggle::make('grafik_tampil')->label('Tampilkan Sekilas Statistik'),
                        Toggle::make('cta_tampil')->label('Tampilkan Kartu Ajakan'),
                        TextInput::make('grafik_judul')->label('Judul Statistik')->maxLength(100),
                        TextInput::make('cta_judul')->label('Judul Kartu Ajakan')->maxLength(100),
                        TextInput::make('grafik_sub')->label('Subjudul Statistik')->maxLength(200),
                        TextInput::make('cta_deskripsi')->label('Deskripsi Kartu Ajakan')->maxLength(200),
                        TextInput::make('cta_tombol')->label('Teks Tombol Ajakan')->maxLength(50),
                    ]),

                Section::make('Footer')
                    ->columns(2)
                    ->components([
                        Textarea::make('footer_deskripsi')->label('Deskripsi')->rows(2)->maxLength(300)->columnSpanFull(),
                        TextInput::make('footer_kontak1')->label('Kontak Baris 1')->maxLength(200),
                        TextInput::make('footer_kontak2')->label('Kontak Baris 2')->maxLength(200),
                        TextInput::make('footer_link_label')->label('Label Tautan Eksternal')->maxLength(100),
                        TextInput::make('footer_link_url')->label('URL Tautan Eksternal')->url()->maxLength(255),
                    ]),

                Section::make('Halaman Tentang')
                    ->components([
                        Textarea::make('tentang_p1')->label('Paragraf 1')->rows(4)->maxLength(2000),
                        Textarea::make('tentang_p2')->label('Paragraf 2')->rows(3)->maxLength(2000),
                        TextInput::make('tentang_kontak1')->label('Kontak Baris 1')->maxLength(300),
                        TextInput::make('tentang_kontak2')->label('Kontak Baris 2')->maxLength(300),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $nilai = $this->form->getState();

        foreach (Setting::BAWAAN as $kunci => $bawaan) {
            if (! array_key_exists($kunci, $nilai)) {
                continue;
            }

            $baru = $nilai[$kunci];

            if (in_array($kunci, self::SAKELAR)) {
                $baru = $baru ? '1' : '0';
            }

            Setting::set($kunci, $baru === null ? '' : (string) $baru);
        }

        Notification::make()
            ->success()
            ->title('Pengaturan tersimpan')
            ->body('Perubahan langsung berlaku di halaman depan.')
            ->send();
    }
}
