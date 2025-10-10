<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<title>Registro Completado</title>
		<style type="text/css">
			body {margin: 20px; 
			background-color: #C4DF9B;
			font-family: Verdana, Helvetica, sans-serif;
			font-size: 90%;}
			h1 {color: #005825;
			border-bottom: 1px solid #005825;}
			h2 {font-size: 1.2em;
			color: #4A0048;}
		</style>
	</head>
	<body>
		<h1>MUCHAS GRACIAS</h1>

		<p>Gracias por entrar al concurso de Tenis Mike&#174; "Chidos mis Tenis". Hemos recibido la siguiente información de tu registro:</p>

		<?php
		// Función de escape para salida segura
		function h($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }

		// Leer y normalizar datos recibidos por POST (usar ?? requiere PHP 7+)
		$name  = $_POST['name']  ?? '';
		$email = $_POST['email'] ?? '';
		$phone = $_POST['phone'] ?? '';
		$story = $_POST['story'] ?? '';
		$color = $_POST['color'] ?? '';
		$features = $_POST['features'] ?? []; // puede no existir => array vacío
		$size  = $_POST['size'] ?? '';
		?>

		<h2>Acerca de ti:</h2>
		<dl>
		  <dt>Nombre:</dt><dd><?php echo h($name); ?></dd>
		  <dt>E-mail:</dt><dd><?php echo h($email); ?></dd>
		  <dt>Teléfono:</dt><dd><?php echo h($phone); ?></dd>
		  <dt>Tu triste historia:</dt><dd><?php echo nl2br(h($story)); ?></dd>
		</dl>

		<h3>Tu diseño de Tenis (si ganas)</h3>
		<dl>
		  <dt>Color:</dt><dd><?php echo h($color); ?></dd>
		  <dt>Características:</dt>
		  <dd>
		    <?php
		      if (is_array($features) && count($features) > 0) {
		        echo '<ul>';
		        foreach ($features as $f) echo '<li>'.h($f).'</li>';
		        echo '</ul>';
		      } else {
		        echo 'Ninguna';
		      }
		    ?>
		  </dd>
		  <dt>Talla:</dt><dd><?php echo h($size); ?></dd>
		</dl>
		<p>
		    <a href="http://validator.w3.org/check?uri=referer"><img
		      src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Strict" height="31" width="88" /></a>
		</p>
	</body>
</html>