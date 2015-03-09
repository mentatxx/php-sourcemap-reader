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
    protected $minifiedLine;
    protected $minifiedColumn;
    protected $originalLine;
    protected $originalFile;
    protected $originalColumn;
    protected $originalName;

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

    protected function convertSegmentToMappingItem($segment) {
        $cnt = count($segment);
        if ($cnt === 0) {
            return null;
        }
        if ($cnt !== 1 && $cnt !== 4 && $cnt !== 5) {
            throw new \Exception("SourceMap group should include 4 or 5 items");
        }
        if ($cnt === 1) {
            $this->minifiedColumn = $segment[0] + $this->minifiedColumn;
            return null;
        }
        $mappingItem = new MappingItem();
        $mappingItem->minifiedLine = $this->minifiedLine;
        $this->minifiedColumn = $mappingItem->minifiedColumn = $segment[0] + $this->minifiedColumn;
        $this->originalFile = $mappingItem->originalFile = $segment[1] + $this->originalFile;
        $this->originalLine = $mappingItem->originalLine = $segment[2] + $this->originalLine;
        $this->originalColumn = $mappingItem->originalColumn = $segment[3] + $this->originalColumn;
        if (count($segment)>4) {
            $this->originalName = $mappingItem->originalName = $segment[4] + $this->originalName;
        }
        return $mappingItem;
    }

    abstract public function walk();
}