<?php

use spaf\metamagic\enums\TargetType;
use spaf\metamagic\MetaMagic;
use spaf\metamagic\spells\SpellMethod;

function cast(mixed $val, CastTo | string $to) {
	$attr = $to instanceof CastTo
		?$to->value
		:ToObj::class;

	$filter = function ($spell) use ($to): SpellMethod | null {
		/** @var SpellMethod $spell */
		if ($spell->attr instanceof ToObj && $spell->attr->class != $to) {
			return null;
		}
		return $spell;
	};

	$generator = MetaMagic::findSpells(
		refs: $val,
		attrs: $attr,
		types: TargetType::MethodType,
		filter: $filter,
	);

	/** @var SpellMethod $spell */
	foreach ($generator as $spell) {
		return $spell($to);
	}

	throw new ValueError(
		"Can't cast ".print_r($val, true)
		." into ".print_r($to, true)." type"
	);
}
