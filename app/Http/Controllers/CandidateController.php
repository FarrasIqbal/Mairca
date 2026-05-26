<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\ActivityLog;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    public function show(Candidate $candidate)
    {
        $candidate->load([
            'position',
            'evaluations.criteria',
            'evaluations.user',
            'interviewSchedules.interviewer',
            'activityLogs.user',
        ]);

        $statuses = Candidate::$statuses;

        return view('candidates.show', compact('candidate', 'statuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'position_id' => 'required|exists:positions,id',
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:candidates,email',
            'phone'       => 'nullable|string|max:20',
            'resume'      => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $data = [
            'position_id' => $request->position_id,
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'status'      => 'berkas',
        ];

        if ($request->hasFile('resume')) {
            $data['resume_path'] = $request->file('resume')->store('resumes', 'public');
        }

        $candidate = Candidate::create($data);

        ActivityLog::create([
            'candidate_id' => $candidate->id,
            'user_id'      => Auth::id(),
            'action'       => 'created',
            'description'  => 'Kandidat didaftarkan ke sistem oleh ' . Auth::user()->name,
            'new_value'    => 'berkas',
        ]);

        if ($request->hasFile('resume')) {
            ActivityLog::create([
                'candidate_id' => $candidate->id,
                'user_id'      => Auth::id(),
                'action'       => 'resume_uploaded',
                'description'  => 'Resume diunggah oleh ' . Auth::user()->name,
            ]);
        }

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
            'resume'      => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $oldStatus = $candidate->status;

        $data = [
            'position_id' => $request->position_id,
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'status'      => $request->status,
        ];

        if ($request->hasFile('resume')) {
            // Hapus resume lama jika ada
            if ($candidate->resume_path) {
                Storage::disk('public')->delete($candidate->resume_path);
            }
            $data['resume_path'] = $request->file('resume')->store('resumes', 'public');
        }

        $candidate->update($data);

        // Log perubahan status
        if ($oldStatus !== $request->status) {
            $statusLabels = Candidate::$statuses;
            ActivityLog::create([
                'candidate_id' => $candidate->id,
                'user_id'      => Auth::id(),
                'action'       => 'status_changed',
                'description'  => 'Status diubah dari "' . ($statusLabels[$oldStatus] ?? $oldStatus) . '" ke "' . ($statusLabels[$request->status] ?? $request->status) . '" oleh ' . Auth::user()->name,
                'old_value'    => $oldStatus,
                'new_value'    => $request->status,
            ]);
        }

        // Log upload resume baru
        if ($request->hasFile('resume')) {
            ActivityLog::create([
                'candidate_id' => $candidate->id,
                'user_id'      => Auth::id(),
                'action'       => 'resume_uploaded',
                'description'  => 'Resume diperbarui oleh ' . Auth::user()->name,
            ]);
        }

        return back()
            ->with('success', 'Data & Status kandidat berhasil diperbarui!');
    }

    public function downloadResume(Candidate $candidate)
    {
        if (!$candidate->resume_path || !Storage::disk('public')->exists($candidate->resume_path)) {
            return back()->with('error', 'Resume tidak ditemukan.');
        }

        $fileName = 'Resume_' . str_replace(' ', '_', $candidate->name) . '.' . pathinfo($candidate->resume_path, PATHINFO_EXTENSION);
        return Storage::disk('public')->download($candidate->resume_path, $fileName);
    }

    public function destroy(Candidate $candidate)
    {
        $candidate->delete();
        return redirect()->route('candidates.index')
            ->with('success', 'Data kandidat berhasil dihapus!');
    }
}
