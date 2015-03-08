<?php
namespace tests;

use mentatxx\SourceMapReader\Base64Vlq;

class Base64VlqTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnsInstance () {
        $instance = Base64Vlq::getInstance();
        $this->assertInstanceOf('mentatxx\SourceMapReader\Base64Vlq', $instance);
    }

    public function testDecode() {
        $instance = Base64Vlq::getInstance();
        $samples = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefA';
        $expected = [0,0,1,-1,2,-2,3,-3,4,-4,5,-5,6,-6,7,-7,8,-8,9,-9,10,-10,11,-11,12,-12,13,-13,14,-14,15,-15,0];
        for($i=0; $i<32; $i++) {
            list($vlqSigned, $position) = $instance->decode($samples, $i);
            $this->assertEquals($expected[$i], $vlqSigned, "VLQ at $i");
            $this->assertEquals($position, $i+1, "VLQ position shift $i");
        }
        $samples = 'ggq2m3Dhgq2m3D';
        $expected = [2000000000,-2000000000];
        $sampleNumber = 0;
        for ($i=0; $i<strlen($samples);) {
            $position = $i;
            list($vlqSigned, $i) = $instance->decode($samples, $position);
            $this->assertEquals($expected[$sampleNumber], $vlqSigned, "VLQ continuous at $position");
            $sampleNumber++;
        }
    }


    public function testEncode() {
        $instance = Base64Vlq::getInstance();
        $samples = [0,1,-1,2,-2,3,-3,4,-4,5,-5,6,-6,7,-7,8,-8,9,-9,10,-10,11,-11,12,-12,13,-13,14,-14,15,-15];
        $result = '';
        foreach($samples as $sample) {
            $result.=$instance->encode($sample);
        }
        $this->assertEquals('ACDEFGHIJKLMNOPQRSTUVWXYZabcdef', $result, "VLQ encode");
        $this->assertEquals('ggq2m3D', $instance->encode(2000000000));
        $this->assertEquals('hgq2m3D', $instance->encode(-2000000000));
    }


}
