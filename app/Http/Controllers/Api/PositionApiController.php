<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\Evaluation;
use App\Services\MaircaEngineService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PositionApiController extends Controller
{
    protected $maircaService;

    public function __construct(MaircaEngineService $maircaService)
    {
        $this->maircaService = $maircaService;
    }

    /**
     * List all positions.
     */
    public function index()
    {
        $positions = Position::withCount(['criteria', 'candidates'])->latest()->get();
        return response()->json([
            'status' => 'success',
            'data' => $positions
        ]);
    }

    /**
     * Store a new position.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:positions,name',
            'is_active' => 'nullable|boolean'
        ]);

        $position = Position::create([
            'name' => $request->name,
            'is_active' => $request->has('is_active') ? $request->is_active : true,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Posisi berhasil ditambahkan!',
            'data' => $position
        ], 201);
    }

    /**
     * Show detailed position center including criteria, candidates, and real-time MAIRCA ranking.
     */
    public function show($id)
    {
        $position = Position::findOrFail($id);

        // 1. Data Kriteria & Total Bobot
        $criteria = $position->criteria()->get();
        $totalWeight = $criteria->sum('weight');

        // 2. Data Semua Kandidat untuk Pipeline
        $allCandidates = $position->candidates()->latest()->get();

        // 3. Data Kandidat Khusus Tahap Evaluasi SPK
        $evalCandidates = $position->candidates()->where('status', 'evaluasi_spk')->get();

        // 4. Ambil Skor Eksisting yang pernah diinput Reviewer/User saat ini
        $userId = Auth::id();
        $evaluations = Evaluation::where('user_id', $userId)
            ->whereIn('candidate_id', $evalCandidates->pluck('id'))
            ->get();

        $existingScores = [];
        foreach ($evaluations as $eval) {
            $existingScores[$eval->candidate_id][$eval->criteria_id] = $eval->score;
        }

        // 5. Jalankan MAIRCA Engine secara Real-Time
        $maircaResult = $this->maircaService->calculate($position);

        return response()->json([
            'status' => 'success',
            'data' => [
                'position' => $position,
                'criteria' => $criteria,
                'total_weight' => $totalWeight,
                'all_candidates' => $allCandidates,
                'eval_candidates' => $evalCandidates,
                'existing_scores' => $existingScores,
                'mairca_result' => $maircaResult
            ]
        ]);
    }

    /**
     * Update an existing position.
     */
    public function update(Request $request, $id)
    {
        $position = Position::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:positions,name,' . $position->id,
            'is_active' => 'nullable|boolean'
        ]);

        $position->update([
            'name' => $request->name,
            'is_active' => $request->has('is_active') ? $request->is_active : $position->is_active,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Posisi berhasil diperbarui!',
            'data' => $position
        ]);
    }

    /**
     * Delete a position.
     */
    public function destroy($id)
    {
        $position = Position::findOrFail($id);
        $position->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Posisi berhasil dihapus!'
        ]);
    }
}
