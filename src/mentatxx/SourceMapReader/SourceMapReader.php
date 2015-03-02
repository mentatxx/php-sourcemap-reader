<?php
/**
 * php-sourcemap-reader
 * @package SourceMapReader
 * @version 0.1.0
 * @link https://github.com/mentatxx/php-sourcemap-reader
 * @author mentatxx <https://github.com/mentatxx>
 * @license https://github.com/mentatxx/php-sourcemap-reader/blob/master/LICENSE
 * @copyright Copyright (c) 2014, mentatxx 
 */

namespace mentatxx\SourceMapReader;

require(dirname(dirname(dirname(dirname(__FILE__)))) . '/vendor/autoload.php');

/**
 * The SourceMapReader class
 * @author mentatxx <https://github.com/mentatxx>
 * @since 0.1.0
 */
class SourceMapReader {

	/**
	 * A sample parameter
	 * @var int $myParam This is my parameter
	 * @since 0.1.0
	 */
	public $myParam = 0;

	/**
	 * A sample function that adds the $n param to $myParam
	 * @name increase
	 * @param int $n The number to add to $myParam
	 * @since 0.1.0
	 * @return object the SourceMapReader object
	 */
	public function increase ( $n ) {
		$this->myParam += $n;
		return $this;
	}

	/**
	 * A sample function that sets $myParam to 0
	 * @name negate
	 * @since 0.1.0
	 * @return object the SourceMapReader object
	 */
	public function negate (){
		$this->myParam = 0;
		return $this;
	}
}
?>