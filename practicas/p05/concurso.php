<?php
// concurso.php — muestra todo lo recibido vía POST en una página verde
?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>MUCHAS GRACIAS</title>
  <style> body { background: #c8f5c8; font-family: Arial, Helvetica, sans-serif; } 
          .box { background: white; padding: 1rem; margin: 2rem auto; max-width:800px; border-radius:6px; }
          h1 { color: #0a7b0a; }
          dl dt { font-weight: bold; margin-top: .5rem; }
  </style>
</head>
<body>
  <div class="box">
    <h1>MUCHAS GRACIAS</h1>
    <p>Gracias por entrar al concurso de Tenis Mike&#174 "Chidos mis Tenis". Hemos recibido la siguiente información de tu registro:</p>
    <h2>Acerca de ti:</h2>
    <dl>
      <dt>Nombre</dt><dd><?php echo htmlspecialchars($_POST['name'] ?? ''); ?></dd>
      <dt>E-mail</dt><dd><?php echo htmlspecialchars($_POST['email'] ?? ''); ?></dd>
      <dt>Teléfono</dt><dd><?php echo htmlspecialchars($_POST['phone'] ?? ''); ?></dd>
      <dt>Tu triste historia:</dt><dd><?php echo nl2br(htmlspecialchars($_POST['story'] ?? '')); ?></dd>
      <h2>Tu diseño de tenis (si ganas):</h2>
      <dt>Color</dt><dd><?php echo htmlspecialchars($_POST['color'] ?? ''); ?></dd>
      <dt>Características</dt>
      <dd>
        <?php
          if (!empty($_POST['features']) && is_array($_POST['features'])) {
            echo '<ul>';
            foreach ($_POST['features'] as $f) {
              echo '<li>' . htmlspecialchars($f) . '</li>';
            }
            echo '</ul>';
          } else {
            echo 'Ninguna';
          }
        ?>
      </dd>
      <dt>Talla</dt><dd><?php echo htmlspecialchars($_POST['size'] ?? ''); ?></dd>
    </dl>

    <p><a href="formulario.html">Volver al formulario</a></p>
  </div>
</body>
</html>
