# Marker Analysis


A very primitive example of how
the MetaMagic pico-framework could be used to
analyse "Marker Attributes".

1. [example.php](example.php) - entrypoint with a very basic logic

Entrypoint logic
```php

$deprecated_methods = MetaMagic::findSpells(
	refs: MyOldCode::class,
	attrs: \Deprecated::class,
	types: TargetType::MethodType,
);

foreach ($deprecated_methods as $method) {
	/** @var SpellMethod $method */
	print_r(
		"Deprecated method found: ".
		"{$method->target->reflection->class}::".
		"{$method->target->reflection->name}()\n"
	);
	/** @var \Deprecated $attr */
	$attr = $method->attr;
	print_r(
		"Deprecation message: ".
		"{$attr->message}\n"
	);
	echo "--------------\n";
}

```

Output:
```text
Deprecated method found: MyOldCode::obsoleteMethod1()
Deprecation message: I don't like this method 1
--------------
Deprecated method found: MyOldCode::obsoleteMethod3()
Deprecation message: I don't like this method 3
--------------
Deprecated method found: MyOldCode::obsoleteMethod5()
Deprecation message: I don't like this method 5
--------------
```