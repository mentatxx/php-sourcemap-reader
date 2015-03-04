<?php
namespace mentatxx\SourceMapReader;


class MappingStream {

    /**
     * Encode mapping items to string
     *
     * @param MappingStreamItem[] $items
     * @return string
     */
    public static function encode($items) {
        $result = '';
        foreach($items as $item) {
            $result.=$item->toString();
        }
        return $result;
    }

    /**
     * @param $encodedString
     * @return MappingStreamItem[]
     */
    public static function decode($encodedString) {
        $stringLength = strlen($encodedString);
        for($i=0; $i<$stringLength;) {
            if ($encodedString[$i] === ',') {
                $i++;
                yield new MappingStreamItem(MappingStreamItem::$TYPE_GROUP, ';');
            } else
            if ($encodedString[$i] === ';') {
                $i++;
                yield new MappingStreamItem(MappingStreamItem::$TYPE_LINE, ';');
            } else {
                list($value, $i) = Base64Vlq::decode($encodedString, $i);
                yield new MappingStreamItem(MappingStreamItem::$TYPE_NUMBER, $value);
            }
        }
    }
}