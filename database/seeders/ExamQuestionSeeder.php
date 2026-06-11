<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExamQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        DB::table('exam_questions')->truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $questions = [
            // Numerik (5 soal)
            [
                'category' => 'Numerik',
                'question' => 'Jika 3x + 7 = 22, maka nilai x adalah...',
                'options' => ['A. 3', 'B. 4', 'C. 5', 'D. 6', 'E. 7'],
                'correct_answer' => 'C',
                'order' => 1,
            ],
            [
                'category' => 'Numerik',
                'question' => 'Sebuah tabung berisi 240 liter air. Jika 3/5 bagian dituang keluar, sisa air dalam tabung adalah...',
                'options' => ['A. 72 liter', 'B. 84 liter', 'C. 96 liter', 'D. 120 liter', 'E. 144 liter'],
                'correct_answer' => 'C',
                'order' => 2,
            ],
            [
                'category' => 'Numerik',
                'question' => 'Hasil dari 25% × 800 + 15% × 400 adalah...',
                'options' => ['A. 240', 'B. 260', 'C. 280', 'D. 300', 'E. 320'],
                'correct_answer' => 'B',
                'order' => 3,
            ],
            [
                'category' => 'Numerik',
                'question' => 'Rata-rata nilai 5 siswa adalah 78. Jika nilai siswa keenam adalah 92, maka rata-rata baru adalah...',
                'options' => ['A. 80', 'B. 80.33', 'C. 81', 'D. 82', 'E. 83'],
                'correct_answer' => 'B',
                'order' => 4,
            ],
            [
                'category' => 'Numerik',
                'question' => 'Jika (2^3) × (2^4) = 2^n, maka nilai n adalah...',
                'options' => ['A. 7', 'B. 8', 'C. 12', 'D. 24', 'E. 28'],
                'correct_answer' => 'A',
                'order' => 5,
            ],

            // Verbal (5 soal)
            [
                'category' => 'Verbal',
                'question' => 'Pilih pasangan kata yang memiliki hubungan makna SEBAB-AKIBAT...',
                'options' => ['A. Panas - Dingin', 'B. Hujan - Banjir', 'C. Buku - Pena', 'D. Rumah - Jendela', 'E. Mobil - Roda'],
                'correct_answer' => 'B',
                'order' => 6,
            ],
            [
                'category' => 'Verbal',
                'question' => 'Antonim dari kata "Bijaksana" adalah...',
                'options' => ['A. Pandai', 'B. Bodoh', 'C. Ceroboh', 'D. Sederhana', 'E. Sombong'],
                'correct_answer' => 'C',
                'order' => 7,
            ],
            [
                'category' => 'Verbal',
                'question' => 'Manakah kalimat yang menggunakan EJAAN yang BENAR?',
                'options' => ['A. Ia pergi kepasar.', 'B. Ia pergi ke pasar.', 'C. Ia pergi Ke Pasar.', 'D. Ia pergi ke-pasar.', 'E. Ia Pergi ke Pasar.'],
                'correct_answer' => 'B',
                'order' => 8,
            ],
            [
                'category' => 'Verbal',
                'question' => 'Sinonim dari kata "Ekonomis" adalah...',
                'options' => ['A. Boros', 'B. Hemat', 'C. Kaya', 'D. Murah', 'E. Mahal'],
                'correct_answer' => 'B',
                'order' => 9,
            ],
            [
                'category' => 'Verbal',
                'question' => 'Kalimat yang mengandung KATA SIFAT adalah...',
                'options' => ['A. Siswa membaca buku.', 'B. Bunga itu sangat indah.', 'C. Kami pergi ke sekolah.', 'D. Ayah bekerja di kantor.', 'E. Anak-anak bermain di taman.'],
                'correct_answer' => 'B',
                'order' => 10,
            ],

            // Logika (5 soal)
            [
                'category' => 'Logika',
                'question' => '3, 6, 12, 24, ?, 96. Angka yang tepat untuk mengisi tanda tanya adalah...',
                'options' => ['A. 36', 'B. 42', 'C. 48', 'D. 54', 'E. 60'],
                'correct_answer' => 'C',
                'order' => 11,
            ],
            [
                'category' => 'Logika',
                'question' => 'Jika semua A adalah B, dan semua B adalah C, maka bisa disimpulkan bahwa...',
                'options' => ['A. Semua C adalah A', 'B. Semua A adalah C', 'C. Tidak ada A yang C', 'D. Beberapa C adalah A', 'E. Tidak ada hubungan'],
                'correct_answer' => 'B',
                'order' => 12,
            ],
            [
                'category' => 'Logika',
                'question' => 'Pola: A, C, E, G, ?, K. Huruf yang tepat untuk mengisi tanda tanya adalah...',
                'options' => ['A. H', 'B. I', 'C. J', 'D. L', 'E. M'],
                'correct_answer' => 'B',
                'order' => 13,
            ],
            [
                'category' => 'Logika',
                'question' => 'Jika today is Monday, what day will it be 100 days from now?',
                'options' => ['A. Monday', 'B. Tuesday', 'C. Wednesday', 'D. Thursday', 'E. Friday'],
                'correct_answer' => 'C',
                'order' => 14,
            ],
            [
                'category' => 'Logika',
                'question' => 'Sebuah mobil bergerak dari kota A ke kota B dengan kecepatan 60 km/jam. Untuk perjalanan pulang, mobil berkecepatan 40 km/jam. Rata-rata kecepatan mobil untuk seluruh perjalanan adalah...',
                'options' => ['A. 45 km/jam', 'B. 48 km/jam', 'C. 50 km/jam', 'D. 52 km/jam', 'E. 55 km/jam'],
                'correct_answer' => 'B',
                'order' => 15,
            ],
        ];

        // JSON encode options for each question
        $encodedQuestions = array_map(function ($q) {
            $q['options'] = json_encode($q['options']);
            return $q;
        }, $questions);

        DB::table('exam_questions')->insert($encodedQuestions);

        $this->command->info('Exam questions seeded successfully! (15 soal)');
    }
}