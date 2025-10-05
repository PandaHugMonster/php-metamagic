<?php

require_once '../../vendor/autoload.php';

require_once 'components/attrs.php';
require_once 'components/CastTo.php';
require_once 'components/utils.php';
require_once 'components/ToStrTrait.php';
require_once 'components/Figure.php';
require_once 'components/Rect.php';
require_once 'components/FakeObject.php';


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
