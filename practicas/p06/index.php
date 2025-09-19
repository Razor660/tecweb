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

  <!-- EJERCICIO 3 -->
  <fieldset><legend>Ejercicio 3 — primer múltiplo de n (GET) — while y do-while</legend>
    <p>Prueba <code>?divisor=37</code> en la URL.</p>
    <?php
      if (isset($_GET['divisor'])) {
        $d = intval($_GET['divisor']);
        echo '<h4>Variante while</h4>';
        $rW = primer_multiplo_while($d);
        if ($rW['numero'] !== null) {
          echo '<p>Encontrado: <strong>'.h($rW['numero']).'</strong> en '.$rW['iteraciones'].' iteraciones</p>';
        } else {
          echo '<p>No se encontró dentro del límite.</p>';
        }

        echo '<h4>Variante do-while</h4>';
        $rD = primer_multiplo_do_while($d);
        if ($rD['numero'] !== null) {
          echo '<p>Encontrado: <strong>'.h($rD['numero']).'</strong> en '.$rD['iteraciones'].' iteraciones</p>';
        } else {
          echo '<p>No se encontró dentro del límite.</p>';
        }
      } else {
        echo '<p>No se recibió parámetro <code>divisor</code>. Ejemplo: <a href="?divisor=37">?divisor=37</a></p>';
      }
    ?>
  </fieldset>

  <!-- EJERCICIO 4 -->
  <fieldset><legend>Ejercicio 4 — arreglo ASCII 97..122</legend>
    <?php
      $arr = crear_arreglo_ascii();
      echo '<table><thead><tr><th>Código</th><th>Letra</th></tr></thead><tbody>';
      foreach ($arr as $k => $v) {
        echo '<tr><td>'.h($k).'</td><td>'.h($v).'</td></tr>';
      }
      echo '</tbody></table>';
    ?>
  </fieldset>

  <!-- EJERCICIO 5 -->
  <fieldset><legend>Ejercicio 5 — formulario edad y sexo (POST)</legend>
    <form action="#ej5" method="post">
      <p>
        Edad: <input type="number" name="edad" min="0" required />
        Sexo:
        <select name="sexo">
          <option value="f">Femenino</option>
          <option value="m">Masculino</option>
          <option value="otro">Otro</option>
        </select>
        <input type="submit" value="Enviar" />
      </p>
    </form>
    <a id="ej5"></a>
    <?php
      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edad']) && isset($_POST['sexo'])) {
        $edad = $_POST['edad'];
        $sexo = $_POST['sexo'];
        if (es_mujer_en_rango($edad, $sexo)) {
          echo '<p><strong>Bienvenida, usted está en el rango de edad permitido.</strong></p>';
        } else {
          echo '<p><strong>No cumple los requisitos:</strong> ';
          echo 'Edad debe ser entre 18 y 35 y sexo Femenino. Usted envió edad='.h($edad).' y sexo='.h($sexo).'.</p>';
        }
      }
    ?>
  </fieldset>

  <!-- EJERCICIO 6 -->
  <fieldset><legend>Ejercicio 6 — registro vehicular (consulta)</legend>
    <form action="#ej6" method="post">
      <p>
        Buscar por matrícula (LLLNNNN): <input type="text" name="matricula" placeholder="ABC1234" />
        <input type="submit" name="buscar" value="Buscar" />
        <input type="submit" name="todos" value="Mostrar todos" />
      </p>
    </form>
    <a id="ej6"></a>
    <?php
      $registro = registro_parque_vehicular();
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['todos'])) {
          echo '<h4>Todos los autos (print_r)</h4><pre>';
          print_r($registro);
          echo '</pre>';
        } elseif (!empty($_POST['matricula'])) {
          $mat = strtoupper(trim($_POST['matricula']));
          $res = buscar_por_matricula($registro, $mat);
          if ($res === null) {
            echo '<p>No se encontró la matrícula '.h($mat).'</p>';
          } else {
            echo '<h4>Registro '.h($mat).'</h4><pre>';
            print_r($res);
            echo '</pre>';
          }
        } else {
          echo '<p>Ingresa una matrícula o pulsa "Mostrar todos".</p>';
        }
      }
    ?>
  </fieldset>

  <p><em>Recuerda: todas las funciones están en <code>src/funciones.php</code>.</em></p>
</body>
</html>
