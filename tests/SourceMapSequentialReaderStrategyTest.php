<?php
namespace tests;

use mentatxx\SourceMapReader\MappingItem;
use mentatxx\SourceMapReader\MappingStreamItem;
use mentatxx\SourceMapReader\SourceMapSequentialReaderStrategy;

class SourceMapSequentialReaderStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testWalk() {
        $seqReader = new SourceMapSequentialReaderStrategy();
        $seqReader->setSourceMap(array(
            "version" => 3,
            "file" => "test.js",
            "sourceRoot" => "",
            "sources" => [ "test.coffee" ],
            "names" => [],
            "mappings" => "AAAA,QAASA,UAITA,MAAMC,UAAUC,QAAU,WACxBC,QAAQC,IAAI;BCQQ,UAAUC"
        ));
        $expected = array(
            MappingItem::withParameters(0, 0, 0, 0, 0),
            MappingItem::withParameters(0, 8, 0, 0, 9),
            MappingItem::withParameters(0, 18, 0, 4, 0),
            MappingItem::withParameters(0, 24, 0, 4, 6, 1),
            MappingItem::withParameters(0, 34, 0, 4, 16, 2),
            MappingItem::withParameters(0, 42, 0, 4, 26),
            MappingItem::withParameters(0, 53, 0, 5, 2, 3),
            MappingItem::withParameters(0, 61, 0, 5, 10, 4),
            MappingItem::withParameters(0, 65, 0, 5, 14),
            MappingItem::withParameters(1, 0, 1, 13, 22),
            MappingItem::withParameters(1, 10, 1, 13, 32, 5),

        );
        $itemNumber = 0;
        foreach ($seqReader->walk() as $item) {
            $this->assertEquals($expected[$itemNumber], $item);
            $itemNumber++;
        }
    }

}
