<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Recibir datos POST
$nombre = trim($_POST['nombre'] ?? '');
$edad   = $_POST['edad']   ?? '';
$sueldo = $_POST['sueldo'] ?? '';

// --- Validaciones ---
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

// --- Respuesta exitosa ---
$nombreFormateado = htmlspecialchars($nombre);
$sueldoFormateado = number_format((float)$sueldo, 2);

echo json_encode([
    'status'  => true,
    'mensaje' => "Aplicación recibida correctamente. Bienvenido/a, $nombreFormateado. " .
                 "Edad: {$edad} años. Sueldo pretendido: \$$sueldoFormateado."
]);
