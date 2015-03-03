<?php
namespace mentatxx\SourceMapReader;


class Base64Vlq {
    // Constants
    private static $SHIFT = 5;
    private static $MASK = 0x1F; // == (1 << SHIFT) == 0b00011111
    private static $CONTINUATION_BIT = 0x20; // == (MASK - 1 ) == 0b00100000
    private static $BASE64_TO_INT = array();
    private static $INT_TO_BASE64 = array();

    private static $_instance = null;

    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new Base64VLQ();
        }
        return self::$_instance;
    }

    private function __construct()
    {
        foreach (str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/') as $i => $char) {
            self::$BASE64_TO_INT[$char] = $i;
            self::$INT_TO_BASE64[$i] = $char;
        }
    }

    private static function toVlqSigned($value) {
        if (PHP_INT_SIZE === 4) {
            return 0xffffffff & ($value < 0 ? ((-$value) << 1) + 1 : ($value << 1) + 0);
        } else if (PHP_INT_SIZE === 8) {
            return 0xffffffffffffffff & ($value < 0 ? ((-$value) << 1) + 1 : ($value << 1) + 0);
        } else {
            throw new \Exception('Not 32-bit and not 64-bit environment');
        }
    }

    private static function fromVlqSigned($value) {
        if (PHP_INT_SIZE === 4) {
            return $value & 1 ? self::shiftRight(~$value + 2, 1) | (-1 - 0x7fffffff) : self::shiftRight($value, 1);
        } else if (PHP_INT_SIZE === 8) {
            return $value & 1 ? self::shiftRight(~$value + 2, 1) | (-1 - 0x7fffffffffffffff) : self::shiftRight($value, 1);
        } else {
            throw new \Exception('Not 32-bit and not 64-bit environment');
        }
    }

    private static function shiftRight($a, $b) {
        return ($a >= 0) ? ($a >> $b) : ($a >> $b) & (PHP_INT_MAX >> ($b-1));
    }

    public static function encode($aValue) {
        $result = "";
        $vlq = self::toVlqSigned($aValue);

        do {
            $digit = $vlq & self::$MASK;
            $vlq = self::shiftRight($vlq, self::$SHIFT);
            if ($vlq > 0) {
                $digit |= self::$CONTINUATION_BIT;
            }
            $result .= self::$INT_TO_BASE64[$digit];
        } while ($vlq > 0);
        return $result;
    }

    /**
     * Return the value decoded from base 64 VLQ and updated.
     *
     * @param $encodedString
     * @param $position
     * @return array
     * @throws \Exception
     */
    public static function decode($encodedString, $position) {
        $vlq = 0;

        do {
            $digit = self::$BASE64_TO_INT[$encodedString[$position]];
            $vlq |= ($digit & self::$MASK) << ($position*self::$SHIFT);
            $position++;
        } while ($digit & self::$CONTINUATION_BIT);

        return array(self::fromVLQSigned($vlq), $position);
    }


}