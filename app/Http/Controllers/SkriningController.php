<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skrining;
use App\Models\Pertanyaan;
use App\Models\Jawaban;
use Illuminate\Support\Str;

class SkriningController extends Controller
{
    public function index()
    {
        return view('skrining.index');
    }

    public function mulai(Request $request)
    {
        $pertanyaan = Pertanyaan::orderBy('urutan')->get();
        if ($pertanyaan->isEmpty()) {
            return redirect()->route('skrining.index')->with('error', 'Belum ada pertanyaan skrining yang tersedia.');
        }

        // Ensure anonymous code exists
        if (!session()->has('kode_unik')) {
            $code = 'ANH-' . strtoupper(Str::random(10));
            session(['kode_unik' => $code]);
        }

        // If screening is already in progress, resume from current question
        $existingPertanyaan = session('skrining_pertanyaan');
        if ($existingPertanyaan && collect($existingPertanyaan)->pluck('id_pertanyaan')->sort()->values()->all()
            === $pertanyaan->pluck('id_pertanyaan')->sort()->values()->all()) {
            $current = (int) session('skrining_current', 0);
            return view('skrining.mulai', [
                'pertanyaan' => $pertanyaan,
                'current' => $current,
            ]);
        }

        // Store pertanyaan in session for the screening flow
        session(['skrining_pertanyaan' => $pertanyaan->toArray()]);
        session(['skrining_jawaban' => []]);
        session(['skrining_current' => 0]);

        return view('skrining.mulai', [
            'pertanyaan' => $pertanyaan,
            'current' => 0,
        ]);
    }

    public function simpanJawaban(Request $request)
    {
        $current = (int) $request->input('current', 0);
        $jawaban = session('skrining_jawaban', []);

        if ($request->has('pilihan')) {
            $jawaban[$current] = [
                'id_pertanyaan' => $request->input('id_pertanyaan'),
                'pilihan_jawaban' => $request->input('pilihan_teks'),
                'skor_kontribusi' => (int) $request->input('pilihan'),
            ];
            session(['skrining_jawaban' => $jawaban]);
        }

        $pertanyaan = Pertanyaan::orderBy('urutan')->get();
        $direction = $request->input('direction', 'next');

        if ($direction === 'prev') {
            $current = max(0, $current - 1);
        } elseif ($direction === 'next') {
            if (!$request->has('pilihan')) {
                return redirect()->route('skrining.mulai')->with('error', 'Silakan pilih jawaban terlebih dahulu.');
            }
            $current++;
        }

        session(['skrining_current' => $current]);

        // If we've answered all questions, submit
        if ($current >= $pertanyaan->count()) {
            return $this->submitSkrining();
        }

        return view('skrining.mulai', [
            'pertanyaan' => $pertanyaan,
            'current' => $current,
        ]);
    }

    private function submitSkrining()
    {
        $jawaban = session('skrining_jawaban', []);
        $kodeUnik = session('kode_unik');

        $skorTotal = 0;
        foreach ($jawaban as $j) {
            $skorTotal += $j['skor_kontribusi'];
        }

        // Determine risk level based on total score
        // Max possible score from seeder: 6+6+5+7+5+6+2+6+4+4 = 51
        if ($skorTotal <= 10) {
            $tingkatRisiko = 'Rendah';
        } elseif ($skorTotal <= 25) {
            $tingkatRisiko = 'Sedang';
        } else {
            $tingkatRisiko = 'Tinggi';
        }

        $skrining = Skrining::create([
            'id_pengguna' => auth()->check() ? auth()->user()->id_pengguna : null,
            'kode_unik' => $kodeUnik,
            'tanggal_skrining' => now(),
            'skor_total' => $skorTotal,
            'tingkat_risiko' => $tingkatRisiko,
        ]);

        // Save individual answers
        foreach ($jawaban as $j) {
            Jawaban::create([
                'id_skrining' => $skrining->id_skrining,
                'id_pertanyaan' => $j['id_pertanyaan'],
                'pilihan_jawaban' => $j['pilihan_jawaban'],
                'skor_kontribusi' => $j['skor_kontribusi'],
            ]);
        }

        // Clear session data
        session()->forget(['skrining_pertanyaan', 'skrining_jawaban', 'skrining_current']);

        return redirect()->route('skrining.hasil', $skrining->id_skrining);
    }

    public function hasil($id)
    {
        $skrining = Skrining::with('jawaban.pertanyaan')->findOrFail($id);

        // Recommendations based on risk
        $rekomendasi = match ($skrining->tingkat_risiko) {
            'Rendah' => 'Risiko Anda tergolong rendah. Tetap jaga perilaku aman dan lakukan tes HIV secara berkala sebagai tindakan pencegahan.',
            'Sedang' => 'Risiko Anda tergolong sedang. Disarankan untuk segera melakukan tes HIV di fasilitas kesehatan terdekat dan berkonsultasi dengan tenaga kesehatan.',
            'Tinggi' => 'Risiko Anda tergolong tinggi. Sangat disarankan untuk segera melakukan tes HIV dan konsultasi dengan dokter. Anda juga dapat mengajukan konseling melalui ANHIVA.',
            default => '',
        };

        return view('skrining.hasil', compact('skrining', 'rekomendasi'));
    }

    public function riwayat(Request $request)
    {
        if (auth()->check()) {
            $riwayat = Skrining::where('id_pengguna', auth()->user()->id_pengguna)
                ->orderBy('tanggal_skrining', 'desc')
                ->paginate(10);
            return view('skrining.riwayat', compact('riwayat'));
        }

        // Anonymous: require code
        if ($request->has('kode_unik')) {

    $request->validate([
        'kode_unik' => 'required|string'
    ]);

    $riwayat = Skrining::where('kode_unik', $request->kode_unik)
        ->orderBy('tanggal_skrining', 'desc')
        ->paginate(10);

    if ($riwayat->total() === 0) {
        return back()
            ->withErrors([
                'kode_unik' => 'Data riwayat skrining tidak ditemukan.'
            ])
            ->withInput();
    }

    return view('skrining.riwayat', compact('riwayat'));
}

        return view('skrining.riwayat', ['riwayat' => null]);
    }
}
