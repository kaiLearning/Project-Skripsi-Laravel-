<?php
namespace App\Services;

class ConfusionMatrix
{
    /**
     * @param array $actual
     * @param array $predicted
     * @param array|null $labels
     */
    public function compute(array $actual, array $predicted, ?array $labels = null): array
    {
        if (count($actual) !== count($predicted)) {
            throw new \InvalidArgumentException("Panjang actual dan predicted harus sama.");
        }

        if ($labels === null) {
            $labels = array_values(array_unique(array_merge($actual, $predicted)));
            sort($labels);
        }
        $L = count($labels);
        $idx = array_flip($labels);

        $M = array_fill(0, $L, array_fill(0, $L, 0));

        $n = count($actual);
        for ($k = 0; $k < $n; $k++) {
            if (!isset($idx[$actual[$k]]) || !isset($idx[$predicted[$k]])) continue;
            $ai = $idx[$actual[$k]];
            $pi = $idx[$predicted[$k]];
            $M[$ai][$pi] += 1;
        }

        $support = [];
        for ($i = 0; $i < $L; $i++) $support[$labels[$i]] = array_sum($M[$i]);

        $total = array_sum(array_map('array_sum', $M));
        $perClass = [];
        $sumTP = $sumFP = $sumFN = 0;

        for ($i = 0; $i < $L; $i++) {
            $tp = $M[$i][$i];
            $fp = $fn = 0;
            for ($r = 0; $r < $L; $r++) if ($r !== $i) $fp += $M[$r][$i];
            for ($c = 0; $c < $L; $c++) if ($c !== $i) $fn += $M[$i][$c];
            $tn = $total - ($tp + $fp + $fn);

            $precision = ($tp + $fp) > 0 ? $tp / ($tp + $fp) : 0.0;
            $recall    = ($tp + $fn) > 0 ? $tp / ($tp + $fn) : 0.0;
            $f1        = ($precision + $recall) > 0 ? (2 * $precision * $recall) / ($precision + $recall) : 0.0;

            $perClass[$labels[$i]] = compact('tp','fp','fn','tn','precision','recall','f1');

            $sumTP += $tp; $sumFP += $fp; $sumFN += $fn;
        }

        $macroPrecision = $L ? array_sum(array_column($perClass, 'precision')) / $L : 0.0;
        $macroRecall    = $L ? array_sum(array_column($perClass, 'recall')) / $L : 0.0;
        $macroF1        = $L ? array_sum(array_column($perClass, 'f1')) / $L : 0.0;

        $microPrecision = ($sumTP + $sumFP) > 0 ? $sumTP / ($sumTP + $sumFP) : 0.0;
        $microRecall    = ($sumTP + $sumFN) > 0 ? $sumTP / ($sumTP + $sumFN) : 0.0;
        $microF1        = ($microPrecision + $microRecall) > 0 ? (2 * $microPrecision * $microRecall) / ($microPrecision + $microRecall) : 0.0;

        $correct = 0; for ($i = 0; $i < $L; $i++) $correct += $M[$i][$i];
        $accuracy = $total > 0 ? $correct / $total : 0.0;

        return [
            'labels'    => $labels,
            'matrix'    => $M,
            'support'   => $support,
            'per_class' => $perClass,
            'accuracy'  => $accuracy,
            'macro_avg' => ['precision'=>$macroPrecision,'recall'=>$macroRecall,'f1'=>$macroF1],
            'micro_avg' => ['precision'=>$microPrecision,'recall'=>$microRecall,'f1'=>$microF1],
        ];
    }
}
