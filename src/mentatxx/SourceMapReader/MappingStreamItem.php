<?php
namespace mentatxx\SourceMapReader;

class MappingStreamItem {
    public static $TYPE_NUMBER = 1;
    public static $TYPE_GROUP = 2;
    public static $TYPE_LINE = 3;
    //
    public $type;
    public $value;
    //
    public function __construct($type, $value) {
        $this->type = $type;
        $this->value = $value;
    }

    /**
     * Returns string representation
     *
     * @return string
     * @throws \Exception
     */
    public function toString() {
        if ($this->type === self::$TYPE_GROUP) {
            return ',';
        } else
        if ($this->type === self::$TYPE_LINE) {
            return ';';
        } else
        if ($this->type === self::$TYPE_NUMBER) {
            return $this->value;
        } else {
            throw new \Exception('Unknown MappingStreamItem::Type in toString');
        }
    }

    /**
     * Convert string item to MappingStreamItem
     *
     * @param $string
     * @return MappingStreamItem
     */
    public static function fromString($string) {
        if ($string == ',') {
            return new MappingStreamItem(self::$TYPE_GROUP, $string);
        } else
        if ($string == ';') {
            return new MappingStreamItem(self::$TYPE_LINE, $string);
        } else {
            return new MappingStreamItem(self::$TYPE_NUMBER, $string);
        }
    }
}