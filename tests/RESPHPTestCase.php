<?php

namespace Test\ChaseIsabelle\RESPHP;

use Exception;
use PHPUnit\Framework\TestCase;

abstract class RESPHPTestCase extends TestCase
{
    protected static function randValue()
    {
        switch (rand(1, 4)) {
            case 1:
                return null;
            case 2:
                return 'string';
            case 3:
                return rand(1, 10);
            case 4:
            default:
                break;
        }

        return new Exception('error');
    }
}
