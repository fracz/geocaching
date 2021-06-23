<?php
$data = json_decode(file_get_contents('php://input'), true);
$targetCoords = json_decode(file_get_contents(__DIR__ . '/coords.json'))['GC9C5ZK'];

if (is_array($data) && ($data['coords'] ?? false)) {
    $coords = $data['coords'];
    if (preg_match('#^[NS] \d\d° \d\d\.\d\d\d\' [EW] \d\d\d° \d\d\.\d\d\d\'#', $coords)) {
        $targetCoords = preg_replace('#[^\dNSEW]#', '', $targetCoords);
        $coords = preg_replace('#[^\dNSEW]#', '', $coords);
        $green = 0;
        $yellow = 0;
        for ($i = 0; $i < strlen($targetCoords); $i++) {
            if ($targetCoords{$i} === $coords{$i}) {
                $targetCoords{$i} = 'X';
                $coords{$i} = 'X';
            }
        }
        for ($i = 0; $i < strlen($targetCoords); $i++) {
            if ($coords{$i} !== 'X') {
                if (($index = strpos($targetCoords, $coords{$i})) !== false) {
                    $coords{$i} = '_';
                    $targetCoords{$index} = '_';
                }
            }
        }
        $green = substr_count($targetCoords, 'X');
        $yellow = substr_count($targetCoords, '_');
        echo json_encode(str_split(str_repeat('G', $green) . str_repeat('Y', $yellow) . str_repeat('R', strlen($targetCoords) - $green - $yellow)));
    }
}
