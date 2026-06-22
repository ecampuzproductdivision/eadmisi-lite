<?php

namespace Database\Seeders;

use App\Models\SyaratDokumen;
use App\Models\TemplateBerkas;
use Illuminate\Database\Seeder;

class SyaratBerkasSeeder extends Seeder
{
    public function run(): void
    {
        // Cek jika sudah ada data
        if (TemplateBerkas::count() > 0) {
            $this->command->info('Template berkas already exists, skipping.');
            return;
        }

        // ── Template 1: Registrasi Reguler ──
        $t1 = TemplateBerkas::create([
            'nama_template' => 'Dokumen Registrasi Reguler',
            'deskripsi'     => 'Template dokumen standar untuk jalur pendaftaran reguler (SNBP/SNBT/Mandiri).',
            'status_aktif'  => true,
        ]);
        SyaratDokumen::insert([
            ['template_berkas_id' => $t1->id, 'nama_dokumen' => 'Ijazah / Surat Keterangan Lulus', 'ekstensi_diizinkan' => 'pdf,jpg,jpeg,png', 'max_size' => 5120, 'status_wajib' => true, 'urutan' => 1],
            ['template_berkas_id' => $t1->id, 'nama_dokumen' => 'Kartu Keluarga',                     'ekstensi_diizinkan' => 'pdf,jpg,jpeg,png', 'max_size' => 2048, 'status_wajib' => true, 'urutan' => 2],
            ['template_berkas_id' => $t1->id, 'nama_dokumen' => 'Akta Kelahiran',                     'ekstensi_diizinkan' => 'pdf,jpg,jpeg,png', 'max_size' => 2048, 'status_wajib' => true, 'urutan' => 3],
            ['template_berkas_id' => $t1->id, 'nama_dokumen' => 'Foto Formal 3x4',                    'ekstensi_diizinkan' => 'jpg,jpeg,png',       'max_size' => 1024, 'status_wajib' => true, 'urutan' => 4],
            ['template_berkas_id' => $t1->id, 'nama_dokumen' => 'Sertifikat Prestasi (jika ada)',     'ekstensi_diizinkan' => 'pdf',                'max_size' => 3072, 'status_wajib' => false, 'urutan' => 5],
        ]);

        // ── Template 2: Beasiswa KIP Kuliah ──
        $t2 = TemplateBerkas::create([
            'nama_template' => 'Dokumen Beasiswa KIP Kuliah',
            'deskripsi'     => 'Template dokumen khusus untuk pendaftar jalur KIP Kuliah.',
            'status_aktif'  => true,
        ]);
        SyaratDokumen::insert([
            ['template_berkas_id' => $t2->id, 'nama_dokumen' => 'Ijazah / SKL',                        'ekstensi_diizinkan' => 'pdf,jpg,jpeg,png', 'max_size' => 5120, 'status_wajib' => true, 'urutan' => 1],
            ['template_berkas_id' => $t2->id, 'nama_dokumen' => 'Kartu Keluarga',                      'ekstensi_diizinkan' => 'pdf,jpg,jpeg,png', 'max_size' => 2048, 'status_wajib' => true, 'urutan' => 2],
            ['template_berkas_id' => $t2->id, 'nama_dokumen' => 'Kartu Indonesia Pintar (KIP)',        'ekstensi_diizinkan' => 'pdf,jpg,jpeg,png', 'max_size' => 2048, 'status_wajib' => true, 'urutan' => 3],
            ['template_berkas_id' => $t2->id, 'nama_dokumen' => 'Surat Keterangan Tidak Mampu (SKTM)', 'ekstensi_diizinkan' => 'pdf',                'max_size' => 3072, 'status_wajib' => true, 'urutan' => 4],
            ['template_berkas_id' => $t2->id, 'nama_dokumen' => 'Slip Gaji Orang Tua',                 'ekstensi_diizinkan' => 'pdf',                'max_size' => 2048, 'status_wajib' => false, 'urutan' => 5],
        ]);

        // ── Template 3: Program Internasional ──
        $t3 = TemplateBerkas::create([
            'nama_template' => 'Dokumen Program Internasional (IUP)',
            'deskripsi'     => 'Template dokumen untuk pendaftar program internasional / double degree.',
            'status_aktif'  => true,
        ]);
        SyaratDokumen::insert([
            ['template_berkas_id' => $t3->id, 'nama_dokumen' => 'Ijazah & Transkrip Nilai (Inggris)', 'ekstensi_diizinkan' => 'pdf',                'max_size' => 5120, 'status_wajib' => true, 'urutan' => 1],
            ['template_berkas_id' => $t3->id, 'nama_dokumen' => 'Sertifikat TOEFL / IELTS',           'ekstensi_diizinkan' => 'pdf',                'max_size' => 2048, 'status_wajib' => true, 'urutan' => 2],
            ['template_berkas_id' => $t3->id, 'nama_dokumen' => 'Paspor (Halaman Identitas)',         'ekstensi_diizinkan' => 'pdf,jpg,jpeg,png', 'max_size' => 2048, 'status_wajib' => true, 'urutan' => 3],
            ['template_berkas_id' => $t3->id, 'nama_dokumen' => 'Motivation Letter',                  'ekstensi_diizinkan' => 'pdf',                'max_size' => 2048, 'status_wajib' => true, 'urutan' => 4],
            ['template_berkas_id' => $t3->id, 'nama_dokumen' => 'Rekomendasi Dosen/Guru',             'ekstensi_diizinkan' => 'pdf',                'max_size' => 2048, 'status_wajib' => false, 'urutan' => 5],
        ]);

        // ── Template 4: Pindahan / Alih Jenjang ──
        $t4 = TemplateBerkas::create([
            'nama_template' => 'Dokumen Alih Jenjang / Transfer',
            'deskripsi'     => 'Template dokumen untuk pendaftar pindahan dari perguruan tinggi lain.',
            'status_aktif'  => true,
        ]);
        SyaratDokumen::insert([
            ['template_berkas_id' => $t4->id, 'nama_dokumen' => 'Transkip Nilai Semester 1-4',        'ekstensi_diizinkan' => 'pdf',                'max_size' => 5120, 'status_wajib' => true, 'urutan' => 1],
            ['template_berkas_id' => $t4->id, 'nama_dokumen' => 'KRS Semester Terakhir',              'ekstensi_diizinkan' => 'pdf',                'max_size' => 2048, 'status_wajib' => true, 'urutan' => 2],
            ['template_berkas_id' => $t4->id, 'nama_dokumen' => 'Surat Pindah dari PT Asal',          'ekstensi_diizinkan' => 'pdf',                'max_size' => 2048, 'status_wajib' => true, 'urutan' => 3],
            ['template_berkas_id' => $t4->id, 'nama_dokumen' => 'Kartu Rencana Studi (KRS)',          'ekstensi_diizinkan' => 'pdf',                'max_size' => 1024, 'status_wajib' => false, 'urutan' => 4],
        ]);

        // ── Template 5: Jalur Prestasi Olahraga ──
        $t5 = TemplateBerkas::create([
            'nama_template' => 'Dokumen Prestasi Olahraga & Seni',
            'deskripsi'     => 'Template dokumen khusus pendaftar jalur prestasi olahraga dan seni budaya.',
            'status_aktif'  => true,
        ]);
        SyaratDokumen::insert([
            ['template_berkas_id' => $t5->id, 'nama_dokumen' => 'Ijazah / SKL',                        'ekstensi_diizinkan' => 'pdf,jpg,jpeg,png', 'max_size' => 5120, 'status_wajib' => true, 'urutan' => 1],
            ['template_berkas_id' => $t5->id, 'nama_dokumen' => 'Sertifikat/Piagam Prestasi Asli',     'ekstensi_diizinkan' => 'pdf,jpg,jpeg,png', 'max_size' => 5120, 'status_wajib' => true, 'urutan' => 2],
            ['template_berkas_id' => $t5->id, 'nama_dokumen' => 'Surat Rekomendasi Pelatih/Pembina',   'ekstensi_diizinkan' => 'pdf',                'max_size' => 2048, 'status_wajib' => true, 'urutan' => 3],
            ['template_berkas_id' => $t5->id, 'nama_dokumen' => 'Video Portofolio (Link YouTube)',     'ekstensi_diizinkan' => 'pdf',                'max_size' => 1024, 'status_wajib' => false, 'urutan' => 4],
        ]);

        $this->command->info('5 template berkas created with total ' . SyaratDokumen::count() . ' syarat dokumen.');
    }
}