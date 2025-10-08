<?php

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class HttpRoute {

	function __construct(
		public $path = null,
	) {}

}
