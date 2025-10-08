<?php


use spaf\metamagic\enums\TargetType;
use spaf\metamagic\MetaMagic;
use spaf\metamagic\spells\SpellClass;
use spaf\metamagic\spells\SpellMethod;


function runRoute($controllers, $path) {
	$filter_controller = function (SpellClass $spell) use ($path) {
		$obj = $spell->target->object;
		if (empty($obj)) {
			throw new ValueError("Only objects can be used for routing");
		}
		if (!$spell->attr instanceof HttpController) {
			return null;
		}

		$attr = $spell->attr;

		$name = $obj?->name ?? $attr->name;

		$name = "/{$name}";

		if (!str_starts_with($path, $name)) {
			return null;
		}

		return $spell;
	};

	$controller_spell = MetaMagic::findSpellOne(
		refs: $controllers,
		attrs: HttpController::class,
		types: TargetType::ClassType,
		filter: $filter_controller,
	);

	/** @var MyController $controller */
	$controller = $controller_spell->target->object;

	$filter_route = function (SpellMethod $spell) use ($path) {
		/** @var HttpRoute $attr */
		$attr = $spell->attr;
		if (!str_ends_with($path, $attr->path)) {
			return null;
		}

		return $spell;
	};

	/** @var SpellMethod $route_spell */
	$route_spell = MetaMagic::findSpellOne(
		refs: $controller,
		attrs: HttpRoute::class,
		types: TargetType::MethodType,
		filter: $filter_route,
	);

	$route_spell($path);
}