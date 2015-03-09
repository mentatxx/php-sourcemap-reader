<?php
namespace mentatxx\SourceMapReader;

use Symfony\Component\Config\Definition\Exception\Exception;

class SourceMapReader {
    protected $sourceMap;
    protected $reader;

    public function __construct()
    {
        $this->reader = new SourceMapSequentialReaderStrategy();
    }

    public function setSourceMap($sourceMap) {
        if (is_array($sourceMap)) {
            $this->sourceMap = $sourceMap;
        } else {
            $this->sourceMap = json_decode($sourceMap, true);
            if ($this->sourceMap === null) {
                throw new Exception("Invalid JSON in setSourceMap");
            }
        }
        $this->reader->setSourceMap($this->sourceMap);
    }

    public static function withFile($fileName) {
        $content = file_get_contents($fileName);
        $sourceMapReader =  new SourceMapReader();
        $sourceMapReader->setSourceMap($content);
        return $sourceMapReader;
    }

    public function sortSources(&$sources)
    {
        usort($sources, function($a, $b){
            $linesCompare = $a[0]-$b[0];
            if ($linesCompare === 0) {
                return $a[1]-$b[1];
            } else {
                return $linesCompare;
            }
        });
    }

    public function getSources($sources)
    {
        $this->sortSources($sources);
        $currentSource = 0;
        $lastItem = MappingItem::withParameters(0,0,0,0,0);
        foreach($this->reader->walk() as $mappingItem) {
            if ($currentSource >= count($sources)) {
                // all sources found
                return;
            }
            // are there any matches?
            while ($currentSource < count($sources)) {
                if ( ($sources[$currentSource][0] <= $mappingItem->minifiedLine) &&
                     ($sources[$currentSource][1] < $mappingItem->minifiedColumn) ) {
                    yield $lastItem;
                    $currentSource++;
                } else {
                    break;
                }
            }
            $lastItem = $mappingItem;
        }
        while ($currentSource < count($sources)) {
            yield $lastItem;
            $currentSource++;
        }
    }
}
