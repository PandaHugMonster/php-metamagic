# MetaMagic Extension for casting


A very primitive example of how
the MetaMagic pico-framework could be used to
implement a casting mechanics.

1. [example.php](example.php) - entrypoint
2. [components](components) - components
    * [attrs.php](components/attrs.php) - All the necessary attributes grouped in a single file
    * [CastTo.php](components/CastTo.php) - Syntactic sugar Enum
    * [utils.php](components/utils.php) - Utils with a single `cast()` function
    * [ToStrTrait.php](components/ToStrTrait.php) - Small trait to apply `cast()` on `__toString()`
    * [FakeObject.php](components/FakeObject.php) - Fake Object class that is not used, 
      but demonstrates filtering based on the `ToObj()` first argument
    * [Figure.php](components/Figure.php) - The second main target class to which 
      the first target class must be cast into
    * [Rect.php](components/Rect.php) - The first main target class 
      on object of which all the functionality is demonstrated

Entrypoint logic
```php

$rect = new Rect(
	10.5, 10.5,
	50.3, 55.9,
);


$casted = "{$rect}";
$type = gettype($casted);
print_r("Type: {$type}; __toString(): {$casted}\n");
echo "-------------\n";


$casted = cast($rect, CastTo::Str);
$type = gettype($casted);
print_r("Type: {$type}; Casted: {$casted}\n");
echo "-------------\n";


$casted = cast($rect, CastTo::Float);
$type = gettype($casted);
print_r("Type: {$type}; Casted: {$casted}\n");
echo "-------------\n";


$casted = cast($rect, CastTo::Array);
$type = gettype($casted);
print_r("Type: {$type}; Casted: ".json_encode($casted)."\n");
echo "-------------\n";


$casted = cast($rect, CastTo::Int);
$type = gettype($casted);
print_r("Type: {$type}; Casted: {$casted}\n");
echo "-------------\n";


$casted = cast($rect, CastTo::Int);
$type = gettype($casted);
print_r("Type: {$type}; Casted: {$casted}\n");
echo "-------------\n";


// Important: this code must not display:
//  "Type: object; Casted: I'm a fake object"
$casted = cast($rect, Figure::class);
$type = gettype($casted);
print_r("Type: {$type}; Casted: {$casted}\n");

```

Output:
```text
Type: string; __toString(): <Rect x1='10.5' y1='10.5' x2='50.3' y2='55.9>
-------------
Type: string; Casted: <Rect x1='10.5' y1='10.5' x2='50.3' y2='55.9>
-------------
Type: double; Casted: 1806.92
-------------
Type: array; Casted: [10.5,10.5,50.3,55.9]
-------------
Type: integer; Casted: 1807
-------------
Type: integer; Casted: 1807
-------------
Type: object; Casted: <Figure path='[(10.5, 10.5), (50.3, 55.9), ]'>
```