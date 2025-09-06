<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Práctica 4</title>
</head>
<body>
    <h2>Ejercicio 1</h2>
    <p>Determina cuál de las siguientes variables son válidas y explica por qué:</p>
    <p>$_myvar,  $_7var,  myvar,  $myvar,  $var7,  $_element1, $house*5</p>
        <?php
        $_myvar = "válida";
        $_7var = "válida";
        // myvar; falta el $
        $myvar = "válida";
        $var7 = "válida";
        $_element1 = "válida";
        // $house*5; caracteres inválidos

        echo "<ul>
            <li>\$_myvar válida (inicia con guion bajo).</li>
            <li>\$_7var válida (inicia con guion bajo).</li>
            <li>myvar inválida (no inicia con \$).</li>
            <li>\$myvar válida (inicia con letra).</li>
            <li>\$var7 válida (letra seguida de número).</li>
            <li>\$_element1 válida (guion bajo permitido).</li>
            <li>\$house*5 inválida (* no permitido en nombre).</li>
        </ul>";
    ?>

    <h2>Ejercicio 2</h2>
    <?php
        $a = "ManejadorSQL";
        $b = "MySQL";
        $c = &$a;

        echo "<p><strong>Primer bloque:</strong></p>";
        echo "\$a = $a<br>";
        echo "\$b = $b<br>";
        echo "\$c = $c<br>";

        $a = "PHP server";
        $b = &$a;

        echo "<p><strong>Segundo bloque:</strong></p>";
        echo "\$a = $a<br>";
        echo "\$b = $b<br>";
        echo "\$c = $c<br>";

        echo "<p><em>Explicación:</em> Al usar referencias 
        las variables apuntan al mismo valor en memoria. 
        Cuando cambiamos \$a, automáticamente cambian \$b y \$c.</p>";
    ?>

    <h2>Ejercicio 3</h2>
    <?php
        $a = "PHP5";
        $z[] = &$a;
        $b = "5a version de PHP";
        $c = $b * 10; // conversión implícita (string → número)
        $a .= $b; // concatenación
        $b *= $c; // conversión implícita (string → número)
        $z[0] = "MySQL";

        echo "<pre>";
        var_dump($a, $b, $c, $z);
        echo "</pre>";
    ?>


</body>
</html>