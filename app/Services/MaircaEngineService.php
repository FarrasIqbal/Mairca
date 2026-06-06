<?php

namespace App\Services;

use App\Models\Position;
use App\Models\Evaluation;

class MaircaEngineService
{
    public function calculate(Position $position)
    {
        $criteria = $position->criteria;
        // Hanya hitung kandidat yang sudah masuk tahap "evaluasi_spk"
        $candidates = $position->candidates()->where('status', 'evaluasi_spk')->get();

        if ($candidates->isEmpty() || $criteria->isEmpty()) {
            return null;
        }

        $m = $candidates->count(); // Jumlah alternatif (kandidat)

        // 1. Matriks Keputusan (X) & Cari Nilai Min-Max
        $matrixX = [];
        $minMax = [];

        foreach ($criteria as $c) {
            $minMax[$c->id] = ['min' => 999999, 'max' => -999999];
        }

        foreach ($candidates as $candidate) {
            foreach ($criteria as $c) {
                // Ambil nilai rata-rata jika ada lebih dari 1 reviewer yang menilai
                $avgScore = Evaluation::where('candidate_id', $candidate->id)
                    ->where('criteria_id', $c->id)
                    ->avg('score') ?? 0;

                $matrixX[$candidate->id][$c->id] = (float) $avgScore;

                if ($avgScore < $minMax[$c->id]['min']) $minMax[$c->id]['min'] = $avgScore;
                if ($avgScore > $minMax[$c->id]['max']) $minMax[$c->id]['max'] = $avgScore;
            }
        }

        // 2. Matriks Penilaian Teoretis (Tp)
        // Probabilitas (Pai) = 1 dibagi jumlah kandidat
        $Pai = 1 / $m;
        $Tp = [];
        foreach ($criteria as $c) {
            $Tp[$c->id] = $Pai * (float) $c->weight;
        }

        // 3. Matriks Riil (Tr) & Matriks Gap (G)
        $Q = []; // Array untuk menampung total skor akhir (Qi)
        $Tr = [];
        $G = [];

        foreach ($candidates as $candidate) {
            $Q[$candidate->id] = 0;

            foreach ($criteria as $c) {
                $x = $matrixX[$candidate->id][$c->id];
                $min = $minMax[$c->id]['min'];
                $max = $minMax[$c->id]['max'];

                $tp = $Tp[$c->id];
                $tr = 0;

                $denominator = $max - $min;

                // Mencegah error Division by Zero jika semua kandidat nilainya sama
                if ($denominator == 0) {
                    $tr = $tp;
                } else {
                    if ($c->type === 'benefit') {
                        $tr = $tp * (($x - $min) / $denominator);
                    } else { // cost
                        $tr = $tp * (($max - $x) / $denominator);
                    }
                }

                // 4. Kesenjangan / Gap (G) = Tp - Tr
                $gap = $tp - $tr;

                $Tr[$candidate->id][$c->id] = $tr;
                $G[$candidate->id][$c->id] = $gap;

                // 5. Total Skor (Qi) = Sigma G
                $Q[$candidate->id] += $gap;
            }
        }

        // 6. Merangking Kandidat berdasarkan Skor Qi terkecil
        $rankedCandidates = $candidates->map(function ($candidate) use ($Q) {
            $candidate->mairca_score = $Q[$candidate->id];
            return $candidate;
        })->sortBy('mairca_score')->values();

        return [
            'ranked' => $rankedCandidates,
            'is_valid' => true,
            'matrixX' => $matrixX,
            'minMax' => $minMax,
            'Tp' => $Tp,
            'Tr' => $Tr,
            'G' => $G,
            'Pai' => $Pai,
            'criteria' => $criteria,
            'candidates' => $candidates
        ];
    }
}
