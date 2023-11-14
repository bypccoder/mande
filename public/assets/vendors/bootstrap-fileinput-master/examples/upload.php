<?php
// Comprobar si se ha enviado el archivo
if ($_FILES["file"]["error"] === UPLOAD_ERR_OK) {
  // Ruta donde se almacenará el archivo subido
  $uploadDir = './files/';

  // Obtener el nombre original del archivo
  $fileName = $_FILES["file"]["name"];

  // Obtener la extensión del archivo
  $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

  // Lista de extensiones permitidas (xls y xlsx)
  $allowedExts = array('xls', 'xlsx');

  // Tamaño máximo permitido (60MB)
  $maxFileSize = 60 * 1024 * 1024;

  // Verificar que la extensión del archivo sea válida
  if (in_array($fileExt, $allowedExts)) {
    // Verificar que el tamaño del archivo sea válido
    if ($_FILES["file"]["size"] <= $maxFileSize) {
      // Generar un nombre único para el archivo
      $uniqueFileName = uniqid() . '.' . $fileExt;

      // Mover el archivo temporal a la ubicación deseada
      $uploadPath = $uploadDir . $uniqueFileName;
      move_uploaded_file($_FILES["file"]["tmp_name"], $uploadPath);

      // Aquí puedes realizar cualquier acción adicional que necesites con el archivo subido
      // Por ejemplo, leer el contenido del archivo Excel, realizar operaciones, etc.

      // Devolver una respuesta al cliente (opcional)
      echo json_encode(array('success' => true, 'message' => 'Archivo subido correctamente.'));
    } else {
      echo json_encode(array('success' => false, 'message' => 'El tamaño del archivo es mayor que el máximo permitido.'));
    }
  } else {
    echo json_encode(array('success' => false, 'message' => 'Tipo de archivo no válido. Solo se permiten archivos xls y xlsx.'));
  }
} else {
  echo json_encode(array('success' => false, 'message' => 'Ocurrió un error al subir el archivo.'));
}
?>
