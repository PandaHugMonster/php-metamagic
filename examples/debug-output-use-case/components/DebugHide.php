<?php

#[Attribute(Attribute::TARGET_PROPERTY)]
class DebugHide {

	function __construct(
		public $text = "****",
	) {}
}
