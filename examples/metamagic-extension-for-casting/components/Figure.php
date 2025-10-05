<?php


class Figure {
	use ToStrTrait;

	public array $data;

	public function __construct(array $data) {
		$this->data = $data;
	}

	#[ToStr]
	public function toStr() {
		$res = "";

		foreach ($this->data as $sub) {
			$res .= "({$sub[0]}, {$sub[1]}), ";
		}

		$class = $this::class;
		return "<{$class} path='[{$res}]'>";
	}

}

