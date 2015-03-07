<?php
namespace tests;

use mentatxx\SourceMapReader\Base64Vlq;

class Base64VlqTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnsInstance () {
        $instance = Base64Vlq::getInstance();
        $this->assertInstanceOf('mentatxx\SourceMapReader\Base64Vlq', $instance);
    }

}
