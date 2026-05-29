<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\ActivityLog;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CandidateApiController extends Controller
{
    /**
     * List all candidates with filters.
     */
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

        // Return paginated list or get all if pagination is disabled
        $perPage = $request->input('per_page', 10);
        $candidates = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $candidates
        ]);
    }

    /**
     * Store a new candidate (upload resume).
     */
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

        return response()->json([
            'status' => 'success',
            'message' => 'Kandidat berhasil ditambahkan ke sistem!',
            'data' => $candidate->load('position')
        ], 201);
    }

    /**
     * Show candidate details including history and evaluation logs.
     */
    public function show($id)
    {
        $candidate = Candidate::with([
            'position',
            'evaluations.criteria',
            'evaluations.user',
            'interviewSchedules.interviewer',
            'activityLogs.user',
            'practicalTest'
        ])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $candidate
        ]);
    }

    /**
     * Update candidate details and pipeline status.
     */
    public function update(Request $request, $id)
    {
        $candidate = Candidate::findOrFail($id);

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
            if ($candidate->resume_path) {
                Storage::disk('public')->delete($candidate->resume_path);
            }
            $data['resume_path'] = $request->file('resume')->store('resumes', 'public');
        }

        $candidate->update($data);

        // Log status change
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

        // Log resume update
        if ($request->hasFile('resume')) {
            ActivityLog::create([
                'candidate_id' => $candidate->id,
                'user_id'      => Auth::id(),
                'action'       => 'resume_uploaded',
                'description'  => 'Resume diperbarui oleh ' . Auth::user()->name,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data & Status kandidat berhasil diperbarui!',
            'data' => $candidate->load('position')
        ]);
    }

    /**
     * Delete candidate.
     */
    public function destroy($id)
    {
        $candidate = Candidate::findOrFail($id);
        
        if ($candidate->resume_path) {
            Storage::disk('public')->delete($candidate->resume_path);
        }
        
        $candidate->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data kandidat berhasil dihapus!'
        ]);
    }

    /**
     * Download or get resume file path.
     */
    public function downloadResume($id)
    {
        $candidate = Candidate::findOrFail($id);

        if (!$candidate->resume_path || !Storage::disk('public')->exists($candidate->resume_path)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Resume tidak ditemukan.'
            ], 404);
        }

        $url = asset('storage/' . $candidate->resume_path);
        
        return response()->json([
            'status' => 'success',
            'data' => [
                'resume_url' => $url,
                'file_name' => 'Resume_' . str_replace(' ', '_', $candidate->name) . '.' . pathinfo($candidate->resume_path, PATHINFO_EXTENSION)
            ]
        ]);
    }
}
