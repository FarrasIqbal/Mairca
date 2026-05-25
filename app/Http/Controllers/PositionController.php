<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\Candidate;
use App\Models\Evaluation;
use App\Services\MaircaEngineService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::withCount(['criteria', 'candidates'])->latest()->paginate(10);
        return view('positions.index', compact('positions'));
    }

    // ================= ACTION SENTRAL: POSITION CONTROL CENTER =================
    public function show(Position $position, MaircaEngineService $maircaService)
    {
        // 1. Data Kriteria & Total Bobot
        $criteria = $position->criteria()->get();
        $totalWeight = $criteria->sum('weight');

        // 2. Data Semua Kandidat untuk Pipeline
        $allCandidates = $position->candidates()->latest()->get();

        // 3. Data Kandidat Khusus Tahap Evaluasi SPK (Untuk Grid Nilai)
        $evalCandidates = $position->candidates()->where('status', 'evaluasi_spk')->get();

        // 4. Ambil Skor Eksisting yang pernah diinput Reviewer ini
        $evaluations = Evaluation::where('user_id', Auth::id())
            ->whereIn('candidate_id', $evalCandidates->pluck('id'))
            ->get();

        $existingScores = [];
        foreach ($evaluations as $eval) {
            $existingScores[$eval->candidate_id][$eval->criteria_id] = $eval->score;
        }

        // 5. Jalankan MAIRCA Engine secara Real-Time
        $maircaResult = $maircaService->calculate($position);

        return view('positions.show', compact(
            'position',
            'criteria',
            'totalWeight',
            'allCandidates',
            'evalCandidates',
            'existingScores',
            'maircaResult'
        ));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:positions,name']);
        Position::create(['name' => $request->name, 'is_active' => $request->has('is_active')]);
        return redirect()->route('positions.index')->with('success', 'Posisi berhasil ditambahkan!');
    }

    public function update(Request $request, Position $position)
    {
        $request->validate(['name' => 'required|string|max:255|unique:positions,name,' . $position->id]);
        $position->update(['name' => $request->name, 'is_active' => $request->has('is_active')]);
        return redirect()->route('positions.index')->with('success', 'Posisi berhasil diperbarui!');
    }

    public function destroy(Position $position)
    {
        $position->delete();
        return redirect()->route('positions.index')->with('success', 'Posisi berhasil dihapus!');
    }
}
