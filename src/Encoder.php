<?php

namespace ChaseIsabelle\RESPHP;

abstract class Encoder
{
    public static function encode(array $input): string
    {
        $buffer = '';

        foreach ($input as $value) {
            $buffer .= self::value($value);
        }

        return $buffer;
    }

    public static function string(string $string): string
    {
        $prefix = '+';

        if (preg_match('~[^\x20-\x7E\t\r\n]~', $string)) {
            $prefix = self::suffix('$' . strlen($string));
        }

        return $prefix . self::suffix($string);
    }

    public static function error(\Exception $error): string
    {
        return '-' . self::suffix($error->getMessage());
    }

    public static function int($int): string
    {
        return ':' . self::suffix($int);
    }

    public static function null(): string
    {
        return "$-1\r\n";
    }

    public static function array(array $array): string
    {
        $buffer = '*' . self::suffix(count($array));

        foreach ($array as $value) {
            $buffer .= self::value($value);
        }

        return $buffer;
    }

    public static function value($value): string
    {
        switch (true) {
            case is_array($value):
                return  self::array($value);
            case is_string($value):
                return self::string($value);
            case $value instanceof \Exception:
                return self::error($value);
            case is_int($value):
                return self::int($value);
            default:
                break;
        }

        throw new \Exception('unsupported type: ' . gettype($value));
    }

    protected static function suffix($scalar): string
    {
        return strval($scalar) . "\r\n";
    }
}
