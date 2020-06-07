<?php
// just some quick tests, will add phpunit later
require_once __DIR__ . '/src/Encoder.php';
require_once __DIR__ . '/src/Decoder.php';

use ChaseIsabelle\RESPHP\Decoder;
use ChaseIsabelle\RESPHP\Encoder;

$pass = true;

$tests = [
    ["foo"],
    "+foo\r\n",

    ["1234"],
    "+1234\r\n",

    [1234],
    ":1234\r\n",

    [new Exception('poop')],
    "-poop\r\n",

    [['poo']],
    "*1\r\n+poo\r\n",

    [[['a'], ['b']]],
    "*2\r\n*1\r\n+a\r\n*1\r\n+b\r\n",

    [["foo", 1234, new Exception('poop')]],
    "*3\r\n+foo\r\n:1234\r\n-poop\r\n",

    [['a', ['b'], 'c']],
    "*3\r\n+a\r\n*1\r\n+b\r\n+c\r\n"
];

foreach (array_chunk($tests, 2) as $test) {
    $input  = $test[0];
    $output = $test[1];
    $encoded = Encoder::encode($input);

    print('expected: ' . addcslashes($output, "\r\n") . "\n");
    print('actual:   ' . addcslashes($encoded, "\r\n") . "\n\n");

    if ($encoded !== $output) {
        print("failure\n");
    }

    $decoded = Decoder::decode($encoded);

//    print('expected: ' . addcslashes(is_array($input) ? implode(', ', $input) : $input, "\r\n") . "\n");
//    print('expected: ' . addcslashes(implode(', ', $input), "\r\n") . "\n");

    print(json_encode($input, JSON_PRETTY_PRINT) . "\n");
    print(json_encode($decoded, JSON_PRETTY_PRINT) . "\n");

    if ($decoded !== $input) {
        print("failure\n");
    }

    print("\n");
}
