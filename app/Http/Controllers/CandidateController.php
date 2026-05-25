<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Position;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function index(Request $request)
    {
        $query = Candidate::with('position')->latest();

        if ($request->filled('position_id')) {
            $query->where('position_id', $request->position_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $candidates = $query->paginate(10)->withQueryString();
        $positions  = Position::where('is_active', true)->get();
        $statuses   = Candidate::$statuses;

        return view('candidates.index', compact('candidates', 'positions', 'statuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'position_id' => 'required|exists:positions,id',
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:candidates,email',
            'phone'       => 'nullable|string|max:20',
        ]);

        Candidate::create([
            'position_id' => $request->position_id,
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'status'      => 'berkas',
        ]);

        return redirect()->route('candidates.index')
            ->with('success', 'Kandidat berhasil ditambahkan ke sistem!');
    }

    public function update(Request $request, Candidate $candidate)
    {
        $request->validate([
            'position_id' => 'required|exists:positions,id',
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:candidates,email,' . $candidate->id,
            'phone'       => 'nullable|string|max:20',
            'status'      => 'required|in:berkas,tes_praktis,wawancara_hr,wawancara_user,evaluasi_spk,hired,rejected',
        ]);

        $candidate->update([
            'position_id' => $request->position_id,
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'status'      => $request->status,
        ]);

        return redirect()->route('candidates.index')
            ->with('success', 'Data & Status kandidat berhasil diperbarui!');
    }

    public function destroy(Candidate $candidate)
    {
        $candidate->delete();
        return redirect()->route('candidates.index')
            ->with('success', 'Data kandidat berhasil dihapus!');
    }
}
