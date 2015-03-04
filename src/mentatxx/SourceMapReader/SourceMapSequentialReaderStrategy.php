<?php
/**
 * Created by PhpStorm.
 * User: mentat
 * Date: 03.03.2015
 * Time: 22:55
 */

namespace mentatxx\SourceMapReader;


class SourceMapSequentialReaderStrategy extends SourceMapReaderAbstractStrategy {
    private $minifiedColumn;
    private $originalLine;
    private $originalFile;
    private $originalColumn;
    private $originalName;

    private function convertSegmentToMappingItem($segment) {
        $cnt = count($segment);
        if ($cnt === 0) {
            return null;
        }
        if ($cnt !== 4 && $cnt !== 5) {
            throw new \Exception("SourceMap group should include 4 or 5 items");
        }
        $mappingItem = new MappingItem();
        $this->minifiedColumn = $mappingItem->minifiedColumn = $segment[0] + $this->minifiedColumn;
        $this->originalFile = $mappingItem->originalFile = $segment[1] + $this->originalFile;
        $this->originalLine = $mappingItem->originalLine = $segment[2] + $this->originalLine;
        $this->originalColumn = $mappingItem->originalColumn = $segment[3] + $this->originalColumn;
        if (count($segment)>4) {
            $this->originalName = $mappingItem->originalName = $segment[4] + $this->originalName;
        }
        return $mappingItem;
    }

    /**
     *
     *
     * @return MappingItem[]
     * @throws \Exception
     */
    public function walk()
    {
        $this->minifiedColumn = 0;
        $this->originalLine = 0;
        $this->originalColumn = 0;
        $this->originalFile = 0;
        $this->originalName = 0;
        $segment = array();
        foreach(MappingStream::decode($this->mappings) as $item) {
            if ($item->type === MappingStreamItem::$TYPE_GROUP) {
                $mappingItem = $this->convertSegmentToMappingItem($segment);
                if (!$mappingItem) {
                    continue;
                }
                yield $mappingItem;
                $segment = array();
            } else if ($item->type === MappingStreamItem::$TYPE_LINE) {
                $mappingItem = $this->convertSegmentToMappingItem($segment);
                if ($mappingItem) {
                    yield $mappingItem;
                }
                $segment = array();
                $this->originalColumn = 0;
                $this->originalLine++;

            } else if ($item->type === MappingStreamItem::$TYPE_NUMBER) {
                $segment[]=$item->value;
            } else {
                throw new \Exception('Invalid MappingItem type exception');
            }
        }
        $mappingItem = $this->convertSegmentToMappingItem($segment);
        if ($mappingItem) {
            yield $mappingItem;
        }
    }
}