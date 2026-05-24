<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\Position;
use Illuminate\Http\Request;

class CriteriaController extends Controller
{
    public function index(Position $position)
    {
        // Ambil semua kriteria yang terikat dengan posisi ini
        $criteria = $position->criteria()->get();

        // Hitung total bobot (harus 1.0 nanti untuk bisa lanjut penilaian)
        $totalWeight = $criteria->sum('weight');

        return view('criteria.index', compact('position', 'criteria', 'totalWeight'));
    }

    public function store(Request $request, Position $position)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:benefit,cost',
            'weight' => 'required|numeric|min:0.01|max:1.00',
        ]);

        // Cek pengaman SQA: akumulasi bobot baru tidak boleh melebihi 1.0
        $currentTotal = $position->criteria()->sum('weight');
        if (($currentTotal + $request->weight) > 1.001) { // 1.001 antisipasi floating-point PHP
            return redirect()->back()->with('error', 'Gagal Simpan! Akumulasi bobot kriteria pada posisi ini akan melebihi batas mutlak 1.00.');
        }

        $position->criteria()->create([
            'name' => $request->name,
            'type' => $request->type,
            'weight' => $request->weight,
        ]);

        return redirect()->route('positions.criteria.index', $position->id)->with('success', 'Kriteria berhasil ditambahkan!');
    }

    public function update(Request $request, Position $position, Criteria $criterion)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:benefit,cost',
            'weight' => 'required|numeric|min:0.01|max:1.00',
        ]);

        // Hitung ulang akumulasi di luar bobot kriteria yang sedang di-edit saat ini
        $currentTotal = $position->criteria()->where('id', '!=', $criterion->id)->sum('weight');
        if (($currentTotal + $request->weight) > 1.001) {
            return redirect()->back()->with('error', 'Gagal Update! Perubahan bobot ini membuat akumulasi total melebihi 1.00.');
        }

        $criterion->update([
            'name' => $request->name,
            'type' => $request->type,
            'weight' => $request->weight,
        ]);

        return redirect()->route('positions.criteria.index', $position->id)->with('success', 'Kriteria berhasil diperbarui!');
    }

    public function destroy(Position $position, Criteria $criterion)
    {
        $criterion->delete();
        return redirect()->route('positions.criteria.index', $position->id)->with('success', 'Kriteria berhasil dihapus!');
    }
}
