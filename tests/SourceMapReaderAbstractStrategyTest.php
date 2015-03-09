<?php
namespace tests;

use mentatxx\SourceMapReader\SourceMapReaderAbstractStrategy;

class SourceMapReaderAbstractStrategyTest extends \PHPUnit_Framework_TestCase {

    public function testSetSourceMap() {

        $stub = $this->getMockForAbstractClass(
            'mentatxx\\SourceMapReader\\SourceMapReaderAbstractStrategy',
            array(),
            '',
            false,
            true,
            true,
            array('walk')
        );
        $stub->expects($this->once())->method('walk')->will($this->returnValue([]));
    }
}
