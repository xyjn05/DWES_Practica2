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

// Bloque 2: Procesador de alquileres, excepciones y tarifas

//- Función de facturación documentada
/**
 * Procesa las reservas y calcular el importe total y diferenciar por distintos categoría.
 * 
 * @param array<int,array{vehiculo:string,dias:int,tarifa:float}>$reservas
 *  La reserva (La marca del vehículo, días de alquiler y tarifa del vehículo).
 * 
 * @return array{subtotal:float,total:float,categoria:string}
 *  Importe subtotal, total y categoría aplicada.
 * 
 * @throws InvalidArgumentException Si el listado de la reserva está vacío.
 */

function procesarReserva(array $reservas): array
{
    // - Control de excepciones
    if (empty($reservas)) { // Si el array $reservas está vació, se lanza la excepción. 
        throw new InvalidArgumentException('No hay reserva');
    }

    $subtotal = 0.0;

    foreach ($reservas as $reserva) { // Utilizamos foreach para recorrer el array $reservas, y con calculamos la subtotal.
        $subtotal += $reserva['dias'] * $reserva['tarifa'];
    }

    // - Categorización condicional
    [$categoria, $porcentaje] = match (true) { // match revisa qué condición está cumpliendo.
        $subtotal >= 1000.0 => ['Descuento corporativa (15%)', -0.15],
        $subtotal >= 500.0  => ['Descuento estándar (10%)', -0.1],
        $subtotal >= 100.0  => ['Tarifa normal', 0.0],
        default          => ['Suplemento reserva (5%)', 0.05]
    };

    $total = $subtotal * (1 + $porcentaje); // Calcula la total.

    return [ // Revuelve los resultado.
        'subtotal' => $subtotal,
        'categoria' => $categoria,
        'total' => $total,
    ];

}

try {
    $reservas = [
        ['vehiculo' => 'Tesla', 'dias' => $dias, 'tarifa' => 79.90],
        ['vehiculo' => 'Nissan', 'dias' => $dias, 'tarifa' => 59.9]
    ];

    $resultado = procesarReserva($reservas);
    echo '<p>Subtotal: ' . number_format($resultado['subtotal'], 2, ',', '.') . ' €<br>';
    echo 'Categoría: ' . $resultado['categoria'] . '<br>';
    echo 'Total a pagar: ' . number_format($resultado['total'], 2, ',', '.') . ' €</p>';

    procesarReserva([]);

    // - Control de excepciones
} catch (InvalidArgumentException $e) { // Imprime excepción.
    echo '<p>Excepción capturada: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>'; // Pasar a HTML
}