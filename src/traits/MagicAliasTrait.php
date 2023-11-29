<?php

namespace spaf\metamagic\traits;

use Exception;
use spaf\metamagic\attributes\magic\Invoke;
use spaf\metamagic\MetaMagic;
use function get_parent_class;

trait MagicAliasTrait {

	function __invoke(...$args) {
		$spell = MetaMagic::find($this, Invoke::class, first: true);
		if (!$spell) {
			$parent_class = get_parent_class($this);
			if ($parent_class) {
				return parent::__invoke(...$args);
			}
			throw new Exception("No #[Invoke] attribute is assigned");
		}
		return $spell(...$args);
	}

}
