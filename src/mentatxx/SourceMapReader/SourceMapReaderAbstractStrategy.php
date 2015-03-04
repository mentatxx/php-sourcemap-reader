<?php
namespace mentatxx\SourceMapReader;


abstract class SourceMapReaderAbstractStrategy {
    public $version;
    public $file;
    public $sources;
    public $names;
    public $mappings;
    public $sourcesContent;
    //
    public function setSourceMap($sourceMap)
    {
        if (is_string($sourceMap)) {
            $map = json_decode($sourceMap, true);
        } else {
            $map = $sourceMap;
        }
        //
        $this->version = $map['version'];
        $this->file = $map['file'];
        $this->sources = isset($map['sources'])?$map['sources']:array();
        $this->names = isset($map['names'])?$map['names']:array();
        $this->mappings = isset($map['mappings'])?$map['mappings']:'';
        $this->sourcesContent = isset($map['sourcesContent'])?$map['sourcesContent']:array();
    }

    abstract public function walk();
}