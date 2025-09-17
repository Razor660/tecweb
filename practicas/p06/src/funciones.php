<?php
// src/funciones.php
// Funciones para la P06

/**
 * Ejercicio 1
 * Comprueba si $n es múltiplo de 5 y de 7
 * Devuelve array asociativo con 'mul5' y 'mul7' (booleans) y 'both' (ambos)
 */
function esMultiplo5y7($n) {
    $n = intval($n);
    $mul5 = ($n % 5 === 0);
    $mul7 = ($n % 7 === 0);
    return ['mul5' => $mul5, 'mul7' => $mul7, 'both' => ($mul5 && $mul7)];
}

