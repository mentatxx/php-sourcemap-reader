<?php
namespace tests;

use mentatxx\SourceMapReader\MappingItem;
use mentatxx\SourceMapReader\SourceMapReader;

class SourceMapReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testSortSources() {
        $sample = [ [0, 8], [10, 1], [0, 10], [0, 30] ];
        $expected = [ [0, 8], [0, 10], [0, 30], [10, 1] ];
        $sourceMapReader = new SourceMapReader();
        $sourceMapReader->sortSources($sample);
        $this->assertEquals($expected, $sample);
    }

	public function testGetSources () {
		$sourceMap = '{ "version" : 3, "file" : "test.js", "sourceRoot" : "", "sources" : [ "test.coffee" ], "names" : [], "mappings" : "AAAA,QAASA,UAITA,MAAMC,UAAUC,QAAU,WACxBC,QAAQC,IAAI;BCQQ,UAAUC" }';
        $sourceMapReader = new SourceMapReader();
        $sourceMapReader->setSourceMap($sourceMap);
        $sample = [ [0, 8], [10, 1], [0, 10], [0, 30] ];
        $expected = [
            MappingItem::withParameters(0, 8, 0, 0, 9),
            MappingItem::withParameters(0, 8, 0, 0, 9),
            MappingItem::withParameters(0, 24, 0, 4, 6, 1),
            MappingItem::withParameters(1, 10, 1, 13, 32, 5)
        ];
        $result = [];
        foreach($sourceMapReader->getSources($sample) as $item) {
            $result[]=$item;
        }
        $this->assertEquals($expected, $result);
	}

}
