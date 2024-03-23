<?php

namespace spaf\metamagic\basic;

use Attribute;

/**
 */
#[Attribute]
abstract class BasicMetaMagicAttribute extends BasicAttribute {

	static abstract function process($entity, ...$args);

}
