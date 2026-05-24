<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\Candidate;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Hitung total semua lowongan/posisi (baik aktif maupun tutup)
        $totalPositions = Position::count();

        // 2. Hitung kandidat aktif (yang statusnya BUKAN hired atau rejected)
        $activeCandidates = Candidate::whereNotIn('status', ['hired', 'rejected'])->count();

        // 3. Hitung kandidat yang sedang menunggu dinilai di tabel evaluasi MAIRCA
        $pendingEvaluations = Candidate::where('status', 'evaluasi_spk')->count();

        return view('dashboard', compact('totalPositions', 'activeCandidates', 'pendingEvaluations'));
    }
}
