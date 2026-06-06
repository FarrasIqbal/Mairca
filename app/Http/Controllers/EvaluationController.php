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
        $user = Auth::user();

        // Tentukan interview_type berdasarkan role user
        $interviewType = ($user->role === 'hr') ? 'hr' : 'user';

        // Ambil daftar posisi yang aktif untuk filter dropdown, batasi jika user adalah reviewer
        if ($user->role === 'reviewer') {
            $positions = Position::where('is_active', true)
                ->where('department', $user->department)
                ->get();
        } else {
            $positions = Position::where('is_active', true)->get();
        }

        $selectedPosition = null;
        $candidates       = collect();
        $criteria         = collect();
        $existingScores   = [];

        if ($request->has('position_id') && $request->position_id != '') {
            $selectedPosition = Position::findOrFail($request->position_id);

            // Validasi hak akses reviewer ke departemen posisi tersebut
            if ($user->role === 'reviewer' && $selectedPosition->department !== $user->department) {
                return redirect()->route('evaluations.index')->with('error', 'Anda tidak memiliki hak akses untuk menilai posisi di departemen lain.');
            }

            $criteria         = $selectedPosition->criteria;

            // Tarik kandidat yang statusnya 'evaluasi_spk'
            $candidates = $selectedPosition->candidates()
                ->where('status', 'evaluasi_spk')
                ->get();

            // Ambil data nilai yang sudah diinput oleh reviewer ini sebelumnya
            $evaluations = Evaluation::where('user_id', Auth::id())
                ->where('interview_type', $interviewType)
                ->whereIn('candidate_id', $candidates->pluck('id'))
                ->get();

            foreach ($evaluations as $eval) {
                $existingScores[$eval->candidate_id][$eval->criteria_id] = $eval->score;
            }
        }

        return view('evaluations.index', compact(
            'positions',
            'selectedPosition',
            'candidates',
            'criteria',
            'existingScores',
            'interviewType'
        ));
    }

    public function storeBulk(Request $request)
    {
        $scores        = $request->input('scores');
        $interviewType = $request->input('interview_type', 'hr');
        $userId        = Auth::id();
        $user          = Auth::user();

        if (!$scores) {
            return redirect()->back()->with('error', 'Tidak ada data yang diproses.');
        }

        // Validasi hak akses reviewer ke departemen kandidat
        if ($user->role === 'reviewer') {
            $candidateIds = array_keys($scores);
            $unauthorized = \App\Models\Candidate::whereIn('id', $candidateIds)
                ->whereHas('position', function($q) use ($user) {
                    $q->where('department', '!=', $user->department);
                })->exists();

            if ($unauthorized) {
                return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk menilai kandidat di departemen lain.');
            }
        }

        foreach ($scores as $candidateId => $criteriaScores) {
            foreach ($criteriaScores as $criteriaId => $score) {
                if ($score !== null && $score !== '') {
                    Evaluation::updateOrCreate(
                        [
                            'candidate_id'   => $candidateId,
                            'criteria_id'    => $criteriaId,
                            'user_id'        => $userId,
                            'interview_type' => $interviewType,
                        ],
                        ['score' => $score]
                    );
                }
            }
        }

        return redirect()->back()
            ->with('success', 'Penilaian berhasil disimpan! Anda bisa mengubahnya kapan saja.');
    }
}
