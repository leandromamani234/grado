<?php
require_once '../modelo/modelo_registroPersona.php';

if (!isset($_GET['term'])) {
    echo json_encode([]);
    exit;
}

$texto = trim($_GET['term']);
$modelo = new ModeloPersona();
$personas = $modelo->buscarPersonasNoSocias($texto);

// Formato compatible con Select2
$resultado = [];
foreach ($personas as $p) {
    $resultado[] = [
        'id' => $p['id_persona'],
        'text' => $p['nombre_completo']
    ];
}

echo json_encode($resultado);
exit;
?>
