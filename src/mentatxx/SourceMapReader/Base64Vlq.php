<?php
namespace mentatxx\SourceMapReader;


class Base64Vlq {
    // Constants
    private static $VLQ_BASE_SHIFT = 5;
    private static $MASK = 0x1F; // == (1 << SHIFT) == 0b00011111
    private static $CONTINUATION_BIT = 0x20; // == (MASK - 1 ) == 0b00100000
    private $BASE64_TO_INT = array();
    private $INT_TO_BASE64 = array();

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
            $this->BASE64_TO_INT[$char] = $i;
            $this->INT_TO_BASE64[$i] = $char;
        }
    }

    private static function toVlqSigned($value) {
        return $value<0 ? ((-$value)<<1)+1 : ($value << 1);
    }

    private static function fromVlqSigned($value) {
        return $value & 1 ? -self::shiftRight($value, 1) : self::shiftRight($value, 1);
    }

    private static function shiftRight($a, $b) {
        return ($a >= 0) ? ($a >> $b) : ($a >> $b) & (PHP_INT_MAX >> ($b-1));
    }

    public function encode($aValue) {
        self::getInstance();
        $result = "";
        $vlq = self::toVlqSigned($aValue);

        do {
            $digit = $vlq & self::$MASK;
            $vlq = self::shiftRight($vlq, self::$VLQ_BASE_SHIFT);
            if ($vlq > 0) {
                $digit |= self::$CONTINUATION_BIT;
            }
            $result .= $this->INT_TO_BASE64[$digit];
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
    public function decode($encodedString, $position) {
        self::getInstance();
        $result = 0;
        $i = 0;
        do {
            $digit = $this->BASE64_TO_INT[$encodedString[$position+$i]];
            $result |= ($digit & self::$MASK) << ($i*self::$VLQ_BASE_SHIFT);
            $i++;
        } while ($digit & self::$CONTINUATION_BIT);

        return array(self::fromVLQSigned($result), $position+$i);
    }


}