<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// --- Recibir datos POST ---
$nombre = trim($_POST['nombre'] ?? '');
$edad   = $_POST['edad']   ?? '';
$sueldo = $_POST['sueldo'] ?? '';

// --- Validaciones de entrada ---
if (empty($nombre)) {
    echo json_encode(['status' => false, 'mensaje' => 'El nombre completo es obligatorio.']);
    exit;
}

if (!is_numeric($edad) || (int)$edad < 18 || (int)$edad > 99) {
    echo json_encode(['status' => false, 'mensaje' => 'La edad debe ser un número entre 18 y 99 años.']);
    exit;
}

if (!is_numeric($sueldo) || (float)$sueldo < 0) {
    echo json_encode(['status' => false, 'mensaje' => 'El sueldo pretendido debe ser un valor numérico válido.']);
    exit;
}

// --- Procesamiento lógico-matemático ---

// 1. Cálculo de Renta: descuento del 10%
$sueldoBruto  = (float)$sueldo;
$renta        = $sueldoBruto * 0.10;
$sueldoNeto   = $sueldoBruto - $renta;

$edadInt      = (int)$edad;
$nombreFmt    = htmlspecialchars($nombre);

// 2. Evaluación de Perfil
$cumpleEdad   = $edadInt >= 18;
$cumpleSueldo = $sueldoNeto > 450.00;

if ($cumpleEdad && $cumpleSueldo) {
    echo json_encode([
        'status'  => true,
        'mensaje' => "Felicidades $nombreFmt, su perfil es apto. Su sueldo neto tras impuestos será de $" . number_format($sueldoNeto, 2) . "."
    ]);
} else {
    echo json_encode([
        'status'  => false,
        'mensaje' => "Solicitud rechazada. El perfil no cumple con los criterios mínimos de edad o ingresos (Ingreso calculado: $" . number_format($sueldoNeto, 2) . ")."
    ]);
}
