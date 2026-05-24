<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
    public function index(Request $request)
    {
        // Ambil daftar posisi yang aktif untuk filter dropdown
        $positions = Position::where('is_active', true)->get();

        $selectedPosition = null;
        $candidates = collect();
        $criteria = collect();
        $existingScores = [];

        // Jika user sudah memilih posisi dari dropdown
        if ($request->has('position_id') && $request->position_id != '') {
            $selectedPosition = Position::findOrFail($request->position_id);
            $criteria = $selectedPosition->criteria;

            // HANYA tarik kandidat yang statusnya 'evaluasi_spk'
            $candidates = $selectedPosition->candidates()->where('status', 'evaluasi_spk')->get();

            // Ambil data nilai yang *sudah pernah* diinput oleh Reviewer ini sebelumnya (agar bisa dicicil/diedit)
            $evaluations = Evaluation::where('user_id', Auth::id())
                ->whereIn('candidate_id', $candidates->pluck('id'))
                ->get();

            // Format ulang data nilai ke array 2 dimensi [candidate_id][criteria_id] = score
            foreach ($evaluations as $eval) {
                $existingScores[$eval->candidate_id][$eval->criteria_id] = $eval->score;
            }
        }

        return view('evaluations.index', compact('positions', 'selectedPosition', 'candidates', 'criteria', 'existingScores'));
    }

    public function storeBulk(Request $request)
    {
        $scores = $request->input('scores'); // Format Array HTML: scores[candidate_id][criteria_id]
        $userId = Auth::id();

        if (!$scores) {
            return redirect()->back()->with('error', 'Tidak ada data yang diproses.');
        }

        // Looping untuk menyimpan/mengupdate data ke tabel pivot evaluations
        foreach ($scores as $candidateId => $criteriaScores) {
            foreach ($criteriaScores as $criteriaId => $score) {
                // Hanya simpan jika input tidak kosong
                if ($score !== null) {
                    Evaluation::updateOrCreate(
                        [
                            'candidate_id' => $candidateId,
                            'criteria_id' => $criteriaId,
                            'user_id' => $userId,
                        ],
                        [
                            'score' => $score
                        ]
                    );
                }
            }
        }

        return redirect()->back()->with('success', 'Evaluasi nilai berhasil disimpan! Anda bisa mengubahnya lagi kapan saja.');
    }
}
