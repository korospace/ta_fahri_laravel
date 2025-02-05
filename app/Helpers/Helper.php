<?php

use Illuminate\Http\Request;

function readJsonFile($file)
{
    // Cek apakah file valid
    if (!$file || !$file->isValid()) {
        return null;
    }

    try {
        // Baca isi file JSON
        $content = file_get_contents($file->getRealPath());

        // Decode JSON menjadi array
        $data = json_decode($content, true);

        // Validasi hasil decoding
        if (json_last_error() === JSON_ERROR_NONE) {
            return $data;
        } else {
            return null; // JSON tidak valid
        }
    } catch (\Exception $e) {
        return null; // Tangani error jika terjadi
    }
}

/**
 * Method Parser.
 *
 * @param string $variableName
 */
function _methodParser(string $variableName): void
{
    $putdata  = fopen("php://input", "r");
    $raw_data = '';

    while ($chunk = fread($putdata, 1024))
        $raw_data .= $chunk;

    fclose($putdata);

    $boundary = substr($raw_data, 0, strpos($raw_data, "\r\n"));

    if(empty($boundary)){
        parse_str($raw_data,$data);
        $GLOBALS[ $variableName ] = $data;
        return;
    }

    $parts = array_slice(explode($boundary, $raw_data), 1);
    $data  = array();

    foreach ($parts as $part) {
        if ($part == "--\r\n") break;

        $part = ltrim($part, "\r\n");
        list($raw_headers, $body) = explode("\r\n\r\n", $part, 2);

        $raw_headers = explode("\r\n", $raw_headers);
        $headers = array();
        foreach ($raw_headers as $header) {
            list($name, $value) = explode(':', $header);
            $headers[strtolower($name)] = ltrim($value, ' ');
        }

        if (isset($headers['content-disposition'])) {
            $filename = null;
            $tmp_name = null;
            preg_match(
                '/^(.+); *name="([^"]+)"(; *filename="([^"]+)")?/',
                $headers['content-disposition'],
                $matches
            );

            if(count($matches) !== 0){
                list(, $type, $name) = $matches;
            }

            if( isset($matches[4]) )
            {
                if( isset( $_FILES[ $matches[ 2 ] ] ) )
                {
                    continue;
                }

                $filename       = $matches[4];
                $filename_parts = pathinfo( $filename );
                $tmp_name       = tempnam( ini_get('upload_tmp_dir'), $filename_parts['filename']);

                $_FILES[ $matches[ 2 ] ] = array(
                    'error'=>0,
                    'name'=>$filename,
                    'tmp_name'=>$tmp_name,
                    'size'=>strlen( $body ),
                    'type'=>preg_replace('/\s+/', '', $value)
                );

                file_put_contents($tmp_name, $body);
            }
            else
            {
                $data[$name] = substr($body, 0, strlen($body) - 2);
            }
        }

    }
    $GLOBALS[ $variableName ] = $data;
    return;
}

/**
 * Transalte Date To English
 *
 * @param string $stringDate
 */
function MonthToEnglish($stringDate)
{
    $bulan_indonesia = [
        'Januari', 'Februari', 'Maret', 'April',
        'Mei', 'Juni', 'Juli', 'Agustus',
        'September', 'Oktober', 'November', 'Desember'
    ];

    $bulan_inggris = [
        'January', 'February', 'March', 'April',
        'May', 'June', 'July', 'August',
        'September', 'October', 'November', 'December'
    ];

    $tanggal_baru = str_replace($bulan_indonesia, $bulan_inggris, $stringDate);
    $tanggal_baru = strtr($stringDate, array_combine($bulan_indonesia, $bulan_inggris));

    return $tanggal_baru;
}

/**
 * Transalte Date To Indonesia
 *
 * @param string $stringDate
 */
function MonthToIndonesia($stringDate)
{
    $bulan_indonesia = [
        'Januari', 'Februari', 'Maret', 'April',
        'Mei', 'Juni', 'Juli', 'Agustus',
        'September', 'Oktober', 'November', 'Desember'
    ];

    $bulan_inggris = [
        'January', 'February', 'March', 'April',
        'May', 'June', 'July', 'August',
        'September', 'October', 'November', 'December'
    ];

    $tanggal_baru = str_replace($bulan_inggris, $bulan_indonesia, $stringDate);
    $tanggal_baru = strtr($stringDate, array_combine($bulan_inggris, $bulan_indonesia));

    return $tanggal_baru;
}

/**
 * Site Authorization
 *
 * @param Illuminate\Http\Request $request
 * @param string $siteId
 */
function authorizeSite(Request $request, string $siteId) : void 
{
    if ($request->user->user_level->id != 1) {
        if ($request->user->site->id != $siteId) {
            abort(401);
        }
    }
}

function buildAdjacencyList(array $nodes, array $edges)
{
    $graph = [];

    // Initialize an empty adjacency list for each node
    foreach ($nodes as $node) {
        $graph[$node] = [];
    }

    // Build adjacency list from edges
    foreach ($edges as $edge) {
        list($u, $v) = $edge;

        // Ensure both nodes exist in the graph
        if (!isset($graph[$u])) $graph[$u] = [];
        if (!isset($graph[$v])) $graph[$v] = [];

        // Avoid self-loops
        if ($u !== $v) {
            $graph[$u][] = $v;
            $graph[$v][] = $u; // Since it's an undirected graph
        }
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
    $betweenness = array_fill_keys(array_keys($graph), 0.0); // Use `array_keys($graph)`

    foreach ($nodes as $s) {
        if (!isset($graph[$s])) continue; // Skip missing nodes

        $queue = new SplQueue();
        $stack = [];
        $distance = array_fill_keys(array_keys($graph), -1);
        $sigma = array_fill_keys(array_keys($graph), 0);
        $predecessors = array_fill_keys(array_keys($graph), []);

        $distance[$s] = 0;
        $sigma[$s] = 1;
        $queue->enqueue($s);

        while (!$queue->isEmpty()) {
            $v = $queue->dequeue();
            array_push($stack, $v);

            foreach ($graph[$v] as $w) {
                if ($distance[$w] == -1) {
                    $distance[$w] = $distance[$v] + 1;
                    $queue->enqueue($w);
                }

                if ($distance[$w] == $distance[$v] + 1) {
                    $sigma[$w] += $sigma[$v];
                    $predecessors[$w][] = $v;
                }
            }
        }

        $delta = array_fill_keys(array_keys($graph), 0.0);
        while (!empty($stack)) {
            $w = array_pop($stack);
            foreach ($predecessors[$w] as $v) {
                $delta[$v] += ($sigma[$v] / $sigma[$w]) * (1 + $delta[$w]);
            }
            if ($w != $s) {
                $betweenness[$w] += $delta[$w];
            }
        }
    }

    foreach ($betweenness as &$value) {
        $value = number_format($value / 2, 6);
    }

    return $betweenness;
}

function calculateClosenessCentrality(array $graph)
{
    $closeness = [];
    $nodes = array_keys($graph);
    $n = count($nodes);

    foreach ($nodes as $node) {
        $distances = [];
        $queue = new SplQueue();
        $visited = array_fill_keys($nodes, false);

        $queue->enqueue([$node, 0]);
        $visited[$node] = true;

        // BFS untuk menghitung jarak terpendek
        while (!$queue->isEmpty()) {
            list($current, $d) = $queue->dequeue();
            $distances[$current] = $d;

            foreach ($graph[$current] as $neighbor) {
                if (!$visited[$neighbor]) {
                    $visited[$neighbor] = true;
                    $queue->enqueue([$neighbor, $d + 1]);
                }
            }
        }

        // Hitung total jarak
        $totalDistance = array_sum($distances);

        // Closeness = (n-1) / total_jarak (jika graf terhubung)
        if ($totalDistance > 0) {
            $closeness[$node] = number_format(($n - 1) / $totalDistance, 6);
        } else {
            $closeness[$node] = 0.0;
        }
    }

    return $closeness;
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