<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
  "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Practica 4</title>
</head>
<body>
    <h2>Ejercicio 1</h2>
    <p>Determina cual de las siguientes variables son validas y explica por que:</p>
    <p>$_myvar,  $_7var,  myvar,  $myvar,  $var7,  $_element1, $house*5</p>
    <?php
        // EJERCICIO 1 (no modifica salida logica, solo presentacion)
        $_myvar = "valida";
        $_7var = "valida";
        $myvar = "valida";
        $var7 = "valida";
        $_element1 = "valida";
        echo '<ul>';
        echo '<li>$_myvar valida (inicia con guion bajo).</li>';
        echo '<li>$_7var valida (inicia con guion bajo).</li>';
        echo '<li>myvar invalida (no inicia con $).</li>';
        echo '<li>$myvar valida (inicia con letra).</li>';
        echo '<li>$var7 valida (letra seguida de numero).</li>';
        echo '<li>$_element1 valida (guion bajo permitido).</li>';
        echo '<li>$house*5 invalida (* no permitido en nombre).</li>';
        echo '</ul>';
    ?>

    <h2>Ejercicio 2</h2>
    <?php
        $a = "ManejadorSQL";
        $b = "MySQL";
        $c = &$a;

        // muestro en bloques <p> y con <br /> autoclosed, escapando por seguridad
        echo '<p><strong>Primer bloque:</strong></p>';
        echo '<p>$a = ' . htmlspecialchars($a, ENT_QUOTES, 'UTF-8') . '<br />'
                 . '$b = ' . htmlspecialchars($b, ENT_QUOTES, 'UTF-8') . '<br />'
                 . '$c = ' . htmlspecialchars($c, ENT_QUOTES, 'UTF-8') . '</p>';

        // Segundo bloque (referencias)
        $a = "PHP server";
        $b = &$a;

        echo '<p><strong>Segundo bloque:</strong></p>';
        echo '<p>$a = ' . htmlspecialchars($a, ENT_QUOTES, 'UTF-8') . '<br />'
                 . '$b = ' . htmlspecialchars($b, ENT_QUOTES, 'UTF-8') . '<br />'
                 . '$c = ' . htmlspecialchars($c, ENT_QUOTES, 'UTF-8') . '</p>';

        echo '<p><em>Explicación:</em> Al usar referencias (&amp;), las variables apuntan al mismo valor en memoria. '
             . 'Cuando cambiamos $a, automáticamente cambian $b y $c.</p>';
    ?>

    <h2>Ejercicio 3</h2>
    <?php
        // comportamiento original, capturo toda la salida incluyendo warnings para luego imprimirla escapada dentro de <pre>
        
        ob_start();
        //  --- bloque que podria generar warnings ---
        $a = "PHP5";
        $z = array();
        $z[] = &$a;
        $b = "5a version de PHP";
        $c = $b * 10;       // puede generar warning
        $a .= $b;
        $b *= $c;           // puede generar warning
        $z[0] = "MySQL";

        var_dump($a);
        var_dump($b);
        var_dump($c);
        var_dump($z);
        // --- fin del bloque ---
        $dump = ob_get_clean();
        echo '<pre>' . htmlspecialchars($dump, ENT_QUOTES, 'UTF-8') . '</pre>';
    ?>

    <p>
        El <strong>warning</strong> aparece porque en <code>$c = $b*10;</code> se intenta
        multiplicar un string no numérico. PHP convierte ese valor en <code>0</code> automaticamente.
        Al final, debido a la referencia, todas las variables terminan con el valor
        <code>"MySQL"</code>.
    </p>

    <h2>Ejercicio 4</h2>
    <?php
        // capturo la salida de print/var_dump y la escapo para XHTML
        ob_start();
        echo "Desde \$GLOBALS:\n";
        // si las variables existen en globals, muestralas
        // si no, muestro el ejemplo
        if (isset($GLOBALS['a']) || isset($GLOBALS['b']) || isset($GLOBALS['c']) || isset($GLOBALS['z'])) {
            var_dump($GLOBALS['a'] ?? null);
            var_dump($GLOBALS['b'] ?? null);
            var_dump($GLOBALS['c'] ?? null);
            var_dump($GLOBALS['z'] ?? null);
        } else {
            // reproducir la salida esperada si no estan definidas en globals
            var_dump($a);
            var_dump($b);
            var_dump($c);
            var_dump($z);
        }
        $dump2 = ob_get_clean();
        echo '<pre>' . htmlspecialchars($dump2, ENT_QUOTES, 'UTF-8') . '</pre>';
    ?>

    <h2>Ejercicio 5</h2>
    <?php
        $a = "7 personas";
        $b = (integer) $a;
        $a = "9E3";
        $c = (double) $a;

        echo '<p>$a = ' . htmlspecialchars((string)$a, ENT_QUOTES, 'UTF-8') . '<br />'
                 . '$b = ' . htmlspecialchars((string)$b, ENT_QUOTES, 'UTF-8') . '<br />'
                 . '$c = ' . htmlspecialchars((string)$c, ENT_QUOTES, 'UTF-8') . '</p>';
    ?>

    <h2>Ejercicio 6</h2>
    <?php
        $a = "0";
        $b = "TRUE";
        $c = FALSE;
        $d = ($a OR $b);
        $e = ($a AND $c);
        $f = ($a XOR $b);

        ob_start();
        var_dump($a);
        var_dump($b);
        var_dump($c);
        var_dump($d);
        var_dump($e);
        var_dump($f);
        $dump3 = ob_get_clean();
        echo '<pre>' . htmlspecialchars($dump3, ENT_QUOTES, 'UTF-8') . '</pre>';

        echo '<p>Mostrar booleanos con <code>var_export</code>:</p>';
        echo '<p>c = ' . htmlspecialchars(var_export($c, true), ENT_QUOTES, 'UTF-8') . '<br />'
                 . 'e = ' . htmlspecialchars(var_export($e, true), ENT_QUOTES, 'UTF-8') . '</p>';
    ?>

    <h2>Ejercicio 7</h2>
    <?php
        // muestro la informacion del servidor y del cliente, escapada y con BR autoclosed
        echo '<p>Apache/PHP: ' . htmlspecialchars($_SERVER['SERVER_SOFTWARE'] ?? '', ENT_QUOTES, 'UTF-8') . '<br />'
                 . 'Sistema operativo servidor: ' . htmlspecialchars(PHP_OS, ENT_QUOTES, 'UTF-8') . '<br />'
                 . 'Idioma navegador: ' . htmlspecialchars($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '', ENT_QUOTES, 'UTF-8') . '<br /></p>';
    ?>
</body>
</html>
