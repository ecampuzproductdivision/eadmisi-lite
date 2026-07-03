<?php

namespace App\Helpers;

class LocationHelper
{
    /**
     * Get the master list of Indonesian regencies/cities formatted for Select2.
     * Returns array of ['id' => '...', 'text' => 'Kab./Kota ..., Provinsi']
     */
    public static function getAll(): array
    {
        return [
            ['id' => 'Aceh Barat', 'text' => 'Kab. Aceh Barat, Aceh'],
            ['id' => 'Banda Aceh', 'text' => 'Kota Banda Aceh, Aceh'],
            ['id' => 'Sabang', 'text' => 'Kota Sabang, Aceh'],
            ['id' => 'Langsa', 'text' => 'Kota Langsa, Aceh'],
            ['id' => 'Lhokseumawe', 'text' => 'Kota Lhokseumawe, Aceh'],
            ['id' => 'Subulussalam', 'text' => 'Kota Subulussalam, Aceh'],
            ['id' => 'Medan', 'text' => 'Kota Medan, Sumatera Utara'],
            ['id' => 'Deli Serdang', 'text' => 'Kab. Deli Serdang, Sumatera Utara'],
            ['id' => 'Sleman', 'text' => 'Kab. Sleman, D.I. Yogyakarta'],
            ['id' => 'Bantul', 'text' => 'Kab. Bantul, D.I. Yogyakarta'],
            ['id' => 'Yogyakarta', 'text' => 'Kota Yogyakarta, D.I. Yogyakarta'],
            ['id' => 'Bandung', 'text' => 'Kota Bandung, Jawa Barat'],
            ['id' => 'Jakarta Selatan', 'text' => 'Kota Jakarta Selatan, DKI Jakarta'],
            ['id' => 'Surabaya', 'text' => 'Kota Surabaya, Jawa Timur'],
            ['id' => 'Makassar', 'text' => 'Kota Makassar, Sulawesi Selatan'],
            ['id' => 'Denpasar', 'text' => 'Kota Denpasar, Bali'],
            ['id' => 'Palu', 'text' => 'Kota Palu, Sulawesi Tengah'],
        ];
    }

    /**
     * Render HTML <option> tags for all locations.
     */
    public static function renderOptions(string $selectedValue = ''): string
    {
        $html = '<option value=""></option>';
        foreach (self::getAll() as $loc) {
            $sel = ($loc['id'] === $selectedValue) ? ' selected' : '';
            $html .= '<option value="' . htmlspecialchars($loc['id'], ENT_QUOTES) . '"' . $sel . '>' . htmlspecialchars($loc['text'], ENT_NOQUOTES) . '</option>';
        }
        return $html;
    }
}