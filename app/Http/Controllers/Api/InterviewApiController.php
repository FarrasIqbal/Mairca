<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InterviewSchedule;
use App\Models\Candidate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterviewApiController extends Controller
{
    /**
     * List all interview schedules.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = InterviewSchedule::with(['candidate.position', 'interviewer'])
            ->orderBy('scheduled_at', 'asc');

        // Reviewer can only see their own assigned interviews
        if ($user->role === 'reviewer') {
            $query->where('interviewer_id', $user->id);
        }

        // Apply filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->input('per_page', 15);
        $schedules = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $schedules
        ]);
    }

    /**
     * Store a new interview schedule.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'candidate_id'   => 'required|exists:candidates,id',
            'interviewer_id' => 'required|exists:users,id',
            'type'           => 'required|in:hr,user',
            'scheduled_at'   => 'required|date|after:now',
            'zoom_link'      => 'nullable|url|max:500',
            'notes'          => 'nullable|string|max:1000',
        ]);

        $schedule = InterviewSchedule::create($validated);

        // Update candidate status based on interview type
        $candidate = Candidate::find($validated['candidate_id']);
        if ($validated['type'] === 'hr' && $candidate->status === 'tes_praktis') {
            $candidate->update(['status' => 'wawancara_hr']);
        } elseif ($validated['type'] === 'user' && $candidate->status === 'wawancara_hr') {
            $candidate->update(['status' => 'wawancara_user']);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Jadwal wawancara berhasil dibuat!',
            'data' => $schedule->load(['candidate', 'interviewer'])
        ], 201);
    }

    /**
     * Update an interview schedule.
     */
    public function update(Request $request, $id)
    {
        $interview = InterviewSchedule::findOrFail($id);

        $validated = $request->validate([
            'candidate_id'   => 'required|exists:candidates,id',
            'interviewer_id' => 'required|exists:users,id',
            'type'           => 'required|in:hr,user',
            'scheduled_at'   => 'required|date',
            'zoom_link'      => 'nullable|url|max:500',
            'notes'          => 'nullable|string|max:1000',
            'status'         => 'required|in:scheduled,completed,cancelled',
        ]);

        $interview->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Jadwal wawancara berhasil diperbarui!',
            'data' => $interview->load(['candidate', 'interviewer'])
        ]);
    }

    /**
     * Delete an interview schedule.
     */
    public function destroy($id)
    {
        $interview = InterviewSchedule::findOrFail($id);
        $interview->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Jadwal wawancara berhasil dihapus.'
        ]);
    }
}
