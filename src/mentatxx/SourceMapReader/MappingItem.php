<?php
namespace mentatxx\SourceMapReader;

class MappingItem {
    public $minifiedLine;
    public $minifiedColumn;
    public $originalFile;
    public $originalLine;
    public $originalColumn;
    public $originalName = 0;

    public static function  withParameters($minifiedLine, $minifiedColumn, $originalFile, $originalLine, $originalColumn, $originalName = 0) {
        $item = new MappingItem();
        $item->minifiedLine = $minifiedLine;
        $item->minifiedColumn = $minifiedColumn;
        $item->originalFile = $originalFile;
        $item->originalColumn = $originalColumn;
        $item->originalLine = $originalLine;
        $item->originalName = $originalName;
        return $item;
    }
}