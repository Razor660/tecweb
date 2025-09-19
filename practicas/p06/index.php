<?php
// index.php - P06
// Incluye las funciones
require_once __DIR__ . '/src/funciones.php';

// Helpers para mostrar XHTML-friendly
function h($s){ return htmlspecialchars($s, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'); }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
  "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>P06 - Funciones y Formularios</title>
  <style type="text/css">
    body{font-family: Arial, Helvetica, sans-serif; margin: 1rem;}
    pre{background:#f4f4f4;padding: .5rem; border:1px solid #ddd;}
    table{border-collapse:collapse;}
    td, th{border:1px solid #ccc;padding:.3rem .6rem;}
    fieldset{margin-bottom:1rem;padding:.6rem;}
  </style>
</head>
<body>
  <h1>Práctica 06 — Funciones y Formularios</h1>

  <!-- EJERCICIO 1 -->
  <fieldset><legend>Ejercicio 1 — múltiplo de 5 y 7 (GET)</legend>
    <p>Prueba pasando el parámetro <code>?numero=10</code> en la URL.</p>
    <?php
      if (isset($_GET['numero'])) {
        $res = esMultiplo5y7($_GET['numero']);
        echo '<p>Número: <strong>'.h($_GET['numero']).'</strong></p>';
        echo '<ul>';
        echo '<li>Es múltiplo de 5? '.($res['mul5'] ? 'Sí' : 'No').'</li>';
        echo '<li>Es múltiplo de 7? '.($res['mul7'] ? 'Sí' : 'No').'</li>';
        echo '<li>Es múltiplo de ambos? '.($res['both'] ? 'Sí' : 'No').'</li>';
        echo '</ul>';
      } else {
        echo '<p>No se recibió parámetro <code>numero</code>. Ejemplo: <a href="?numero=35">?numero=35</a></p>';
      }
    ?>
  </fieldset>

    <!-- EJERCICIO 2 -->
  <fieldset><legend>Ejercicio 2 — generar ternas hasta impar,par,impar</legend>
    <?php
      $res2 = generar_hasta_impar_par_impar(0, 1000);
      echo '<p>Iteraciones: <strong>'.$res2['iteraciones'].'</strong>, números generados: <strong>'.$res2['numeros_generados'].'</strong></p>';
      echo '<table><thead><tr><th>#</th><th>n1</th><th>n2</th><th>n3</th></tr></thead><tbody>';
      $idx = 1;
      foreach ($res2['matriz'] as $fila) {
        echo '<tr><td>'.($idx++).'</td><td>'.h($fila[0]).'</td><td>'.h($fila[1]).'</td><td>'.h($fila[2]).'</td></tr>';
      }
      echo '</tbody></table>';
    ?>
  </fieldset>

  