<?php
/**
 * Created by PhpStorm.
 * User: mentat
 * Date: 03.03.2015
 * Time: 22:55
 */

namespace mentatxx\SourceMapReader;


class SourceMapSequentialReaderStrategy extends SourceMapReaderAbstractStrategy {

    /**
     *
     *
     * @return MappingItem[]
     * @throws \Exception
     */
    public function walk()
    {
        $this->minifiedLine = 0;
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
                $this->minifiedColumn = 0;
                $this->minifiedLine++;

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