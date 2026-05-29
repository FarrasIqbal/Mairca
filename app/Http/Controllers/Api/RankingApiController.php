<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Services\MaircaEngineService;
use Illuminate\Http\Request;

class RankingApiController extends Controller
{
    protected $maircaService;

    public function __construct(MaircaEngineService $maircaService)
    {
        $this->maircaService = $maircaService;
    }

    /**
     * Get real-time MAIRCA rankings for a position.
     */
    public function index(Request $request)
    {
        $request->validate([
            'position_id' => 'required|exists:positions,id'
        ]);

        $position = Position::findOrFail($request->position_id);

        // Run MAIRCA Engine calculation
        $result = $this->maircaService->calculate($position);

        if ($result === null) {
            return response()->json([
                'status' => 'success',
                'message' => 'Tidak ada kandidat di tahap evaluasi_spk atau kriteria belum ditentukan untuk posisi ini.',
                'data' => [
                    'position' => $position,
                    'ranked' => [],
                    'is_valid' => false
                ]
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'position' => $position,
                'ranked' => $result['ranked'],
                'is_valid' => $result['is_valid']
            ]
        ]);
    }
}
