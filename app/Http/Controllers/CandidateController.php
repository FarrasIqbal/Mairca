<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Position;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function index()
    {
        // Ambil data kandidat beserta nama posisinya (Eager Loading untuk performa)
        $candidates = Candidate::with('position')->latest()->paginate(10);

        // Ambil posisi yang aktif saja untuk dropdown di modal Create/Edit
        $positions = Position::where('is_active', true)->get();

        return view('candidates.index', compact('candidates', 'positions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'position_id' => 'required|exists:positions,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:candidates,email',
        ]);

        Candidate::create([
            'position_id' => $request->position_id,
            'name' => $request->name,
            'email' => $request->email,
            'status' => 'berkas', // Status awal default
        ]);

        return redirect()->route('candidates.index')->with('success', 'Kandidat berhasil ditambahkan ke sistem!');
    }

    public function update(Request $request, Candidate $candidate)
    {
        $request->validate([
            'position_id' => 'required|exists:positions,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:candidates,email,' . $candidate->id,
            'status' => 'required|in:berkas,tes_praktis,evaluasi_spk,hired,rejected',
        ]);

        $candidate->update([
            'position_id' => $request->position_id,
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,
        ]);

        return redirect()->route('candidates.index')->with('success', 'Data & Status kandidat berhasil diperbarui!');
    }

    public function destroy(Candidate $candidate)
    {
        $candidate->delete();
        return redirect()->route('candidates.index')->with('success', 'Data kandidat berhasil dihapus!');
    }
}
