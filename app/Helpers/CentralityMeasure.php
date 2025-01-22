<?php

function buildAdjacencyList(array $nodes, array $edges)
{
    $graph = [];
    foreach ($nodes as $node) {
        $graph[$node] = [];
    }

    foreach ($edges as $edge) {
        [$source, $target] = $edge;
        $graph[$source][] = $target;
        $graph[$target][] = $source;
    }

    return $graph;
}

function calculateDegreeCentrality(array $graph)
{
    $centrality = [];
    $nodeCount = count($graph);

    foreach ($graph as $node => $neighbors) {
        $centrality[$node] = number_format(count($neighbors) / ($nodeCount - 1), 6);
    }

    return $centrality;
}

function calculateBetweennessCentrality(array $nodes, array $edges, array $graph)
{
    // Implementasi betweenness centrality jika dibutuhkan
    $centrality = [];
    foreach ($nodes as $node) {
        $centrality[$node] = 0.0; // Placeholder
    }
    return $centrality;
}

function calculateClosenessCentrality(array $graph)
{
    // Implementasi closeness centrality
    $centrality = [];
    foreach ($graph as $node => $neighbors) {
        $centrality[$node] = 0.0; // Placeholder
    }
    return $centrality;
}

function calculateEigenvectorCentrality(array $graph, $iterations = 100, $tolerance = 1e-6)
{
    $centrality = [];
    $nodes = array_keys($graph);
    $nodeCount = count($nodes);

    $scores = array_fill_keys($nodes, 1.0);
    for ($i = 0; $i < $iterations; $i++) {
        $newScores = array_fill_keys($nodes, 0.0);
        foreach ($graph as $node => $neighbors) {
            foreach ($neighbors as $neighbor) {
                $newScores[$node] += $scores[$neighbor];
            }
        }

        $norm = sqrt(array_sum(array_map(fn($v) => $v * $v, $newScores)));
        foreach ($newScores as &$score) {
            $score /= $norm;
        }

        $diff = max(array_map(fn($n, $o) => abs($n - $o), $newScores, $scores));
        $scores = $newScores;

        if ($diff < $tolerance) {
            break;
        }
    }

    foreach ($scores as $node => $score) {
        $centrality[$node] = number_format($score, 6);
    }

    return $centrality;
}
