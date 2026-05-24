<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Services\MaircaEngineService;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    public function index(Request $request, MaircaEngineService $maircaService)
    {
        $positions = Position::where('is_active', true)->get();
        $selectedPosition = null;
        $result = null;

        if ($request->has('position_id') && $request->position_id != '') {
            $selectedPosition = Position::findOrFail($request->position_id);

            // Panggil Engine MAIRCA
            $result = $maircaService->calculate($selectedPosition);
        }

        return view('rankings.index', compact('positions', 'selectedPosition', 'result'));
    }
}
