<?php

namespace Database\Seeders;

use App\Models\Form;
use App\Models\FormField;
use Illuminate\Database\Seeder;

class FormPendaftaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $forms = [
            [
                'nama' => 'Form Pendaftaran Reguler',
                'deskripsi' => 'Formulir standar untuk pendaftaran jalur reguler. Mencakup data pribadi, kontak, dan pendidikan terakhir.',
                'fields' => [
                    // Data Pribadi
                    ['field_type' => 'text', 'field_name' => 'nama_lengkap', 'field_label' => 'Nama Lengkap', 'placeholder' => 'Masukkan nama lengkap', 'help_text' => 'Nama lengkap sesuai identitas resmi', 'section' => 'Data Pribadi', 'width' => 'col-md-6', 'is_required' => true, 'sort_order' => 1],
                    ['field_type' => 'text', 'field_name' => 'tempat_lahir', 'field_label' => 'Tempat Lahir', 'placeholder' => 'Contoh: Jakarta', 'section' => 'Data Pribadi', 'width' => 'col-md-6', 'is_required' => true, 'sort_order' => 2],
                    ['field_type' => 'date', 'field_name' => 'tanggal_lahir', 'field_label' => 'Tanggal Lahir', 'section' => 'Data Pribadi', 'width' => 'col-md-6', 'is_required' => true, 'sort_order' => 3],
                    ['field_type' => 'select', 'field_name' => 'jenis_kelamin', 'field_label' => 'Jenis Kelamin', 'options' => ['Laki-laki', 'Perempuan'], 'section' => 'Data Pribadi', 'width' => 'col-md-6', 'is_required' => true, 'sort_order' => 4],
                    ['field_type' => 'select', 'field_name' => 'agama', 'field_label' => 'Agama', 'options' => ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'], 'section' => 'Data Pribadi', 'width' => 'col-md-6', 'is_required' => true, 'sort_order' => 5],
                    ['field_type' => 'text', 'field_name' => 'nik', 'field_label' => 'NIK', 'placeholder' => '16 digit NIK', 'help_text' => 'Nomor Induk Kependudukan sesuai KTP', 'section' => 'Data Pribadi', 'width' => 'col-md-6', 'is_required' => true, 'sort_order' => 6],
                    // Kontak & Alamat
                    ['field_type' => 'tel', 'field_name' => 'no_hp', 'field_label' => 'Nomor WhatsApp Aktif', 'placeholder' => '08xxxxxxxxxx', 'help_text' => 'Nomor WhatsApp yang aktif dan dapat dihubungi', 'section' => 'Kontak & Alamat', 'width' => 'col-md-6', 'is_required' => true, 'sort_order' => 7],
                    ['field_type' => 'email', 'field_name' => 'email', 'field_label' => 'Alamat Email', 'placeholder' => 'contoh@email.com', 'help_text' => 'Alamat email aktif untuk komunikasi pendaftaran', 'section' => 'Kontak & Alamat', 'width' => 'col-md-6', 'is_required' => true, 'sort_order' => 8],
                    ['field_type' => 'textarea', 'field_name' => 'alamat', 'field_label' => 'Alamat Lengkap', 'placeholder' => 'Masukkan alamat lengkap', 'section' => 'Kontak & Alamat', 'width' => 'col-12', 'is_required' => true, 'sort_order' => 9],
                    ['field_type' => 'text', 'field_name' => 'kode_pos', 'field_label' => 'Kode Pos', 'placeholder' => '5 digit kode pos', 'section' => 'Kontak & Alamat', 'width' => 'col-md-4', 'is_required' => false, 'sort_order' => 10],
                    // Pendidikan Terakhir
                    ['field_type' => 'text', 'field_name' => 'nama_sekolah', 'field_label' => 'Nama Sekolah Asal', 'placeholder' => 'Masukkan nama SMA/SMK/MA', 'section' => 'Pendidikan Terakhir', 'width' => 'col-md-6', 'is_required' => true, 'sort_order' => 11],
                    ['field_type' => 'select', 'field_name' => 'jurusan', 'field_label' => 'Jurusan', 'options' => ['IPA', 'IPS', 'Bahasa', 'Teknik', 'Akuntansi', 'Lainnya'], 'section' => 'Pendidikan Terakhir', 'width' => 'col-md-6', 'is_required' => true, 'sort_order' => 12],
                    ['field_type' => 'text', 'field_name' => 'tahun_lulus', 'field_label' => 'Tahun Lulus', 'placeholder' => 'Contoh: 2024', 'section' => 'Pendidikan Terakhir', 'width' => 'col-md-4', 'is_required' => true, 'sort_order' => 13],
                ],
            ],
            [
                'nama' => 'Form Pendaftaran Beasiswa',
                'deskripsi' => 'Formulir khusus untuk pendaftaran jalur beasiswa, mencakup data akademik dan informasi beasiswa.',
                'fields' => [
                    ['field_type' => 'text', 'field_name' => 'nama_lengkap', 'field_label' => 'Nama Lengkap', 'placeholder' => 'Masukkan nama lengkap', 'section' => 'Data Pribadi', 'width' => 'col-md-6', 'is_required' => true, 'sort_order' => 1],
                    ['field_type' => 'email', 'field_name' => 'email', 'field_label' => 'Alamat Email', 'placeholder' => 'contoh@email.com', 'section' => 'Data Pribadi', 'width' => 'col-md-6', 'is_required' => true, 'sort_order' => 2],
                    ['field_type' => 'tel', 'field_name' => 'no_hp', 'field_label' => 'Nomor WhatsApp', 'placeholder' => '08xxxxxxxxxx', 'section' => 'Data Pribadi', 'width' => 'col-md-6', 'is_required' => true, 'sort_order' => 3],
                    ['field_type' => 'date', 'field_name' => 'tanggal_lahir', 'field_label' => 'Tanggal Lahir', 'section' => 'Data Pribadi', 'width' => 'col-md-6', 'is_required' => true, 'sort_order' => 4],
                    ['field_type' => 'textarea', 'field_name' => 'prestasi_akademik', 'field_label' => 'Prestasi Akademik', 'placeholder' => 'Sebutkan prestasi akademik yang pernah diraih', 'help_text' => 'Juara kelas, olimpiade, lomba, dll.', 'section' => 'Informasi Beasiswa', 'width' => 'col-12', 'is_required' => true, 'sort_order' => 5],
                    ['field_type' => 'textarea', 'field_name' => 'prestasi_nonakademik', 'field_label' => 'Prestasi Non-Akademik', 'placeholder' => 'Sebutkan prestasi non-akademik (olahraga, seni, dll)', 'section' => 'Informasi Beasiswa', 'width' => 'col-12', 'is_required' => false, 'sort_order' => 6],
                    ['field_type' => 'number', 'field_name' => 'rata_rata_nilai', 'field_label' => 'Rata-rata Nilai Rapor', 'placeholder' => 'Contoh: 85.5', 'help_text' => 'Rata-rata nilai rapor semester 1-5', 'section' => 'Informasi Beasiswa', 'width' => 'col-md-4', 'is_required' => true, 'sort_order' => 7],
                    ['field_type' => 'select', 'field_name' => 'jenis_beasiswa', 'field_label' => 'Jenis Beasiswa yang Diinginkan', 'options' => ['Beasiswa Prestasi Akademik', 'Beasiswa Kurang Mampu (KIP)', 'Beasiswa Atlet', 'Beasiswa Hafidz Quran', 'Beasiswa Kerjasama Perusahaan'], 'section' => 'Informasi Beasiswa', 'width' => 'col-md-8', 'is_required' => true, 'sort_order' => 8],
                    ['field_type' => 'textarea', 'field_name' => 'alasan_beasiswa', 'field_label' => 'Alasan Mengajukan Beasiswa', 'placeholder' => 'Jelaskan alasan Anda mengajukan beasiswa', 'section' => 'Informasi Beasiswa', 'width' => 'col-12', 'is_required' => true, 'sort_order' => 9],
                ],
            ],
            [
                'nama' => 'Form Pendaftaran Pindahan / Transfer',
                'deskripsi' => 'Formulir untuk calon mahasiswa pindahan dari perguruan tinggi lain.',
                'fields' => [
                    ['field_type' => 'text', 'field_name' => 'nama_lengkap', 'field_label' => 'Nama Lengkap', 'placeholder' => 'Masukkan nama lengkap', 'section' => 'Data Pribadi', 'width' => 'col-md-6', 'is_required' => true, 'sort_order' => 1],
                    ['field_type' => 'email', 'field_name' => 'email', 'field_label' => 'Alamat Email', 'placeholder' => 'contoh@email.com', 'section' => 'Data Pribadi', 'width' => 'col-md-6', 'is_required' => true, 'sort_order' => 2],
                    ['field_type' => 'tel', 'field_name' => 'no_hp', 'field_label' => 'Nomor WhatsApp', 'placeholder' => '08xxxxxxxxxx', 'section' => 'Data Pribadi', 'width' => 'col-md-6', 'is_required' => true, 'sort_order' => 3],
                    // Data Akademik Sebelumnya
                    ['field_type' => 'text', 'field_name' => 'nama_pt_asal', 'field_label' => 'Nama Perguruan Tinggi Asal', 'placeholder' => 'Masukkan nama PT asal', 'section' => 'Data Akademik Sebelumnya', 'width' => 'col-md-6', 'is_required' => true, 'sort_order' => 4],
                    ['field_type' => 'text', 'field_name' => 'prodi_asal', 'field_label' => 'Program Studi Asal', 'placeholder' => 'Masukkan nama prodi asal', 'section' => 'Data Akademik Sebelumnya', 'width' => 'col-md-6', 'is_required' => true, 'sort_order' => 5],
                    ['field_type' => 'text', 'field_name' => 'nim_asal', 'field_label' => 'NIM di PT Asal', 'placeholder' => 'Masukkan NIM', 'section' => 'Data Akademik Sebelumnya', 'width' => 'col-md-4', 'is_required' => true, 'sort_order' => 6],
                    ['field_type' => 'number', 'field_name' => 'ipk', 'field_label' => 'IPK Terakhir', 'placeholder' => 'Contoh: 3.50', 'help_text' => 'Indeks Prestasi Kumulatif terakhir', 'section' => 'Data Akademik Sebelumnya', 'width' => 'col-md-4', 'is_required' => true, 'sort_order' => 7],
                    ['field_type' => 'number', 'field_name' => 'jumlah_sks', 'field_label' => 'Jumlah SKS yang Telah Ditempuh', 'placeholder' => 'Contoh: 80', 'section' => 'Data Akademik Sebelumnya', 'width' => 'col-md-4', 'is_required' => true, 'sort_order' => 8],
                    ['field_type' => 'textarea', 'field_name' => 'alasan_pindah', 'field_label' => 'Alasan Pindah', 'placeholder' => 'Jelaskan alasan Anda pindah ke kampus ini', 'section' => 'Data Akademik Sebelumnya', 'width' => 'col-12', 'is_required' => true, 'sort_order' => 9],
                    ['field_type' => 'select', 'field_name' => 'status_akademik', 'field_label' => 'Status Akademik Terakhir', 'options' => ['Aktif', 'Cuti Akademik', 'Drop Out', 'Lulus'], 'section' => 'Data Akademik Sebelumnya', 'width' => 'col-md-6', 'is_required' => true, 'sort_order' => 10],
                ],
            ],
            [
                'nama' => 'Form Pendaftaran Program Vokasi (D3/D4)',
                'deskripsi' => 'Formulir untuk pendaftaran program vokasi, fokus pada keterampilan praktis dan pengalaman kerja.',
                'fields' => [
                    ['field_type' => 'text', 'field_name' => 'nama_lengkap', 'field_label' => 'Nama Lengkap', 'placeholder' => 'Masukkan nama lengkap', 'section' => 'Data Pribadi', 'width' => 'col-md-6', 'is_required' => true, 'sort_order' => 1],
                    ['field_type' => 'email', 'field_name' => 'email', 'field_label' => 'Alamat Email', 'placeholder' => 'contoh@email.com', 'section' => 'Data Pribadi', 'width' => 'col-md-6', 'is_required' => true, 'sort_order' => 2],
                    ['field_type' => 'tel', 'field_name' => 'no_hp', 'field_label' => 'Nomor WhatsApp', 'placeholder' => '08xxxxxxxxxx', 'section' => 'Data Pribadi', 'width' => 'col-md-6', 'is_required' => true, 'sort_order' => 3],
                    ['field_type' => 'select', 'field_name' => 'pendidikan_terakhir', 'field_label' => 'Pendidikan Terakhir', 'options' => ['SMA IPA', 'SMA IPS', 'SMK', 'MA', 'Paket C'], 'section' => 'Data Pribadi', 'width' => 'col-md-6', 'is_required' => true, 'sort_order' => 4],
                    // Pengalaman Kerja
                    ['field_type' => 'select', 'field_name' => 'pengalaman_kerja', 'field_label' => 'Apakah Anda memiliki pengalaman kerja?', 'options' => ['Ya, kurang dari 1 tahun', 'Ya, 1-3 tahun', 'Ya, lebih dari 3 tahun', 'Tidak ada'], 'section' => 'Pengalaman Kerja', 'width' => 'col-md-6', 'is_required' => true, 'sort_order' => 5],
                    ['field_type' => 'text', 'field_name' => 'bidang_keahlian', 'field_label' => 'Bidang Keahlian / Minat', 'placeholder' => 'Contoh: Teknik Komputer, Akuntansi, Desain Grafis', 'section' => 'Pengalaman Kerja', 'width' => 'col-md-6', 'is_required' => false, 'sort_order' => 6],
                    ['field_type' => 'textarea', 'field_name' => 'sertifikasi', 'field_label' => 'Sertifikasi Profesi (jika ada)', 'placeholder' => 'Sebutkan sertifikasi yang dimiliki', 'section' => 'Pengalaman Kerja', 'width' => 'col-12', 'is_required' => false, 'sort_order' => 7],
                    ['field_type' => 'select', 'field_name' => 'program_vokasi', 'field_label' => 'Program Vokasi Pilihan', 'options' => ['D3 Akuntansi', 'D3 Teknik Informatika', 'D3 Administrasi Bisnis', 'D4 Manajemen Pemasaran', 'D4 Teknologi Rekayasa Perangkat Lunak'], 'section' => 'Pengalaman Kerja', 'width' => 'col-md-8', 'is_required' => true, 'sort_order' => 8],
                ],
            ],
            [
                'nama' => 'Form Pendaftaran Jalur Prestasi',
                'deskripsi' => 'Formulir untuk calon mahasiswa jalur prestasi, dengan portofolio dan pencapaian.',
                'fields' => [
                    ['field_type' => 'text', 'field_name' => 'nama_lengkap', 'field_label' => 'Nama Lengkap', 'placeholder' => 'Masukkan nama lengkap', 'section' => 'Data Pribadi', 'width' => 'col-md-6', 'is_required' => true, 'sort_order' => 1],
                    ['field_type' => 'email', 'field_name' => 'email', 'field_label' => 'Alamat Email', 'placeholder' => 'contoh@email.com', 'section' => 'Data Pribadi', 'width' => 'col-md-6', 'is_required' => true, 'sort_order' => 2],
                    ['field_type' => 'tel', 'field_name' => 'no_hp', 'field_label' => 'Nomor WhatsApp', 'placeholder' => '08xxxxxxxxxx', 'section' => 'Data Pribadi', 'width' => 'col-md-6', 'is_required' => true, 'sort_order' => 3],
                    // Portofolio Prestasi
                    ['field_type' => 'select', 'field_name' => 'tingkat_prestasi', 'field_label' => 'Tingkat Prestasi Tertinggi', 'options' => ['Sekolah/Kota', 'Kabupaten', 'Provinsi', 'Nasional', 'Internasional'], 'section' => 'Portofolio Prestasi', 'width' => 'col-md-6', 'is_required' => true, 'sort_order' => 4],
                    ['field_type' => 'select', 'field_name' => 'bidang_prestasi', 'field_label' => 'Bidang Prestasi', 'options' => ['Akademik (Olimpiade Sains)', 'Olahraga', 'Seni & Budaya', 'Teknologi & Robotik', 'Karya Ilmiah', 'Kepemimpinan & Organisasi'], 'section' => 'Portofolio Prestasi', 'width' => 'col-md-6', 'is_required' => true, 'sort_order' => 5],
                    ['field_type' => 'textarea', 'field_name' => 'deskripsi_prestasi', 'field_label' => 'Deskripsi Prestasi', 'placeholder' => 'Jelaskan prestasi yang pernah diraih secara detail', 'help_text' => 'Sebutkan nama lomba, tahun, dan pencapaian', 'section' => 'Portofolio Prestasi', 'width' => 'col-12', 'is_required' => true, 'sort_order' => 6],
                    ['field_type' => 'textarea', 'field_name' => 'organisasi', 'field_label' => 'Pengalaman Organisasi', 'placeholder' => 'Sebutkan pengalaman organisasi dan jabatan', 'section' => 'Portofolio Prestasi', 'width' => 'col-12', 'is_required' => false, 'sort_order' => 7],
                    ['field_type' => 'number', 'field_name' => 'rata_rata_nilai', 'field_label' => 'Rata-rata Nilai Rapor', 'placeholder' => 'Contoh: 88', 'help_text' => 'Rata-rata nilai rapor semester 1-5 skala 0-100', 'section' => 'Portofolio Prestasi', 'width' => 'col-md-4', 'is_required' => true, 'sort_order' => 8],
                ],
            ],
        ];

        foreach ($forms as $formData) {
            // Cek apakah form dengan nama yang sama sudah ada
            $existingForm = Form::where('nama', $formData['nama'])->first();
            if ($existingForm) {
                $this->command->info("Form '{$formData['nama']}' sudah ada (ID: {$existingForm->id}), skip.");
                continue;
            }

            // Buat form
            $form = Form::create([
                'nama' => $formData['nama'],
                'deskripsi' => $formData['deskripsi'],
                'is_active' => true,
            ]);

            $this->command->info("Form '{$form->nama}' created (ID: {$form->id})");

            // Buat fields
            foreach ($formData['fields'] as $fieldData) {
                $field = $form->fields()->create([
                    'field_type'  => $fieldData['field_type'],
                    'field_name'  => $fieldData['field_name'],
                    'field_label' => $fieldData['field_label'],
                    'placeholder' => $fieldData['placeholder'] ?? null,
                    'help_text'   => $fieldData['help_text'] ?? null,
                    'options'     => $fieldData['options'] ?? null,
                    'section'     => $fieldData['section'] ?? 'Data Pribadi',
                    'width'       => $fieldData['width'] ?? 'col-md-6',
                    'is_required' => $fieldData['is_required'] ?? false,
                    'is_active'   => true,
                    'is_system'   => in_array($fieldData['field_name'], FormField::coreFieldNames()),
                    'sort_order'  => $fieldData['sort_order'],
                ]);
            }

            // Ensure core fields exist (in case some are missing)
            FormField::ensureCoreFields($form->id);

            $this->command->info("  -> " . count($formData['fields']) . " fields created");
        }

        $this->command->info('FormPendaftaranSeeder completed! ' . count($forms) . ' forms processed.');
    }
}