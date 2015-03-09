<?php
namespace tests;

use mentatxx\SourceMapReader\MappingStream;
use mentatxx\SourceMapReader\MappingStreamItem;

class MappingStreamTest extends \PHPUnit_Framework_TestCase
{
    public function testEncode() {
        $stream = new MappingStream();
        $sample = [
            new MappingStreamItem(MappingStreamItem::$TYPE_LINE, ';'),
            new MappingStreamItem(MappingStreamItem::$TYPE_LINE, ';'),
            new MappingStreamItem(MappingStreamItem::$TYPE_LINE, ';'),
            new MappingStreamItem(MappingStreamItem::$TYPE_LINE, ';'),
            new MappingStreamItem(MappingStreamItem::$TYPE_NUMBER, 2),
            new MappingStreamItem(MappingStreamItem::$TYPE_NUMBER, 0),
            new MappingStreamItem(MappingStreamItem::$TYPE_NUMBER, 2),
            new MappingStreamItem(MappingStreamItem::$TYPE_NUMBER, 2),
            new MappingStreamItem(MappingStreamItem::$TYPE_GROUP, ','),
            new MappingStreamItem(MappingStreamItem::$TYPE_NUMBER, 2),
            new MappingStreamItem(MappingStreamItem::$TYPE_NUMBER, 0),
            new MappingStreamItem(MappingStreamItem::$TYPE_NUMBER, 0),
            new MappingStreamItem(MappingStreamItem::$TYPE_NUMBER, 2)
        ];
        $this->assertEquals(';;;;EAEE,EAAE', $stream->encode($sample));
    }

    public function testDecodeItem() {
        $sample = ';E,';
        $expected = [
            new MappingStreamItem(MappingStreamItem::$TYPE_LINE, ';'),
            new MappingStreamItem(MappingStreamItem::$TYPE_NUMBER, 2),
            new MappingStreamItem(MappingStreamItem::$TYPE_GROUP, ',')
        ];
        $itemNumber = 0;
        for ($i=0;$i<strlen($sample);) {
            list($item, $i) = MappingStreamItem::fromString($sample, $i);
            $this->assertEquals($expected[$itemNumber], $item);
            $itemNumber++;
        }
    }

}
