<?php
// Declara el modo de tipado estricto.
declare(strict_types=1);

// Mostrar errores durante el desarrollo.
ini_set('display_errors', '1'); // Decide dónde se muestra ese reporte
error_reporting(E_ALL); // Decide qué se reporta

// Escapa texto para mostrarlo de forma segura dentro de HTML.
function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Bloque 3: Tratamiento de texto multibyte y análisis de flota
//- Normalización de textos de flota
$flota = [
    ['nombre' => 'Citroën', 'categoria' => 'diesel', 'autonomia' => 395, 'descuento' => '10'],
    ['nombre' => 'TeSlA', 'categoria' => 'eléctrico', 'autonomia' => 520, 'descuento' => null],
    ['nombre' => 'ferrari', 'categoria' => 'gasolina', 'autonomia' => 400, 'descuento' => '5'],
];

$flota = array_map(function (array $v): array {
    $v['categoria']       = mb_convert_case($v['categoria'], MB_CASE_TITLE, 'UTF-8'); // Formato título
    $v['nombre_mayus']    = mb_strtoupper($v['nombre'], 'UTF-8'); // Mayúsculas
    $v['longitud_chars']  = mb_strlen($v['nombre'], 'UTF-8'); // Calcula su longitud exacta en caracteres

    //- Diferenciación de existencia de propiedades
    $v['descuento_isset']      = isset($v['descuento']); // bool(false) es null
    $v['descuento_key_existe'] = array_key_exists('descuento', $v); // bool(true) La clave existe aunque valga null
    
    return $v;
}, $flota);


//- Ordenación del catálogo
usort($flota, fn(array $a, array $b): int => $b['autonomia'] <=> $a['autonomia']);
