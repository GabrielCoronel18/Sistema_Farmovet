<?php
namespace Gabriel\SistemaFarmovet\helpers;

use Hashids\Hashids;

function getHashids(): Hashids {
   
    return new Hashids('SistemaFarmovet', 6);
}

function codificarId(int $id): string {
    return getHashids()->encode($id);
}

function decodificarId(string $hash): ?int {
    $resultado = getHashids()->decode($hash);
    return !empty($resultado) ? (int) $resultado[0] : null;
}