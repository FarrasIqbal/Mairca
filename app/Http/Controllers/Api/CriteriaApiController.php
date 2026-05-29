<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Criteria;
use App\Models\Position;
use Illuminate\Http\Request;

class CriteriaApiController extends Controller
{
    /**
     * List all criteria for a position.
     */
    public function index($positionId)
    {
        $position = Position::findOrFail($positionId);
        $criteria = $position->criteria()->get();
        $totalWeight = $criteria->sum('weight');

        return response()->json([
            'status' => 'success',
            'data' => [
                'position' => $position,
                'criteria' => $criteria,
                'total_weight' => $totalWeight
            ]
        ]);
    }

    /**
     * Store a new criterion for a position.
     */
    public function store(Request $request, $positionId)
    {
        $position = Position::findOrFail($positionId);

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:benefit,cost',
            'weight' => 'required|numeric|min:0.01|max:1.00',
        ]);

        $currentTotal = $position->criteria()->sum('weight');
        if (($currentTotal + $request->weight) > 1.001) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Simpan! Akumulasi bobot kriteria pada posisi ini akan melebihi batas mutlak 1.00.'
            ], 422);
        }

        $criterion = $position->criteria()->create([
            'name' => $request->name,
            'type' => $request->type,
            'weight' => $request->weight,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kriteria berhasil ditambahkan!',
            'data' => $criterion
        ], 201);
    }

    /**
     * Update an existing criterion.
     */
    public function update(Request $request, $positionId, $criterionId)
    {
        $position = Position::findOrFail($positionId);
        $criterion = $position->criteria()->findOrFail($criterionId);

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:benefit,cost',
            'weight' => 'required|numeric|min:0.01|max:1.00',
        ]);

        $currentTotal = $position->criteria()->where('id', '!=', $criterion->id)->sum('weight');
        if (($currentTotal + $request->weight) > 1.001) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Update! Perubahan bobot ini membuat akumulasi total melebihi 1.00.'
            ], 422);
        }

        $criterion->update([
            'name' => $request->name,
            'type' => $request->type,
            'weight' => $request->weight,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kriteria berhasil diperbarui!',
            'data' => $criterion
        ]);
    }

    /**
     * Delete a criterion.
     */
    public function destroy($positionId, $criterionId)
    {
        $position = Position::findOrFail($positionId);
        $criterion = $position->criteria()->findOrFail($criterionId);
        $criterion->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Kriteria berhasil dihapus!'
        ]);
    }
}
