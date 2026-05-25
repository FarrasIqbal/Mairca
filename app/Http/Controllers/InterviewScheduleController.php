<?php

namespace App\Http\Controllers;

use App\Models\InterviewSchedule;
use App\Models\Candidate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterviewScheduleController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = InterviewSchedule::with(['candidate.position', 'interviewer'])
            ->orderBy('scheduled_at', 'asc');

        // Reviewer hanya melihat jadwal miliknya sendiri
        if ($user->role === 'reviewer') {
            $query->where('interviewer_id', $user->id);
        }

        // Filter berdasarkan tipe jika dipilih
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter berdasarkan status jika dipilih
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $schedules = $query->paginate(15)->withQueryString();

        return view('interviews.index', compact('schedules'));
    }

    public function create(Request $request)
    {
        $candidates = Candidate::with('position')
            ->whereNotIn('status', ['hired', 'rejected'])
            ->orderBy('name')
            ->get();

        $interviewers = User::orderBy('name')->get();
        $selectedCandidateId = $request->candidate_id;

        return view('interviews.create', compact('candidates', 'interviewers', 'selectedCandidateId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'candidate_id'   => 'required|exists:candidates,id',
            'interviewer_id' => 'required|exists:users,id',
            'type'           => 'required|in:hr,user',
            'scheduled_at'   => 'required|date|after:now',
            'zoom_link'      => 'nullable|url|max:500',
            'notes'          => 'nullable|string|max:1000',
        ], [
            'candidate_id.required'   => 'Kandidat harus dipilih.',
            'interviewer_id.required' => 'Pewawancara harus dipilih.',
            'type.required'           => 'Tipe wawancara harus dipilih.',
            'scheduled_at.required'   => 'Tanggal & waktu harus diisi.',
            'scheduled_at.after'      => 'Jadwal harus di masa mendatang.',
            'zoom_link.url'           => 'Link Zoom tidak valid.',
        ]);

        $schedule = InterviewSchedule::create($validated);

        // Update status kandidat sesuai tipe wawancara
        $candidate = Candidate::find($validated['candidate_id']);
        if ($validated['type'] === 'hr' && $candidate->status === 'tes_praktis') {
            $candidate->update(['status' => 'wawancara_hr']);
        } elseif ($validated['type'] === 'user' && $candidate->status === 'wawancara_hr') {
            $candidate->update(['status' => 'wawancara_user']);
        }

        return redirect()->route('interviews.index')
            ->with('success', 'Jadwal wawancara berhasil dibuat!');
    }

    public function edit(InterviewSchedule $interview)
    {
        $candidates  = Candidate::with('position')->whereNotIn('status', ['hired', 'rejected'])->orderBy('name')->get();
        $interviewers = User::orderBy('name')->get();

        return view('interviews.edit', compact('interview', 'candidates', 'interviewers'));
    }

    public function update(Request $request, InterviewSchedule $interview)
    {
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

        return redirect()->route('interviews.index')
            ->with('success', 'Jadwal wawancara berhasil diperbarui!');
    }

    public function destroy(InterviewSchedule $interview)
    {
        $interview->delete();
        return redirect()->route('interviews.index')
            ->with('success', 'Jadwal wawancara berhasil dihapus.');
    }
}
