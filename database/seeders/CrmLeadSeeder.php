<?php

namespace Database\Seeders;

use App\Models\CrmLead;
use Illuminate\Database\Seeder;

class CrmLeadSeeder extends Seeder
{
    public function run(): void
    {
        $leads = [
            [
                'nama' => 'Siti Rahmawati',
                'whatsapp' => '6281234567890',
                'pertanyaan' => 'Selamat siang, saya ingin bertanya mengenai program studi D3 Keperawatan di STIKes. Apakah masih ada kuota pendaftaran untuk jalur mandiri tahun ini? Terima kasih.',
                'status' => 'New',
            ],
            [
                'nama' => 'Ahmad Fauzi',
                'whatsapp' => '6289876543210',
                'pertanyaan' => 'Halo min, saya mau tanya kalau kuliah di sini bisa cicilan per bulan? Soalnya saya kerja part time, takut nggak sanggup bayar full. Trus untuk akreditasinya bagaimana ya?',
                'status' => 'In Progress',
                'catatan_admin' => 'Dijelaskan opsi cicilan 4x. Menunggu konfirmasi ketersediaan dana.',
            ],
            [
                'nama' => 'Diana Kusuma Wardhani',
                'whatsapp' => '6285551234567',
                'pertanyaan' => 'Saya lulusan SMA IPA tahun lalu. Apakah ada program beasiswa untuk mahasiswa berprestasi? Saya juara umum 3 besar selama 2 tahun berturut-turut. Mohon informasinya.',
                'status' => 'New',
            ],
            [
                'nama' => 'Budi Santoso',
                'whatsapp' => '6281112223334',
                'pertanyaan' => 'Min, saya minat daftar di STIKes. Untuk jurusan S1 Gizi, apakah nanti ada pemeriksaan kesehatan khusus? Soalnya saya punya riwayat asma ringan, takut nggak lolos. Terima kasih.',
                'status' => 'Responded',
                'catatan_admin' => 'Sudah dijelaskan bahwa asma ringan tidak menjadi halangan selama terkontrol. Prospek akan daftar minggu depan.',
            ],
            [
                'nama' => 'Rina Marlina',
                'whatsapp' => '6287778889990',
                'pertanyaan' => 'Assalamualaikum, saya mau tanya jadwal pendaftaran gelombang 2 kapan ya? Saya baru selesai kerja bakti di desa dan baru bisa daftar bulan depan. Mohon infonya.',
                'status' => 'Converted',
                'catatan_admin' => 'Prospek sudah mendaftar via jalur prestasi gelombang 1. Data sudah masuk sistem.',
            ],
        ];

        foreach ($leads as $lead) {
            CrmLead::create($lead);
        }

        $this->command->info('5 sample CRM leads created successfully.');
    }
}