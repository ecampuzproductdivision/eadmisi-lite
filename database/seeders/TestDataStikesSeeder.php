<?php

namespace Database\Seeders;

use App\Helpers\PeriodeHelper;
use App\Models\KategoriJalur;
use App\Models\Periode;
use App\Models\ProgramStudi;
use App\Models\Registration;
use App\Models\RegistrationPath;
use App\Models\TemplateBerkas;
use App\Models\User;
use App\Models\Wawancara;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * TestDataStikesSeeder - Creates interconnected test data for STIKES workflow.
 *
 * STEP 1: Creates "Jalur Reguler STIKES" admission path with wawancara enabled.
 * STEP 2: Creates 3 sample applicants tied to that path.
 * STEP 3: Creates wawancara interview scheduling records for each applicant.
 */
class TestDataStikesSeeder extends Seeder
{
    public function run(): void
    {
        // ──────────────────────────────────────────────
        // Determine active period
        // ──────────────────────────────────────────────
        $activePeriode = Periode::active()->first();
        if (!$activePeriode) {
            $this->command->error('No active period found! Skipping TestDataStikesSeeder.');
            return;
        }
        $this->command->info("Using active period: {$activePeriode->label}");

        // ──────────────────────────────────────────────
        // STEP 1: Create "Jalur Reguler STIKES"
        // ──────────────────────────────────────────────
        $kategori = KategoriJalur::first();
        $programStudi = ProgramStudi::first();

        // Check if already exists
        $existingPath = RegistrationPath::where('code', 'STIKES_REG')->first();
        if ($existingPath) {
            $this->command->info('Jalur Reguler STIKES already exists, reusing it.');
            $jalurStikes = $existingPath;
        } else {
            $jalurStikes = RegistrationPath::create([
                'periode_id'           => $activePeriode->id,
                'kategori_jalur_id'    => $kategori?->id ?? 1,
                'code'                 => 'STIKES_REG',
                'name'                 => 'Jalur Reguler STIKES',
                'description'          => 'Jalur reguler penerimaan mahasiswa baru untuk program studi kesehatan (STIKES) dengan tahapan ujian online, unggah berkas, dan wawancara.',
                'registration_start'   => '2026-06-01',
                'registration_end'     => '2026-08-31',
                'fee'                  => 350000,
                'color'                => 'primary',
                'quota'                => 100,
                'jumlah_pilihan_prodi' => 2,
                'is_active'            => true,
                'gunakan_ujian'        => true,
                'paket_soal_id'        => null,
                'gunakan_berkas'       => true,
                'template_berkas_id'   => null,
                'metode_pengumuman'    => 'ditahan',
                'gunakan_wawancara'    => true,
            ]);

            // Attach to program studi if available
            if ($programStudi) {
                $jalurStikes->programStudis()->sync([$programStudi->id]);
            }

            $this->command->info("STEP 1: Jalur Reguler STIKES created (ID: {$jalurStikes->id})");
        }

        // ──────────────────────────────────────────────
        // STEP 2: Create 3 applicants (pendaftar)
        // ──────────────────────────────────────────────
        $applicants = [
            [
                'nama_lengkap' => 'Budi Santoso',
                'nik'          => '3273011501000001',
                'no_hp'        => '081234567890',
                'email'        => 'budi.santoso@example.com',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir'=> '2000-01-15',
                'jenis_kelamin'=> 'L',
                'agama'        => 'Islam',
                'alamat'       => 'Jl. Merdeka No. 123, Jakarta Pusat',
                'kode_pos'     => '10110',
                'nama_sekolah' => 'SMA Negeri 1 Jakarta',
                'jurusan'      => 'IPA',
                'tahun_lulus'  => '2024',
            ],
            [
                'nama_lengkap' => 'Siti Aminah',
                'nik'          => '3273011501000002',
                'no_hp'        => '081234567891',
                'email'        => 'siti.aminah@example.com',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir'=> '2001-03-20',
                'jenis_kelamin'=> 'P',
                'agama'        => 'Islam',
                'alamat'       => 'Jl. Diponegoro No. 45, Bandung',
                'kode_pos'     => '40115',
                'nama_sekolah' => 'SMA Negeri 3 Bandung',
                'jurusan'      => 'IPA',
                'tahun_lulus'  => '2024',
            ],
            [
                'nama_lengkap' => 'Rendi Wijaya',
                'nik'          => '3273011501000003',
                'no_hp'        => '081234567892',
                'email'        => 'rendi.wijaya@example.com',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir'=> '2000-11-08',
                'jenis_kelamin'=> 'L',
                'agama'        => 'Islam',
                'alamat'       => 'Jl. Pemuda No. 78, Surabaya',
                'kode_pos'     => '60271',
                'nama_sekolah' => 'SMA Negeri 5 Surabaya',
                'jurusan'      => 'IPA',
                'tahun_lulus'  => '2023',
            ],
        ];

        // Get user for binding (use admin or first user)
        $user = User::where('email', 'admin@akademik.com')->first();
        if (!$user) {
            $user = User::first();
        }

        $createdRegistrations = [];

        foreach ($applicants as $index => $data) {
            // Check if registration already exists with this NIK
            $existingReg = Registration::where('nik', $data['nik'])->first();
            if ($existingReg) {
                $this->command->info("Skipping {$data['nama_lengkap']} (already registered as ID: {$existingReg->id})");
                $createdRegistrations[] = $existingReg;
                continue;
            }

            $reg = Registration::create([
                'user_id'              => $user->id,
                'registration_path_id' => $jalurStikes->id,
                'program_studi_1_id'   => $programStudi?->id,
                'program_studi_2_id'   => null,
                'status'               => 'submitted',
                'nama_lengkap'         => $data['nama_lengkap'],
                'nik'                  => $data['nik'],
                'no_hp'                => $data['no_hp'],
                'email'                => $data['email'],
                'tempat_lahir'         => $data['tempat_lahir'],
                'tanggal_lahir'        => $data['tanggal_lahir'],
                'jenis_kelamin'        => $data['jenis_kelamin'],
                'agama'                => $data['agama'],
                'alamat'               => $data['alamat'],
                'kode_pos'             => $data['kode_pos'],
                'nama_sekolah'         => $data['nama_sekolah'],
                'jurusan'              => $data['jurusan'],
                'tahun_lulus'          => $data['tahun_lulus'],
            ]);

            $createdRegistrations[] = $reg;
            $this->command->info("STEP 2: Applicant '{$data['nama_lengkap']}' created (ID: {$reg->id})");
        }

        $this->command->info('STEP 2 completed: ' . count($createdRegistrations) . ' applicants created.');

        // ──────────────────────────────────────────────
        // STEP 3: Create wawancara records
        // ──────────────────────────────────────────────
        $wawancaraData = [
            [
                'tanggal' => '2026-06-25',
                'jam'     => '09:00',
                'lokasi'  => 'Ruang Wawancara Klinis Kampus A',
            ],
            [
                'tanggal' => '2026-06-25',
                'jam'     => '10:30',
                'lokasi'  => 'Ruang Wawancara Klinis Kampus A',
            ],
            [
                'tanggal' => '2026-06-26',
                'jam'     => '09:00',
                'lokasi'  => 'Ruang Wawancara Klinis Kampus A',
            ],
        ];

        foreach ($createdRegistrations as $index => $reg) {
            $wd = $wawancaraData[$index] ?? $wawancaraData[0];

            // Check if wawancara already exists
            $existingW = Wawancara::where('pendaftaran_id', $reg->id)->first();
            if ($existingW) {
                $this->command->info("Skipping wawancara for registration #{$reg->id} (already exists)");
                continue;
            }

            $namaPewawancara = [
                'Dr. H. Ahmad, M.Kep.',
                'Ns. Indah Kirana, M.Kep.',
                'Dr. H. Ahmad, M.Kep.',
            ];

            Wawancara::create([
                'pendaftaran_id'     => $reg->id,
                'tanggal_wawancara'  => $wd['tanggal'],
                'jam_wawancara'      => $wd['jam'],
                'lokasi_wawancara'   => $wd['lokasi'],
                'nama_pewawancara'   => $namaPewawancara[$index] ?? 'Dr. H. Ahmad, M.Kep.',
                'status_wawancara'   => 'Belum Wawancara',
                'catatan_pewawancara'=> null,
            ]);

            $this->command->info("STEP 3: Wawancara for REG-{$reg->id} ({$reg->nama_lengkap}) scheduled: {$wd['tanggal']} {$wd['jam']}");
        }

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('TestDataStikesSeeder COMPLETED!');
        $this->command->info('========================================');
        $this->command->info("Jalur: {$jalurStikes->name}");
        $this->command->info('Applicants: ' . count($createdRegistrations));
        $this->command->info('Wawancara: ' . count($createdRegistrations) . ' records');
        $this->command->info('');
        $this->command->info('Visit /settings/wawancara to see the data.');
    }
}