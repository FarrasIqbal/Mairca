<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PracticalTest;
use App\Models\Candidate;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PublicTestApiController extends Controller
{
    /**
     * Get candidate practical test page information by token.
     */
    public function showTest($token)
    {
        $test = PracticalTest::where('token', $token)->first();

        if (!$test) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token ujian tidak valid.'
            ], 404);
        }

        $candidate = $test->candidate;

        if ($test->submitted_at) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'state' => 'submitted',
                    'candidate_name' => $candidate->name,
                    'submitted_at' => $test->submitted_at
                ]
            ]);
        }

        // Check if link expired
        if ($test->expires_at && Carbon::now()->greaterThan($test->expires_at)) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'state' => 'expired',
                    'candidate_name' => $candidate->name,
                    'expires_at' => $test->expires_at
                ]
            ]);
        }

        $position = $candidate->position;
        
        // Start test timer on first fetch
        if (!$test->started_at) {
            $test->update([
                'started_at' => Carbon::now()
            ]);
        }

        // Calculate timer remaining seconds
        $durationMinutes = $position->test_duration ?? 60;
        $durationSeconds = $durationMinutes * 60;
        $elapsedSeconds = Carbon::now()->timestamp - $test->started_at->timestamp;
        
        // Auto-submit if time has already run out
        if ($elapsedSeconds >= $durationSeconds) {
            $test->update([
                'submitted_at' => Carbon::now(),
                'score' => 0,
                'is_suitable' => false,
                'answers' => [],
            ]);
            
            ActivityLog::create([
                'candidate_id' => $candidate->id,
                'action' => 'practical_test_submitted',
                'description' => 'Sistem otomatis mengunci lembar jawaban karena batas waktu pengerjaan telah habis.',
                'new_value' => 0,
            ]);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'state' => 'submitted_expired',
                    'candidate_name' => $candidate->name,
                    'message' => 'Waktu pengerjaan telah habis. Jawaban dikunci otomatis.'
                ]
            ]);
        }

        $remainingSeconds = max(0, $durationSeconds - $elapsedSeconds);
        $dbQuestions = $position->testQuestions ?? collect();

        $questions = [];
        if ($dbQuestions->isNotEmpty()) {
            foreach ($dbQuestions as $q) {
                $questions[] = [
                    'id' => $q->id,
                    'text' => $q->question_text,
                    'placeholder' => $q->placeholder_text ?? 'Tuliskan jawaban lengkap Anda di sini...',
                ];
            }
        } else {
            // Static questions list fallback
            $positionName = strtolower($position->name ?? '');
            $questions = $this->getQuestionsForPosition($positionName);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'state' => 'active',
                'candidate' => [
                    'name' => $candidate->name,
                    'position' => $position->name
                ],
                'test' => [
                    'duration_minutes' => $durationMinutes,
                    'remaining_seconds' => $remainingSeconds,
                    'questions' => $questions
                ]
            ]
        ]);
    }

    /**
     * Submit candidate test answers.
     */
    public function submitTest(Request $request, $token)
    {
        $test = PracticalTest::where('token', $token)->first();

        if (!$test) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token ujian tidak valid.'
            ], 404);
        }

        if ($test->submitted_at) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ujian ini sudah pernah dikirim sebelumnya.'
            ], 422);
        }

        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|string|min:10',
        ], [
            'answers.*.required' => 'Semua pertanyaan wajib dijawab.',
            'answers.*.min' => 'Jawaban Anda terlalu singkat. Mohon berikan jawaban yang lebih komprehensif (minimal 10 karakter).',
        ]);

        $candidate = $test->candidate;
        $answers = $request->input('answers');

        // Smart mock evaluation algorithm based on character lengths
        $totalLength = 0;
        foreach ($answers as $ans) {
            $totalLength += strlen($ans);
        }

        if ($totalLength < 100) {
            $score = rand(45, 59);
        } elseif ($totalLength < 300) {
            $score = rand(60, 69);
        } elseif ($totalLength < 600) {
            $score = rand(70, 79);
        } elseif ($totalLength < 1200) {
            $score = rand(80, 89);
        } else {
            $score = rand(90, 98);
        }

        $passingScore = 70;
        $isSuitable = $score >= $passingScore;

        $test->update([
            'score' => $score,
            'answers' => $answers,
            'is_suitable' => $isSuitable,
            'submitted_at' => Carbon::now(),
        ]);

        ActivityLog::create([
            'candidate_id' => $candidate->id,
            'action' => 'practical_test_submitted',
            'description' => 'Kandidat telah menyelesaikan dan mengirimkan jawaban Tes Praktis dengan nilai: ' . $score . ' (' . ($isSuitable ? 'Sesuai' : 'Tidak Sesuai') . ')',
            'new_value' => $score,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Tes praktis Anda berhasil dikirim! Terima kasih.',
            'data' => [
                'score' => $score,
                'is_suitable' => $isSuitable,
                'submitted_at' => $test->submitted_at
            ]
        ]);
    }

    private function getQuestionsForPosition($positionName)
    {
        if (str_contains($positionName, 'seo') || str_contains($positionName, 'search engine')) {
            return [
                [
                    'id' => 1,
                    'text' => 'Bagaimana metodologi Anda dalam melakukan audit teknis SEO pada website e-commerce yang memiliki ribuan halaman dengan masalah duplikasi konten?',
                    'placeholder' => 'Jelaskan analisis canonical tags, penanganan parameter URL, struktur navigasi, sitemap XML, dan optimasi performa halaman...',
                ],
                [
                    'id' => 2,
                    'text' => 'Deskripsikan alur riset kata kunci (keyword research) Anda untuk menemukan peluang kata kunci yang berpotensi mendatangkan trafik transaksional berkualitas tinggi.',
                    'placeholder' => 'Sebutkan tool yang Anda gunakan, cara memetakan search intent (informasional, transaksional), analisis kompetitor, dan pengelompokan kata kunci (keyword clustering)...',
                ],
                [
                    'id' => 3,
                    'text' => 'Sebutkan 3 metrik utama di Google Search Console yang paling sering Anda pantau, dan jelaskan bagaimana Anda menindaklanjuti fluktuasi negatif dari masing-masing metrik tersebut.',
                    'placeholder' => 'Misalnya Clicks, Impressions, CTR, Average Position. Jelaskan cara menganalisis penurunan performa dan solusi perbaikannya...',
                ],
            ];
        }

        if (str_contains($positionName, 'developer') || str_contains($positionName, 'programmer') || str_contains($positionName, 'engineer') || str_contains($positionName, 'tech')) {
            return [
                [
                    'id' => 1,
                    'text' => 'Bagaimana Anda merancang arsitektur database yang efisien dan berskala besar untuk fitur real-time chat pada aplikasi berbasis web?',
                    'placeholder' => 'Jelaskan skema tabel, indexing, penggunaan cache (seperti Redis), polling vs websockets, dan strategi load balancing...',
                ],
                [
                    'id' => 2,
                    'text' => 'Jelaskan perbedaan mendasar antara RESTful API dan GraphQL, serta berikan contoh skenario nyata di mana Anda akan memilih salah satunya dibanding yang lain.',
                    'placeholder' => 'Bahas aspek overhead data, jumlah request roundtrip, fleksibilitas query sisi klien, caching, dan kemudahan deployment...',
                ],
                [
                    'id' => 3,
                    'text' => 'Bagaimana cara Anda mendeteksi, mendiagnosis, dan menyelesaikan bottleneck performa (seperti N+1 query) pada aplikasi berbasis PHP/Laravel?',
                    'placeholder' => 'Jelaskan penggunaan tool seperti Laravel Telescope, clockwork, eager loading (with/load), database indexing, dan optimasi query...',
                ],
            ];
        }

        return [
            [
                'id' => 1,
                'text' => 'Jelaskan rencana kerja taktis dan strategis 30-60-90 hari pertama Anda jika diterima bergabung di posisi ini.',
                'placeholder' => 'Jelaskan proses adaptasi di 30 hari pertama, kontribusi aktif di 60 hari, dan inisiasi program mandiri di 90 hari...',
            ],
            [
                'id' => 2,
                'text' => 'Bagaimana cara Anda menyikapi situasi kerja di mana Anda harus menyelesaikan tugas dengan tenggat waktu ketat, namun terjadi perubahan ruang lingkup kerja (scope creep) di tengah jalan?',
                'placeholder' => 'Jelaskan metode komunikasi kepada manajemen/klien, prioritas tugas, manajemen waktu, dan mitigasi stres...',
            ],
            [
                'id' => 3,
                'text' => 'Berikan satu contoh studi kasus nyata dari pengalaman kerja Anda sebelumnya di mana Anda berhasil memecahkan masalah kompleks atau konflik profesional secara objektif.',
                'placeholder' => 'Gunakan metode STAR (Situation, Task, Action, Result) untuk menceritakan tantangan yang dihadapi dan hasil yang dicapai...',
            ],
        ];
    }
}
