<?php

namespace Database\Seeders;

use App\Models\PaketSoal;
use App\Models\SoalUjian;
use Illuminate\Database\Seeder;

class PaketSoalSeeder extends Seeder
{
    public function run(): void
    {
        if (PaketSoal::count() > 0) {
            $this->command->info('Paket soal already exists, skipping.');
            return;
        }

        // ═══ PAKET 1: Tes Potensi Akademik (TPA) ═══
        $p1 = PaketSoal::create([
            'nama_paket'   => 'Try Out TPA Saintek',
            'deskripsi'    => 'Paket try out tes potensi akademik untuk kelompok sains dan teknologi. Terdiri dari 10 soal dengan total skor 100.',
            'status_aktif' => true,
        ]);
        SoalUjian::insert([
            ['paket_soal_id' => $p1->id, 'pertanyaan' => 'Jika 3x + 7 = 22, maka nilai x adalah...', 'opsi_a' => '3', 'opsi_b' => '5', 'opsi_c' => '7', 'opsi_d' => '9', 'kunci_jawaban' => 'b', 'skor' => 10, 'urutan' => 1, 'status_aktif' => true],
            ['paket_soal_id' => $p1->id, 'pertanyaan' => 'Manakah dari berikut yang termasuk bilangan prima?', 'opsi_a' => '15', 'opsi_b' => '21', 'opsi_c' => '23', 'opsi_d' => '25', 'kunci_jawaban' => 'c', 'skor' => 10, 'urutan' => 2, 'status_aktif' => true],
            ['paket_soal_id' => $p1->id, 'pertanyaan' => 'Hukum Newton tentang gerak menyatakan bahwa...', 'opsi_a' => 'Gaya sebanding dengan massa', 'opsi_b' => 'Setiap aksi ada reaksi', 'opsi_c' => 'Energi tidak dapat diciptakan', 'opsi_d' => 'Massa jenis air adalah 1', 'kunci_jawaban' => 'b', 'skor' => 10, 'urutan' => 3, 'status_aktif' => true],
            ['paket_soal_id' => $p1->id, 'pertanyaan' => 'Apa fungsi utama mitokondria dalam sel?', 'opsi_a' => 'Sintesis protein', 'opsi_b' => 'Produksi energi (ATP)', 'opsi_c' => 'Pencernaan zat makanan', 'opsi_d' => 'Reproduksi sel', 'kunci_jawaban' => 'b', 'skor' => 10, 'urutan' => 4, 'status_aktif' => true],
            ['paket_soal_id' => $p1->id, 'pertanyaan' => 'Logaritma dari 1000 dengan basis 10 adalah...', 'opsi_a' => '1', 'opsi_b' => '2', 'opsi_c' => '3', 'opsi_d' => '4', 'kunci_jawaban' => 'c', 'skor' => 10, 'urutan' => 5, 'status_aktif' => true],
            ['paket_soal_id' => $p1->id, 'pertanyaan' => 'Gas rumah kaca yang paling banyak dihasilkan dari aktivitas manusia adalah...', 'opsi_a' => 'Oksigen', 'opsi_b' => 'Nitrogen', 'opsi_c' => 'Karbon dioksida', 'opsi_d' => 'Helium', 'kunci_jawaban' => 'c', 'skor' => 10, 'urutan' => 6, 'status_aktif' => true],
            ['paket_soal_id' => $p1->id, 'pertanyaan' => 'Jika sebuah benda bermassa 5 kg didorong dengan gaya 20 N, percepatannya adalah...', 'opsi_a' => '2 m/s²', 'opsi_b' => '4 m/s²', 'opsi_c' => '5 m/s²', 'opsi_d' => '10 m/s²', 'kunci_jawaban' => 'b', 'skor' => 10, 'urutan' => 7, 'status_aktif' => true],
            ['paket_soal_id' => $p1->id, 'pertanyaan' => 'Organisme yang dapat membuat makanan sendiri disebut...', 'opsi_a' => 'Konsumen', 'opsi_b' => 'Herbivora', 'opsi_c' => 'Autotrof', 'opsi_d' => 'Parasit', 'kunci_jawaban' => 'c', 'skor' => 10, 'urutan' => 8, 'status_aktif' => true],
            ['paket_soal_id' => $p1->id, 'pertanyaan' => 'Nilai dari sin 90° adalah...', 'opsi_a' => '0', 'opsi_b' => '0.5', 'opsi_c' => '1', 'opsi_d' => '∞', 'kunci_jawaban' => 'c', 'skor' => 10, 'urutan' => 9, 'status_aktif' => true],
            ['paket_soal_id' => $p1->id, 'pertanyaan' => 'Reaksi kimia yang melepaskan panas disebut...', 'opsi_a' => 'Endoterm', 'opsi_b' => 'Eksoterm', 'opsi_c' => 'Fotosintesis', 'opsi_d' => 'Hidrolisis', 'kunci_jawaban' => 'b', 'skor' => 10, 'urutan' => 10, 'status_aktif' => true],
        ]);

        // ═══ PAKET 2: Tes Bahasa Inggris ═══
        $p2 = PaketSoal::create([
            'nama_paket'   => 'Try Out Bahasa Inggris',
            'deskripsi'    => 'Paket try out kemampuan bahasa Inggris untuk program reguler dan internasional.',
            'status_aktif' => true,
        ]);
        SoalUjian::insert([
            ['paket_soal_id' => $p2->id, 'pertanyaan' => 'The opposite of "beautiful" is...', 'opsi_a' => 'Pretty', 'opsi_b' => 'Ugly', 'opsi_c' => 'Nice', 'opsi_d' => 'Cute', 'kunci_jawaban' => 'b', 'skor' => 20, 'urutan' => 1, 'status_aktif' => true],
            ['paket_soal_id' => $p2->id, 'pertanyaan' => 'She ___ to school every day.', 'opsi_a' => 'Go', 'opsi_b' => 'Goes', 'opsi_c' => 'Going', 'opsi_d' => 'Went', 'kunci_jawaban' => 'b', 'skor' => 20, 'urutan' => 2, 'status_aktif' => true],
            ['paket_soal_id' => $p2->id, 'pertanyaan' => 'What is the meaning of "diligent"?', 'opsi_a' => 'Pintar', 'opsi_b' => 'Rajin', 'opsi_c' => 'Malas', 'opsi_d' => 'Cepat', 'kunci_jawaban' => 'b', 'skor' => 20, 'urutan' => 3, 'status_aktif' => true],
            ['paket_soal_id' => $p2->id, 'pertanyaan' => 'Choose the correct sentence:', 'opsi_a' => 'I have went to Bali', 'opsi_b' => 'I has gone to Bali', 'opsi_c' => 'I have gone to Bali', 'opsi_d' => 'I gone to Bali', 'kunci_jawaban' => 'c', 'skor' => 20, 'urutan' => 4, 'status_aktif' => true],
            ['paket_soal_id' => $p2->id, 'pertanyaan' => 'Synonym of "happy" is...', 'opsi_a' => 'Sad', 'opsi_b' => 'Angry', 'opsi_c' => 'Glad', 'opsi_d' => 'Tired', 'kunci_jawaban' => 'c', 'skor' => 20, 'urutan' => 5, 'status_aktif' => true],
        ]);

        // ═══ PAKET 3: Tes Pengetahuan Agama ═══
        $p3 = PaketSoal::create([
            'nama_paket'   => 'Try Out Pengetahuan Keagamaan',
            'deskripsi'    => 'Paket soal untuk mengukur pengetahuan dasar keagamaan.',
            'status_aktif' => true,
        ]);
        SoalUjian::insert([
            ['paket_soal_id' => $p3->id, 'pertanyaan' => 'Rukun Islam yang pertama adalah...', 'opsi_a' => 'Puasa', 'opsi_b' => 'Syahadat', 'opsi_c' => 'Sholat', 'opsi_d' => 'Zakat', 'kunci_jawaban' => 'b', 'skor' => 20, 'urutan' => 1, 'status_aktif' => true],
            ['paket_soal_id' => $p3->id, 'pertanyaan' => 'Jumlah kitab suci yang wajib diimani ada...', 'opsi_a' => '2', 'opsi_b' => '3', 'opsi_c' => '4', 'opsi_d' => '5', 'kunci_jawaban' => 'c', 'skor' => 20, 'urutan' => 2, 'status_aktif' => true],
            ['paket_soal_id' => $p3->id, 'pertanyaan' => 'Malaikat yang bertugas mencatat amal baik adalah...', 'opsi_a' => 'Jibril', 'opsi_b' => 'Mikail', 'opsi_c' => 'Raqib', 'opsi_d' => 'Israfil', 'kunci_jawaban' => 'c', 'skor' => 20, 'urutan' => 3, 'status_aktif' => true],
            ['paket_soal_id' => $p3->id, 'pertanyaan' => 'Sholat sunnah yang dilakukan setelah Isya disebut...', 'opsi_a' => 'Dhuha', 'opsi_b' => 'Tahajud', 'opsi_c' => 'Tarawih', 'opsi_d' => 'Witir', 'kunci_jawaban' => 'b', 'skor' => 20, 'urutan' => 4, 'status_aktif' => true],
            ['paket_soal_id' => $p3->id, 'pertanyaan' => 'Puasa sunnah yang dilakukan setiap hari Senin dan Kamis disebut...', 'opsi_a' => 'Puasa Ramadhan', 'opsi_b' => 'Puasa Syawal', 'opsi_c' => 'Puasa Senin-Kamis', 'opsi_d' => 'Puasa Arofah', 'kunci_jawaban' => 'c', 'skor' => 20, 'urutan' => 5, 'status_aktif' => true],
        ]);

        // ═══ PAKET 4: Tes Kesehatan (STIKes) ═══
        $p4 = PaketSoal::create([
            'nama_paket'   => 'Try Out Dasar Kesehatan (STIKes)',
            'deskripsi'    => 'Paket soal untuk calon mahasiswa STIKes bidang kesehatan.',
            'status_aktif' => true,
        ]);
        SoalUjian::insert([
            ['paket_soal_id' => $p4->id, 'pertanyaan' => 'Organ terbesar dalam tubuh manusia adalah...', 'opsi_a' => 'Hati', 'opsi_b' => 'Kulit', 'opsi_c' => 'Otak', 'opsi_d' => 'Paru-paru', 'kunci_jawaban' => 'b', 'skor' => 20, 'urutan' => 1, 'status_aktif' => true],
            ['paket_soal_id' => $p4->id, 'pertanyaan' => 'Tekanan darah normal dewasa adalah...', 'opsi_a' => '90/60', 'opsi_b' => '120/80', 'opsi_c' => '140/90', 'opsi_d' => '160/100', 'kunci_jawaban' => 'b', 'skor' => 20, 'urutan' => 2, 'status_aktif' => true],
            ['paket_soal_id' => $p4->id, 'pertanyaan' => 'Vitamin yang dihasilkan saat kulit terkena sinar matahari adalah...', 'opsi_a' => 'Vitamin A', 'opsi_b' => 'Vitamin C', 'opsi_c' => 'Vitamin D', 'opsi_d' => 'Vitamin E', 'kunci_jawaban' => 'c', 'skor' => 20, 'urutan' => 3, 'status_aktif' => true],
            ['paket_soal_id' => $p4->id, 'pertanyaan' => 'Jenis tulang yang melindungi otak adalah...', 'opsi_a' => 'Tulang Paha', 'opsi_b' => 'Tulang Belakang', 'opsi_c' => 'Tulang Tengkorak', 'opsi_d' => 'Tulang Rusuk', 'kunci_jawaban' => 'c', 'skor' => 20, 'urutan' => 4, 'status_aktif' => true],
            ['paket_soal_id' => $p4->id, 'pertanyaan' => 'Pertolongan pertama pada luka bakar ringan adalah...', 'opsi_a' => 'Oleskan pasta gigi', 'opsi_b' => 'Aliri air dingin', 'opsi_c' => 'Tutup rapat dengan kain', 'opsi_d' => 'Oleskan mentega', 'kunci_jawaban' => 'b', 'skor' => 20, 'urutan' => 5, 'status_aktif' => true],
        ]);

        // ═══ PAKET 5: Tes Wawasan Kebangsaan ═══
        $p5 = PaketSoal::create([
            'nama_paket'   => 'Try Out Wawasan Kebangsaan',
            'deskripsi'    => 'Paket soal wawasan kebangsaan dan Pancasila untuk semua jalur.',
            'status_aktif' => true,
        ]);
        SoalUjian::insert([
            ['paket_soal_id' => $p5->id, 'pertanyaan' => 'Pancasila sebagai dasar negara tercantum dalam...', 'opsi_a' => 'UUD 1945', 'opsi_b' => 'Pembukaan UUD 1945', 'opsi_c' => 'Batang Tubuh UUD 1945', 'opsi_d' => 'Penjelasan UUD 1945', 'kunci_jawaban' => 'b', 'skor' => 20, 'urutan' => 1, 'status_aktif' => true],
            ['paket_soal_id' => $p5->id, 'pertanyaan' => 'Sila ke-3 Pancasila berbunyi...', 'opsi_a' => 'Kemanusiaan yang adil dan beradab', 'opsi_b' => 'Persatuan Indonesia', 'opsi_c' => 'Kerakyatan yang dipimpin oleh hikmat', 'opsi_d' => 'Keadilan sosial bagi seluruh rakyat', 'kunci_jawaban' => 'b', 'skor' => 20, 'urutan' => 2, 'status_aktif' => true],
            ['paket_soal_id' => $p5->id, 'pertanyaan' => 'Bendera Indonesia pertama kali dikibarkan pada...', 'opsi_a' => '17 Agustus 1945', 'opsi_b' => '28 Oktober 1928', 'opsi_c' => '20 Mei 1908', 'opsi_d' => '1 Juni 1945', 'kunci_jawaban' => 'a', 'skor' => 20, 'urutan' => 3, 'status_aktif' => true],
            ['paket_soal_id' => $p5->id, 'pertanyaan' => 'Lagu kebangsaan Indonesia Raya diciptakan oleh...', 'opsi_a' => 'W.R. Supratman', 'opsi_b' => 'Ibu Sud', 'opsi_c' => 'C. Simandjuntak', 'opsi_d' => 'H. Mutahar', 'kunci_jawaban' => 'a', 'skor' => 20, 'urutan' => 4, 'status_aktif' => true],
            ['paket_soal_id' => $p5->id, 'pertanyaan' => 'Batas wilayah Indonesia di sebelah timur adalah...', 'opsi_a' => 'Samudra Hindia', 'opsi_b' => 'Samudra Pasifik', 'opsi_c' => 'Papua Nugini', 'opsi_d' => 'Malaysia', 'kunci_jawaban' => 'c', 'skor' => 20, 'urutan' => 5, 'status_aktif' => true],
        ]);

        $this->command->info('5 paket soal created with total ' . SoalUjian::count() . ' soal ujian.');
    }
}