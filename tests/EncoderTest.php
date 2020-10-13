<?php

namespace Test\ChaseIsabelle\RESPHP;

use ChaseIsabelle\RESPHP\Decoder;
use ChaseIsabelle\RESPHP\Encoder;
use Exception;

class EncoderTest extends RESPHPTestCase
{
    public function testEncodeString()
    {
        $input  = 'this is a simple string';
        $output = "+$input\r\n";

        $this->assertEquals($output, Encoder::string($input));

        $input  = "this is a bulk string¡";
        $output = '$' . strlen($input) . "\r\n$input\r\n";

        $this->assertEquals($output, Encoder::string($input));
    }

    public function testEncodeError()
    {
        $input  = 'this is an error';
        $output = "-$input\r\n";

        $this->assertEquals($output, Encoder::error(new Exception($input)));
    }

    public function testEncodeInt()
    {
        $input  = rand(1, 100000);
        $output = ":$input\r\n";

        $this->assertEquals($output, Encoder::int($input));
    }

    public function testEncodeNull()
    {
        $output = "$-1\r\n";

        $this->assertEquals($output, Encoder::null());
    }

    public function testEncodeArray()
    {
        $input  = ['string', 1, null, new Exception('error')];
        $output = "*4\r\n+string\r\n:1\r\n\$-1\r\n-error\r\n";

        $this->assertEquals($output, Encoder::array($input));
    }

    public function testEncodeValue()
    {
        $input  = 'string';
        $output = "+$input\r\n";

        $this->assertEquals($output, Encoder::value($input));

        $input  = rand(1, 100000);
        $output = ":$input\r\n";

        $this->assertEquals($output, Encoder::value($input));

        $input  = null;
        $output = "\$-1\r\n";

        $this->assertEquals($output, Encoder::value($input));

        $input  = new Exception('error');
        $output = "-error\r\n";

        $this->assertEquals($output, Encoder::value($input));

        $input  = ['s', 1, null, new Exception('e')];
        $output = "*4\r\n+s\r\n:1\r\n\$-1\r\n-e\r\n";

        $this->assertEquals($output, Encoder::value($input));
    }

    public function testEncode()
    {
        $input  = ['s', 1, null, new Exception('e')];
        $output = "+s\r\n:1\r\n\$-1\r\n-e\r\n";

        $this->assertEquals($output, Encoder::encode($input));
    }

    public function testBackToBack()
    {
        $input = ['s', 1, null, new Exception('e')];

        $this->assertEquals($input, Decoder::decode(Encoder::encode($input)));
    }
}
