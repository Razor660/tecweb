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

/**
 * Ejercicio 3 - Variante while
 * Encuentra el primer numero aleatorio (entre min y max) que sea múltiplo de $divisor.
 * Devuelve ['numero'=>..., 'iteraciones'=>...] (iteraciones = intentos)
 */
function primer_multiplo_while($divisor, $min = 1, $max = 10000, $maxAttempts = 1000000) {
    $divisor = max(1, intval($divisor));
    $count = 0;
    while ($count < $maxAttempts) {
        $count++;
        $n = rand($min, $max);
        if ($n % $divisor === 0) {
            return ['numero' => $n, 'iteraciones' => $count];
        }
    }
    return ['numero' => null, 'iteraciones' => $count];
}

/**
 * Ejercicio 3 - Variante do-while
 */
function primer_multiplo_do_while($divisor, $min = 1, $max = 10000, $maxAttempts = 1000000) {
    $divisor = max(1, intval($divisor));
    $count = 0;
    do {
        $count++;
        $n = rand($min, $max);
        if ($n % $divisor === 0) {
            return ['numero' => $n, 'iteraciones' => $count];
        }
    } while ($count < $maxAttempts);
    return ['numero' => null, 'iteraciones' => $count];
}

/**
 * Ejercicio 4
 * Crea arreglo con índices 97..122 y valores 'a'..'z'
 */
function crear_arreglo_ascii() {
    $arr = [];
    for ($i = 97; $i <= 122; $i++) {
        $arr[$i] = chr($i);
    }
    return $arr;
}

/**
 * Ejercicio 5
 * Valida edad y sexo: retorna true si sexo femenino y edad en [18,35]
 * (Se asume sexo 'f' o 'femenino' en minusculas; normalize antes de pasar)
 */
function es_mujer_en_rango($edad, $sexo) {
    $edad = intval($edad);
    $sexo = strtolower(trim($sexo));
    $esFemenino = ($sexo === 'f' || $sexo === 'femenino' || $sexo === 'female' || $sexo === 'mujer');
    return $esFemenino && ($edad >= 18 && $edad <= 35);
}

/**
 * Ejercicio 6
 * Devuelve un arreglo asociativo con 15 autos (matricula => datos)
 * Matricula formato LLLNNNN (ejemplo: ABC1234)
 */
function registro_parque_vehicular() {
    return [
        'UBN6338' => [
            'Auto' => ['marca'=>'HONDA','modelo'=>'2020','tipo'=>'camioneta'],
            'Propietario'=>['nombre'=>'Alfonzo Esparza','ciudad'=>'Puebla, Pue.','direccion'=>'C.U., Jardines de San Manuel']
        ],
        'UBN6339' => [
            'Auto' => ['marca'=>'MAZDA','modelo'=>'2019','tipo'=>'sedan'],
            'Propietario'=>['nombre'=>'Ma. del Consuelo Molina','ciudad'=>'Puebla, Pue.','direccion'=>'97 oriente']
        ],
        'ABC1234' => [
            'Auto'=>['marca'=>'NISSAN','modelo'=>'2018','tipo'=>'sedan'],
            'Propietario'=>['nombre'=>'Juan Pérez','ciudad'=>'Ciudad','direccion'=>'Calle Falsa 123']
        ],
        'DEF5678' => [
            'Auto'=>['marca'=>'TOYOTA','modelo'=>'2021','tipo'=>'hachback'],
            'Propietario'=>['nombre'=>'María López','ciudad'=>'Ciudad','direccion'=>'Av. Siempre Viva 7']
        ],
        'GHI9012' => [
            'Auto'=>['marca'=>'FORD','modelo'=>'2017','tipo'=>'camioneta'],
            'Propietario'=>['nombre'=>'Carlos Ruiz','ciudad'=>'Ciudad','direccion'=>'Bosques 12']
        ],
        'JKL3456' => [
            'Auto'=>['marca'=>'CHEVROLET','modelo'=>'2016','tipo'=>'sedan'],
            'Propietario'=>['nombre'=>'Ana Torres','ciudad'=>'Ciudad','direccion'=>'Lago 45']
        ],
        'MNO7890' => [
            'Auto'=>['marca'=>'KIA','modelo'=>'2015','tipo'=>'hachback'],
            'Propietario'=>['nombre'=>'Diego Sánchez','ciudad'=>'Ciudad','direccion'=>'Río 22']
        ],
        'PQR2345' => [
            'Auto'=>['marca'=>'HYUNDAI','modelo'=>'2014','tipo'=>'camioneta'],
            'Propietario'=>['nombre'=>'Sofía Gómez','ciudad'=>'Ciudad','direccion'=>'Olmo 9']
        ],
        'STU6789' => [
            'Auto'=>['marca'=>'VOLKSWAGEN','modelo'=>'2022','tipo'=>'sedan'],
            'Propietario'=>['nombre'=>'Luis Martínez','ciudad'=>'Ciudad','direccion'=>'Cerro 3']
        ],
        'VWX0123' => [
            'Auto'=>['marca'=>'BMW','modelo'=>'2020','tipo'=>'sedan'],
            'Propietario'=>['nombre'=>'Patricia Díaz','ciudad'=>'Ciudad','direccion'=>'Simón 11']
        ],
        'YZA4567' => [
            'Auto'=>['marca'=>'AUDI','modelo'=>'2019','tipo'=>'hachback'],
            'Propietario'=>['nombre'=>'Ricardo Flores','ciudad'=>'Ciudad','direccion'=>'Oasis 7']
        ],
        'BCD8901' => [
            'Auto'=>['marca'=>'SUBARU','modelo'=>'2018','tipo'=>'camioneta'],
            'Propietario'=>['nombre'=>'Verónica Peña','ciudad'=>'Ciudad','direccion'=>'Mirador 4']
        ],
        'EFG2346' => [
            'Auto'=>['marca'=>'SUZUKI','modelo'=>'2021','tipo'=>'hachback'],
            'Propietario'=>['nombre'=>'Hugo Ramírez','ciudad'=>'Ciudad','direccion'=>'Encino 66']
        ],
        'HIJ6780' => [
            'Auto'=>['marca'=>'RENAULT','modelo'=>'2017','tipo'=>'sedan'],
            'Propietario'=>['nombre'=>'Laura Méndez','ciudad'=>'Ciudad','direccion'=>'Pino 2']
        ],
        'KLM3451' => [
            'Auto'=>['marca'=>'PEUGEOT','modelo'=>'2016','tipo'=>'sedan'],
            'Propietario'=>['nombre'=>'Roberto Castillo','ciudad'=>'Ciudad','direccion'=>'Mar 33']
        ],
    ];
}

/**
 * Busca por matricula (case-insensitive) en el registro y devuelve el registro o null
 */
function buscar_por_matricula($registro, $matricula) {
    $matricula = strtoupper(trim($matricula));
    return $registro[$matricula] ?? null;
}
