<?php
// Bloque 1: Configuración, validación y diagnóstico

//- Inicialización
// Declara el modo de tipado estricto.
declare(strict_types=1);

// Mostrar errores durante el desarrollo.
ini_set('display_errors', '1'); // Decide dónde se muestra ese reporte
error_reporting(E_ALL); // Decide qué se reporta


//Capturamos el dato crudo de la URL
$raw = $_GET['dias'] ?? null;


//- Validación de la reserva

// Validamos que $raw sea entero y positivo. Y como mínimo 1.
$dias = filter_var($raw, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

//Si $dias igual a false 
if ($dias === false) { // Utilizamos === para evitar un valor 0 válido no se confunda con false. Aunque, en este caso, esto no va pasar porque hemos establecido 'min_range' => 1.
    http_response_code(400); // Sirve para establece el código de estado de HTTP.
    echo 'Error 400: El parámetro "dias" debe ser un número entero positivos.'; // Imprime Error.
    exit; // Detiene la ejecución del PHP inmediatamente.
}

//- Trazabilidad de datos

echo '<pre>'; // Etiqueta de HTML para mostrar texto preformateado.
echo "Inspección técnica\n";
var_dump($raw);   // String
var_dump($dias);  // Int
echo '</pre>';