<?php


#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class HttpController {

	function __construct(
		public $name,
	) {}

}
