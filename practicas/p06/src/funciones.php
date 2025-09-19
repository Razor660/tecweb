<?php
// src/funciones.php
// Funciones para la P06

/**
 * Ejercicio 1
 * Comprueba si $n es múltiplo de 5 y de 7
 * Devuelve array asociativo con 'mul5' y 'mul7' (booleanos) y 'both' (ambos)
 */
function esMultiplo5y7($n) {
    $n = intval($n);
    $mul5 = ($n % 5 === 0);
    $mul7 = ($n % 7 === 0);
    return ['mul5' => $mul5, 'mul7' => $mul7, 'both' => ($mul5 && $mul7)];
}

/**
 * Ejercicio 2
 * Genera ternas aleatorias hasta obtener la secuencia impar, par, impar
 * Retorna array con 'matriz' => array de filas (cada fila = array de 3 numeros),
 * 'iteraciones' => numero de filas, 'numeros_generados' => iteraciones*3
 */
function generar_hasta_impar_par_impar($min = 0, $max = 1000) {
    $filas = [];
    while (true) {
        $a = rand($min, $max);
        $b = rand($min, $max);
        $c = rand($min, $max);
        $filas[] = [$a, $b, $c];
        $is_impar_par_impar = ($a % 2 !== 0) && ($b % 2 === 0) && ($c % 2 !== 0);
        if ($is_impar_par_impar) break;
    }
    $iter = count($filas);
    return ['matriz' => $filas, 'iteraciones' => $iter, 'numeros_generados' => $iter * 3];
}
