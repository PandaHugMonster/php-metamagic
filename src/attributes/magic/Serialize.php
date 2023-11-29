<?php

namespace spaf\metamagic\attributes\magic;

use Attribute;
use spaf\metamagic\basic\BasicDecoratorAlikeAttribute;
use spaf\metamagic\components\Spell;

/**
 * Magic Method Serialize through Attribute
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Serialize extends BasicDecoratorAlikeAttribute {

}
