<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationApiController extends Controller
{
    /**
     * Get candidate evaluations grid for a position.
     */
    public function index(Request $request)
    {
        $request->validate([
            'position_id' => 'required|exists:positions,id'
        ]);

        $user = Auth::user();
        $interviewType = ($user->role === 'hr') ? 'hr' : 'user';

        $position = Position::findOrFail($request->position_id);
        $criteria = $position->criteria;
        
        // Fetch candidates in evaluasi_spk stage
        $candidates = $position->candidates()
            ->where('status', 'evaluasi_spk')
            ->get();

        // Get evaluations entered by the current reviewer
        $evaluations = Evaluation::where('user_id', $user->id)
            ->where('interview_type', $interviewType)
            ->whereIn('candidate_id', $candidates->pluck('id'))
            ->get();

        $existingScores = [];
        foreach ($evaluations as $eval) {
            $existingScores[$eval->candidate_id][$eval->criteria_id] = $eval->score;
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'position' => $position,
                'criteria' => $criteria,
                'candidates' => $candidates,
                'existing_scores' => $existingScores,
                'interview_type' => $interviewType
            ]
        ]);
    }

    /**
     * Store bulk evaluations.
     */
    public function storeBulk(Request $request)
    {
        $request->validate([
            'scores' => 'required|array',
            'interview_type' => 'nullable|in:hr,user',
        ]);

        $scores = $request->input('scores');
        $interviewType = $request->input('interview_type');
        
        if (!$interviewType) {
            $interviewType = (Auth::user()->role === 'hr') ? 'hr' : 'user';
        }
        
        $userId = Auth::id();
        $count = 0;

        foreach ($scores as $candidateId => $criteriaScores) {
            if (!is_array($criteriaScores)) {
                continue;
            }
            foreach ($criteriaScores as $criteriaId => $score) {
                if ($score !== null && $score !== '') {
                    Evaluation::updateOrCreate(
                        [
                            'candidate_id'   => $candidateId,
                            'criteria_id'    => $criteriaId,
                            'user_id'        => $userId,
                            'interview_type' => $interviewType,
                        ],
                        ['score' => intval($score)]
                    );
                    $count++;
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => "Berhasil menyimpan {$count} nilai evaluasi!"
        ]);
    }
}
