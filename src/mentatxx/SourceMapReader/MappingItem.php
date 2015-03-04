<?php
/**
 * Created by PhpStorm.
 * User: mentat
 * Date: 03.03.2015
 * Time: 23:28
 */

namespace mentatxx\SourceMapReader;


class MappingItem {
    public $minifiedColumn;
    public $originalFile;
    public $originalLine;
    public $originalColumn;
    public $originalName = '';
}