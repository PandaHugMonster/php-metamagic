<?php


use components\DefaultsAttr;

#[DefaultsAttr("my-bio-defaults.json")]
class MyBio {
	use DefaultsTrait;

	public null|string $name = null;
	public null|string $handle = null;
	public null|int $age = 0;
	public null|string $description = null;

	function __construct($name = null, $handle = null, $age = null, $description = null) {
		$this->defaultsInit();
		$this->name = $name ?? $this->name;
		$this->handle = $handle ?? $this->handle;
		$this->age = $age ?? $this->age;
		$this->description = $description ?? $this->description;
	}

}
