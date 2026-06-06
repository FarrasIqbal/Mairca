<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Position;
use App\Models\PracticalTest;
use App\Models\TestQuestion;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminPracticalTestController extends Controller
{
    /**
     * Display index of practical tests and questions management.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Fetch Candidates in Tes Praktis stage
        $candidatesQuery = Candidate::with(['position', 'practicalTest'])
            ->where('status', 'tes_praktis');

        if ($user->role === 'reviewer') {
            $candidatesQuery->whereHas('position', function($q) use ($user) {
                $q->where('department', $user->department);
            });
        }

        if ($request->filled('search')) {
            $candidatesQuery->where('name', 'like', '%' . $request->search . '%');
        }

        $candidates = $candidatesQuery->latest()->get();

        // 2. Fetch positions and their custom questions
        if ($user->role === 'reviewer') {
            $positions = Position::where('is_active', true)
                ->where('department', $user->department)
                ->with('testQuestions')
                ->get();
        } else {
            $positions = Position::where('is_active', true)->with('testQuestions')->get();
        }

        // Active position selected for managing questions
        $activePositionId = $request->input('position_id', $positions->first()->id ?? null);
        $activePosition = $positions->firstWhere('id', $activePositionId);

        return view('practical_tests.index', compact('candidates', 'positions', 'activePositionId', 'activePosition'));
    }

    /**
     * Store a new custom question for a position.
     */
    public function storeQuestion(Request $request)
    {
        $request->validate([
            'position_id' => 'required|exists:positions,id',
            'question_text' => 'required|string',
            'placeholder_text' => 'nullable|string',
            'grading_guide' => 'nullable|string',
        ]);

        TestQuestion::create($request->all());

        return back()->with('success', 'Soal ujian berhasil ditambahkan!');
    }

    /**
     * Update an existing custom question.
     */
    public function updateQuestion(Request $request, TestQuestion $question)
    {
        $request->validate([
            'question_text' => 'required|string',
            'placeholder_text' => 'nullable|string',
            'grading_guide' => 'nullable|string',
        ]);

        $question->update($request->all());

        return back()->with('success', 'Soal ujian berhasil diperbarui!');
    }

    /**
     * Delete a custom question.
     */
    public function destroyQuestion(TestQuestion $question)
    {
        $question->delete();

        return back()->with('success', 'Soal ujian berhasil dihapus!');
    }

    /**
     * Show evaluation/grading sheet for a candidate.
     */
    public function evaluate(Candidate $candidate)
    {
        $candidate->load(['position', 'practicalTest']);
        $user = Auth::user();

        if ($user->role === 'reviewer' && ($candidate->position->department ?? '') !== $user->department) {
            return back()->with('error', 'Anda tidak memiliki hak akses untuk menilai kandidat di departemen lain.');
        }

        $test = $candidate->ensurePracticalTest();

        if (!$test->submitted_at) {
            return back()->with('error', 'Kandidat ini belum mengirimkan jawaban tes praktis.');
        }

        // Dynamically resolve questions text based on position database rules
        $position = $candidate->position;
        $customQuestions = $position->testQuestions;

        $questionsList = [];
        if ($customQuestions->isNotEmpty()) {
            foreach ($customQuestions as $q) {
                $questionsList[$q->id] = [
                    'text' => $q->question_text,
                    'guide' => $q->grading_guide ?? 'Review jawaban kandidat secara objektif sesuai kualifikasi posisi.',
                    'max_score' => $q->max_score ?? 100,
                ];
            }
        } else {
            // Fallback general static list with guidelines
            $positionName = strtolower($position->name ?? '');
            if (str_contains($positionName, 'seo') || str_contains($positionName, 'search engine')) {
                $questionsList = [
                    1 => [
                        'text' => 'Bagaimana metodologi Anda dalam melakukan audit teknis SEO pada website e-commerce yang memiliki ribuan halaman dengan masalah duplikasi konten?',
                        'guide' => 'Kunci Jawaban/Panduan: Kandidat harus menjelaskan penanganan canonical tags, robots.txt, URL query parameters (di GSC), penanganan paginasi, dan struktur link internal.',
                        'max_score' => 100,
                    ],
                    2 => [
                        'text' => 'Deskripsikan alur riset kata kunci (keyword research) Anda untuk menemukan peluang kata kunci yang berpotensi mendatangkan trafik transaksional berkualitas tinggi.',
                        'guide' => 'Kunci Jawaban/Panduan: Menggunakan riset volume pencarian (Ahrefs/SEMrush), mencakup riset intent pencarian (transactional vs informational intent), dan keyword clustering.',
                        'max_score' => 100,
                    ],
                    3 => [
                        'text' => 'Sebutkan 3 metrik utama di Google Search Console yang paling sering Anda pantau, dan jelaskan bagaimana Anda menindaklanjuti fluktuasi negatif dari masing-masing metrik tersebut.',
                        'guide' => 'Kunci Jawaban/Panduan: Memantau Clicks, Impressions, CTR, dan Average Position. Mampu menganalisis penurunan performa akibat pembaruan algoritma Google atau kompetitor.',
                        'max_score' => 100,
                    ],
                ];
            } elseif (str_contains($positionName, 'developer') || str_contains($positionName, 'programmer') || str_contains($positionName, 'engineer') || str_contains($positionName, 'tech')) {
                $questionsList = [
                    1 => [
                        'text' => 'Bagaimana Anda merancang arsitektur database yang efisien dan berskala besar untuk fitur real-time chat pada aplikasi berbasis web?',
                        'guide' => 'Kunci Jawaban/Panduan: Skema DB (Redis/Memcached untuk caching data aktif), penggunaan websockets (Socket.io), database indexing, dan pembagian partisi (sharding).',
                        'max_score' => 100,
                    ],
                    2 => [
                        'text' => 'Jelaskan perbedaan mendasar antara RESTful API dan GraphQL, serta berikan contoh skenario nyata di mana Anda akan memilih salah satunya dibanding yang lain.',
                        'guide' => 'Kunci Jawaban/Panduan: RESTful (over-fetching, multi-endpoint) vs GraphQL (single endpoint, flexible queries). Memilih REST untuk arsitektur sederhana/caching kuat, GraphQL untuk frontend kompleks.',
                        'max_score' => 100,
                    ],
                    3 => [
                        'text' => 'Bagaimana cara Anda mendeteksi, mendiagnosis, dan menyelesaikan bottleneck performa (seperti N+1 query) pada aplikasi berbasis PHP/Laravel?',
                        'guide' => 'Kunci Jawaban/Panduan: Mendeteksi via Laravel Telescope/Debugbar. Mengatasi N+1 query dengan eager loading (metode `with()` atau `load()`), dan menggunakan DB index.',
                        'max_score' => 100,
                    ],
                ];
            } else {
                $questionsList = [
                    1 => [
                        'text' => 'Jelaskan rencana kerja taktis dan strategis 30-60-90 hari pertama Anda jika diterima bergabung di posisi ini.',
                        'guide' => 'Kunci Jawaban/Panduan: Adaptasi & orientasi tim (30 hari), inisiasi/kontribusi mandiri (60 hari), inovasi & pencapaian nilai bisnis (90 hari).',
                        'max_score' => 100,
                    ],
                    2 => [
                        'text' => 'Bagaimana cara Anda menyikapi situasi kerja di mana Anda harus menyelesaikan tugas dengan tenggat waktu ketat, namun terjadi perubahan ruang lingkup kerja (scope creep) di tengah jalan?',
                        'guide' => 'Kunci Jawaban/Panduan: Menjelaskan komunikasi asertif kepada PM/klien, negosiasi ulang prioritas tugas (Iron Triangle), dan penyusunan ulang timeline secara realistis.',
                        'max_score' => 100,
                    ],
                    3 => [
                        'text' => 'Berikan satu contoh studi kasus nyata dari pengalaman kerja Anda sebelumnya di mana Anda berhasil memecahkan masalah kompleks atau konflik profesional secara objektif.',
                        'guide' => 'Kunci Jawaban/Panduan: Evaluasi terstruktur menggunakan kerangka STAR (Situation, Task, Action, Result) yang berorientasi pada penyelesaian masalah secara rasional dan damai.',
                        'max_score' => 100,
                    ],
                ];
            }
        }

        return view('practical_tests.evaluate', compact('candidate', 'test', 'questionsList'));
    }

    /**
     * Save manual grading and progress status.
     */
    public function storeEvaluation(Request $request, Candidate $candidate)
    {
        $request->validate([
            'score' => 'nullable|integer|min:0|max:100',
            'question_scores' => 'nullable|array',
            'reviewer_notes' => 'nullable|string',
            'status' => 'required|in:wawancara_hr,rejected,tes_praktis',
        ]);

        $user = Auth::user();
        if ($user->role === 'reviewer' && ($candidate->position->department ?? '') !== $user->department) {
            return back()->with('error', 'Anda tidak memiliki hak akses untuk menilai kandidat di departemen lain.');
        }

        $test = $candidate->ensurePracticalTest();

        // Dynamically resolve questions list to calculate max sums
        $position = $candidate->position;
        $customQuestions = $position->testQuestions;
        $questionsList = [];
        if ($customQuestions->isNotEmpty()) {
            foreach ($customQuestions as $q) {
                $questionsList[$q->id] = $q->max_score ?? 100;
            }
        } else {
            $questionsList = [1 => 100, 2 => 100, 3 => 100];
        }

        $earnedSum = 0;
        $maxSum = 0;
        $submittedScores = $request->input('question_scores', []);
        
        if (is_array($submittedScores) && count($submittedScores) > 0) {
            foreach ($questionsList as $qId => $maxScore) {
                $scoreInput = isset($submittedScores[$qId]) ? intval($submittedScores[$qId]) : 0;
                // Clip between 0 and maxScore
                $scoreInput = max(0, min($maxScore, $scoreInput));
                $earnedSum += $scoreInput;
                $maxSum += $maxScore;
                $submittedScores[$qId] = $scoreInput; // clean value
            }
            $newScore = $maxSum > 0 ? intval(round(($earnedSum / $maxSum) * 100)) : 0;
        } else {
            $newScore = intval($request->input('score', 0));
        }

        $oldScore = $test->score;
        $isSuitable = $newScore >= 70; // passing score is 70

        // Update practical test scores and notes
        $test->update([
            'score' => $newScore,
            'question_scores' => $submittedScores,
            'reviewer_notes' => $request->reviewer_notes,
            'is_suitable' => $isSuitable,
        ]);

        // Update Candidate stage and log it
        $oldStatus = $candidate->status;
        $newStatus = $request->status;

        $candidate->update([
            'status' => $newStatus,
        ]);

        // Create detailed audit log
        $statusLabels = Candidate::$statuses;
        ActivityLog::create([
            'candidate_id' => $candidate->id,
            'user_id' => Auth::id(),
            'action' => 'practical_test_evaluated',
            'description' => 'Evaluasi manual tes praktis dinilai oleh ' . Auth::user()->name . '. Skor: ' . $newScore . '. ' . ($request->reviewer_notes ? 'Catatan: ' . $request->reviewer_notes : ''),
            'old_value' => $oldScore,
            'new_value' => $newScore,
        ]);

        if ($oldStatus !== $newStatus) {
            ActivityLog::create([
                'candidate_id' => $candidate->id,
                'user_id' => Auth::id(),
                'action' => 'status_changed',
                'description' => 'Status kandidat diubah dari "' . ($statusLabels[$oldStatus] ?? $oldStatus) . '" ke "' . ($statusLabels[$newStatus] ?? $newStatus) . '" oleh ' . Auth::user()->name,
                'old_value' => $oldStatus,
                'new_value' => $newStatus,
            ]);
        }

        return redirect()->route('admin.practical-tests.index')
            ->with('success', 'Penilaian manual kandidat ' . $candidate->name . ' berhasil disimpan!');
    }

    /**
     * Update the practical test duration for a position.
     */
    public function updatePositionDuration(Request $request, Position $position)
    {
        $request->validate([
            'test_duration' => 'required|integer|min:5|max:480',
        ]);

        $position->update([
            'test_duration' => $request->test_duration,
        ]);

        return back()->with('success', 'Durasi waktu pengerjaan tes untuk posisi ' . $position->name . ' berhasil diperbarui!');
    }

    /**
     * Extend practical test link expiration date for a candidate.
     */
    public function extendTestLink(Candidate $candidate)
    {
        $test = $candidate->ensurePracticalTest();
        
        $test->update([
            'expires_at' => \Carbon\Carbon::now()->addDays(3),
        ]);

        ActivityLog::create([
            'candidate_id' => $candidate->id,
            'user_id' => Auth::id(),
            'action' => 'practical_test_extended',
            'description' => 'Tautan ujian praktis pelamar diperpanjang selama 3 hari oleh ' . Auth::user()->name,
        ]);

        return back()->with('success', 'Tautan ujian kandidat ' . $candidate->name . ' berhasil diperpanjang 3 hari ke depan!');
    }
}
