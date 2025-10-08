<?php

class Rect {
	use ToStrTrait;

	function __construct(
		public float $x1 = 0,
		public float $y1 = 0,

		public float $x2 = 0,
		public float $y2 = 0,
	) {}

	/**
	 * Method calculating area of a rectangle
	 *
	 * @return float
	 */

	#[ToFloat]
	public function toFloat(): float {
		$width = $this->x2 - $this->x1;
		$height = $this->y2 - $this->y1;

		return $width * $height;
	}

	#[ToInt]
	public function toInt(): int {
		return round($this->toFloat());
	}

	#[ToArray]
	public function toArray(): array {
		return [
			$this->x1, $this->y1,
			$this->x2, $this->y2,
		];
	}

	#[ToStr]
	public function toStr(): string {
		$class = $this::class;

		return "<{$class} x1='{$this->x1}' y1='{$this->y1}'"
			." x2='{$this->x2}' y2='{$this->y2}>";
	}

	#[ToObj(FakeObject::class)]
	public function toFakeObj($class) {
		return new FakeObject();
	}

	#[ToObj(Figure::class)]
	public function toFigureObj($class) {
		$data = [
			[$this->x1, $this->y1],
			[$this->x2, $this->y2],
		];
		return new Figure($data);
	}

}

