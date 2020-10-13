<?php

namespace Test\ChaseIsabelle\RESPHP;

use ChaseIsabelle\RESPHP\Decoder;
use ChaseIsabelle\RESPHP\Encoder;
use Exception;

class DecoderTest extends RESPHPTestCase
{
    public function testDecodeString()
    {
        $output = 'simple';
        $input  = "+$output\r\n";

        $this->assertEquals([$output], Decoder::decode($input));

        $output = 'bulk';
        $input  = "\$4\r\n$output\r\n";

        $this->assertEquals([$output], Decoder::decode($input));
    }

    public function testDecodeInt()
    {
        $output = rand(1, 100000);
        $input  = ":$output\r\n";

        $this->assertEquals([$output], Decoder::decode($input));
    }

    public function testDecodeNull()
    {
        $output = null;
        $input  = "\$-1\r\n";

        $this->assertEquals([$output], Decoder::decode($input));
    }

    public function testDecodeError()
    {
        $output = 'error';
        $input  = "-$output\r\n";

        $this->assertEquals([new Exception($output)], Decoder::decode($input));
    }

    public function testDecodeArray()
    {
        $output = ['a', 'b', 'c'];
        $input  = "*3\r\n+a\r\n+b\r\n+c\r\n";

        $this->assertEquals([$output], Decoder::decode($input));
    }

    public function testDecode()
    {
        $output = ['s', 1, null, new Exception('e'), [2]];
        $input  = "+s\r\n:1\r\n\$-1\r\n-e\r\n*1\r\n:2\r\n";

        $this->assertEquals($output, Decoder::decode($input));
    }

    public function testDecodeEncode()
    {
        $input = "+s\r\n:1\r\n\$-1\r\n-e\r\n*1\r\n:2\r\n";

        $this->assertEquals($input, Encoder::encode(Decoder::decode($input)));
    }
}
