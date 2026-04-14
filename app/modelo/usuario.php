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
    // Aprobado
    echo json_encode([
        'status'  => true,
        'mensaje' => "Perfil APROBADO. Bienvenido/a, $nombreFmt. " .
                     "Sueldo bruto: $" . number_format($sueldoBruto, 2) . " | " .
                     "Renta (10%): $" . number_format($renta, 2) . " | " .
                     "Sueldo neto: $" . number_format($sueldoNeto, 2) . "."
    ]);
} else {
    // Rechazado — indicar motivo específico
    $motivos = [];

    if (!$cumpleEdad) {
        $motivos[] = "edad insuficiente ($edadInt años, mínimo 18)";
    }

    if (!$cumpleSueldo) {
        $motivos[] = "sueldo neto insuficiente ($" . number_format($sueldoNeto, 2) . ", mínimo \$450.01)";
    }

    echo json_encode([
        'status'  => false,
        'mensaje' => "Perfil RECHAZADO para $nombreFmt. Motivo(s): " . implode(' y ', $motivos) . "."
    ]);
}
