<?php

namespace ChaseIsabelle\RESPHP;

abstract class Decoder
{
    public static function decode(string $string): array
    {
        $lines = explode("\r\n", $string);

        if (array_pop($lines) !== '') {
            throw new \Exception('malformed input: no terminating delimiter');
        }

        return self::lines($lines, count($lines));
    }

    protected static function lines(array &$lines, int $count): array
    {
        $cursor = 0;
        $buffer = [];

        while ($lines && $cursor++ < $count) {
            $line = array_shift($lines);

            if (!$line) {
                throw new \Exception('malformed input: empty/invalid line');
            }

            $prefix = $line[0] ?? '';
            $suffix = substr($line, 1);

            switch ($prefix) {
                case '+':
                    $buffer[] = $suffix;

                    break;
                case '-':
                    $buffer[] = new \Exception($suffix);

                    break;
                case '$':
                    if ($suffix === '-1') {
                        $buffer[] = null;

                        break;
                    }

                    $line = array_shift($lines);

                    if (!is_string($line) || intval($suffix) !== strlen($line)) {
                        throw new \Exception('malformed input: bulk string');
                    }

                    $buffer[] = $line;

                    break;
                case ':':
                    $buffer[] = intval($suffix);

                    break;
                case '*':
                    $buffer[] = self::lines($lines, intval($suffix));

                    break;
                default:
                    throw new \Exception('malformed input: empty/invalid prefix');
            }
        }

        return $buffer;
    }
}
