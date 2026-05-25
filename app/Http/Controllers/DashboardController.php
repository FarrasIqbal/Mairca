<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Position;
use App\Models\InterviewSchedule;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // === Statistik Global ===
        $totalPositions    = Position::where('is_active', true)->count();
        $activeCandidates  = Candidate::whereNotIn('status', ['hired', 'rejected'])->count();
        $pendingEvaluations = Candidate::where('status', 'evaluasi_spk')->count();
        $hiredThisMonth    = Candidate::where('status', 'hired')
            ->whereMonth('updated_at', Carbon::now()->month)
            ->whereYear('updated_at', Carbon::now()->year)
            ->count();

        // === Distribusi Status Kandidat ===
        $statusCounts = Candidate::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // === Jadwal Wawancara Hari Ini ===
        $todaySchedulesQuery = InterviewSchedule::with(['candidate.position', 'interviewer'])
            ->whereDate('scheduled_at', Carbon::today())
            ->where('status', 'scheduled');

        if ($user->role === 'reviewer') {
            $todaySchedulesQuery->where('interviewer_id', $user->id);
        }

        $todaySchedules = $todaySchedulesQuery->orderBy('scheduled_at')->get();

        // === Jadwal Mendatang (7 hari ke depan) ===
        $upcomingSchedulesQuery = InterviewSchedule::with(['candidate.position', 'interviewer'])
            ->where('scheduled_at', '>', Carbon::today()->endOfDay())
            ->where('scheduled_at', '<=', Carbon::now()->addDays(7))
            ->where('status', 'scheduled');

        if ($user->role === 'reviewer') {
            $upcomingSchedulesQuery->where('interviewer_id', $user->id);
        }

        $upcomingSchedules = $upcomingSchedulesQuery->orderBy('scheduled_at')->take(5)->get();

        // === Kandidat Terbaru ===
        $recentCandidates = Candidate::with('position')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalPositions',
            'activeCandidates',
            'pendingEvaluations',
            'hiredThisMonth',
            'statusCounts',
            'todaySchedules',
            'upcomingSchedules',
            'recentCandidates'
        ));
    }
}
