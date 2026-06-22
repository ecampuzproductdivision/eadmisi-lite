<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingFacility;
use App\Models\LandingFeature;
use App\Models\LandingProgramStudi;
use App\Models\LandingSetting;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        $features = LandingFeature::orderBy('sort_order')->get();
        $settings = LandingSetting::all()->keyBy('key');
        $iconOptions = [
            'ti-certificate', 'ti-affiliate', 'ti-device-laptop', 'ti-users',
            'ti-heart', 'ti-star', 'ti-shield', 'ti-flag', 'ti-award',
            'ti-book', 'ti-globe', 'ti-building-bank', 'ti-currency-dollar',
        ];

        $landingProdis = LandingProgramStudi::with('programStudi')->get();
        $allProgramStudis = ProgramStudi::active()->orderBy('nama')->get();
        $iconOptionsProdi = [
            'ti-device-analytics', 'ti-building-bank', 'ti-report-money', 'ti-heart',
            'ti-language', 'ti-currency-dollar', 'ti-microscope', 'ti-book', 'ti-globe',
        ];

        $facilities = LandingFacility::orderBy('urutan')->get();
        $facilityIcons = ['ti-wifi', 'ti-books', 'ti-microscope', 'ti-building-stadium', 'ti-school', 'ti-users', 'ti-heart', 'ti-star', 'ti-award', 'ti-globe'];

        return view('settings.landing-page.index', compact('features', 'settings', 'iconOptions', 'landingProdis', 'allProgramStudis', 'iconOptionsProdi', 'facilities', 'facilityIcons'));
    }

    public function storeFeature(Request $request)
    {
        $validated = $request->validate([
            'judul_poin' => 'required|string|max:255',
            'deskripsi_poin' => 'required|string',
            'nama_icon' => 'required|string|max:100',
            'warna_skema' => 'nullable|string|max:50',
        ]);

        $maxOrder = LandingFeature::max('sort_order') ?? 0;
        LandingFeature::create([
            'judul_poin' => $validated['judul_poin'],
            'deskripsi_poin' => $validated['deskripsi_poin'],
            'nama_icon' => $validated['nama_icon'],
            'warna_skema' => $validated['warna_skema'] ?? 'danger',
            'sort_order' => $maxOrder + 1,
            'is_active' => true,
        ]);

        return redirect()->route('settings.landing-page.index')
            ->with('success', 'Keunggulan berhasil ditambahkan.');
    }

    public function updateFeature(Request $request, LandingFeature $landingFeature)
    {
        $validated = $request->validate([
            'judul_poin' => 'required|string|max:255',
            'deskripsi_poin' => 'required|string',
            'nama_icon' => 'required|string|max:100',
            'warna_skema' => 'nullable|string|max:50',
        ]);

        $landingFeature->update($validated);

        return redirect()->route('settings.landing-page.index')
            ->with('success', 'Keunggulan berhasil diperbarui.');
    }

    public function toggleFeature(LandingFeature $landingFeature)
    {
        $landingFeature->update(['is_active' => !$landingFeature->is_active]);

        return redirect()->route('settings.landing-page.index')
            ->with('success', 'Status keunggulan berhasil diubah.');
    }

    public function destroyFeature(LandingFeature $landingFeature)
    {
        $landingFeature->delete();

        return redirect()->route('settings.landing-page.index')
            ->with('success', 'Keunggulan berhasil dihapus.');
    }

    public function storeLandingProdi(Request $request)
    {
        $validated = $request->validate([
            'program_studi_id' => 'required|exists:program_studis,id',
            'deskripsi_singkat' => 'nullable|string',
            'kode_icon' => 'required|string|max:100',
            'akreditasi' => 'nullable|string|max:50',
            'jumlah_semester' => 'nullable|integer|min:1|max:12',
            'is_published' => 'boolean',
        ]);

        LandingProgramStudi::updateOrCreate(
            ['program_studi_id' => $validated['program_studi_id']],
            [
                'deskripsi_singkat' => $validated['deskripsi_singkat'] ?? '',
                'kode_icon' => $validated['kode_icon'],
                'akreditasi' => $validated['akreditasi'] ?? null,
                'jumlah_semester' => $validated['jumlah_semester'] ?? null,
                'is_published' => $request->boolean('is_published', true),
            ]
        );

        return redirect()->route('settings.landing-page.index')
            ->with('success', 'Program studi berhasil ditambahkan ke landing page.');
    }

    public function toggleLandingProdi(LandingProgramStudi $landingProgramStudi)
    {
        $landingProgramStudi->update(['is_published' => !$landingProgramStudi->is_published]);
        return redirect()->route('settings.landing-page.index')
            ->with('success', 'Status publish berhasil diubah.');
    }

    public function destroyLandingProdi(LandingProgramStudi $landingProgramStudi)
    {
        $landingProgramStudi->delete();
        return redirect()->route('settings.landing-page.index')
            ->with('success', 'Program studi berhasil dihapus dari landing page.');
    }

    public function storeFacility(Request $request)
    {
        $validated = $request->validate([
            'nama_fasilitas' => 'required|string|max:255',
            'deskripsi_fasilitas' => 'nullable|string',
            'kode_icon' => 'required|string|max:100',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        LandingFacility::create([
            'nama_fasilitas' => $validated['nama_fasilitas'],
            'deskripsi_fasilitas' => $validated['deskripsi_fasilitas'] ?? '',
            'kode_icon' => $validated['kode_icon'],
            'urutan' => $validated['urutan'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('settings.landing-page.index')
            ->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function toggleFacility(LandingFacility $landingFacility)
    {
        $landingFacility->update(['is_active' => !$landingFacility->is_active]);
        return redirect()->route('settings.landing-page.index')
            ->with('success', 'Status fasilitas berhasil diubah.');
    }

    public function destroyFacility(LandingFacility $landingFacility)
    {
        $landingFacility->delete();
        return redirect()->route('settings.landing-page.index')
            ->with('success', 'Fasilitas berhasil dihapus.');
    }

    public function updateSettings(Request $request)
    {
        $keys = ['contact_email', 'contact_phone', 'contact_address', 'social_instagram', 'social_facebook', 'social_youtube', 'landing_about_title', 'landing_about_description', 'landing_facility_title', 'landing_facility_description'];

        foreach ($keys as $key) {
            $value = $request->input($key);
            LandingSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '']
            );
        }

        return redirect()->route('settings.landing-page.index')
            ->with('success', 'Pengaturan kontak & sosial media berhasil disimpan.');
    }
}