:ukraine: #StandWithUkraine

This is the official Bank of Ukraine link for donations for Ukraine:

https://bank.gov.ua/en/news/all/natsionalniy-bank-vidkriv-spetsrahunok-dlya-zboru-koshtiv-na-potrebi-armiyi

-----

-----

# PHP MetaMagic

This project is a pico-framework (tini-tiny library) for few basic purposes:
1. Make software more flexible and configurable on a development level.
   * Code examples:
     * [Object default params from file](examples/object-default-params-from-file/README.md)
2. Working with "Attributed" **classes**, **properties**, **methods**, **class-const**.
   * **searching and filtering** the attributed members to perform actions on them.
   * Code examples:
     * [Primitive HTTP routing](examples/primitive-http-routing/README.md)
3. Provide a new way to extend functionality that usually use "Magic Methods".
   * Code examples:
     * **auto-casting** (SimpUtils Caster, future project)
       * [MetaMagic Extension for casting](examples/metamagic-extension-for-casting/README.md)
     * dynamic **getter/setter** functionality (SimpUtils Props, future project)
       * [Primitive getter/setter handler](examples/primitive-getter-setter-handler/README.md)
4. More comfortable development process through dev-markings and architectural analysis 
   by writing utilities that analyse "Attributes" written by Architects and Lead Developers (future project)
   * Code examples:
     * [Marker Analysis](examples/marker-analysis/README.md)

MetaMagic is a fully redesigned subproject of [PHP SimpUtils](https://github.com/PandaHugMonster/php-simputils).


> [!NOTICE]
> There are 2 logical types of PHP Attributes:
> 1. "Marker Attribute" - The Attributes that are used just for marking parts of code
>    for analysis and notifications.
>    Those Attributes just mark portion of code and might contain only data/params in them,
>    but do not perform any functionality/logic directly. The most of the native PHP 8+ Attributes
>    play a role of a Marker. Some use cases:
>    * `#[\Deprecated]`
>    * `#[\ReturnTypeWillChange]`
> 2. "Functional Attribute" - The Attributes that contain logic and functionality directly in
>    the Attribute classes. So those are basically real classes that could be used as normal classes
>    beside being Attributes. Some use cases:
>    * `#[\spaf\metamagic\attributes\magic\ToString]`
>    * `#[\spaf\metamagic\attributes\magic\DebugInfo]`
>
> Why the difference?! - Because from the architectural standpoint "Functional Attribute"
> increases coupling due to certain logic in them, while the "Marker Attribute" are dependency-free
> and does not have a direct coupling, and play role within the context/tools/framework that uses them.


## Generic usage

There is basically just a couple of major methods for searching code members covered by attributes.

Simple example of searching members:
```php

use spaf\metamagic\abstract\AbstractSpell;
use spaf\metamagic\enums\TargetType;
use spaf\metamagic\MetaMagic;



#[Attribute(Attribute::TARGET_CLASS)]
class MyClassAttr {}

#[Attribute(Attribute::TARGET_ALL)]
class MyCommonAttr {}

#[MyClassAttr]
class MyClass {

	#[MyCommonAttr]
	public $my_field = null;

	#[MyCommonAttr]
	protected function myMethod() {

	}

}

$my_obj = new MyClass();

$filter = function ($spell): AbstractSpell | null {
	// Returning spell will include it into result, returning `null` will skip this spell
	if ($spell->attr instanceof MyClassAttr) {
		return null;
	}
	return $spell;
};

// Generator of `AbstractSpell` objects returned
$generator = MetaMagic::findSpells(
    // Refs param can contain array with objects, class-refs.
    // You also can directly specify a single ref without wrapping it into array
	refs: $my_obj,
	// The same about attrs it can be an array of class-refs or a single class-ref to an Attribute
	attrs: [MyClassAttr::class, MyCommonAttr::class],
	// The same about types it can be an array of enum values or a single enum value
	types: [TargetType::ClassType, TargetType::FieldType],
	// It allows to control whether you want to
	// search attributes with inheritance involved or exact attribute class.
	// is_instance_of is set to true by default - what means that it will consider inheritance
	// (children classes of an attribute as well)
	is_instance_of: true,
	// You can perform a custom filtering by providing a callable
	filter: $filter
);

$first_spell = MetaMagic::findSpellOne(
	// Refs param can contain array with objects, class-refs.
	// You also can directly specify a single ref without wrapping it into array
	refs: $my_obj,
	// The same about attrs it can be an array of class-refs or a single class-ref to an Attribute
	attrs: [MyClassAttr::class, MyCommonAttr::class],
	// The same about types it can be an array of enum values or a single enum value
	types: [TargetType::ClassType, TargetType::FieldType],
	// It allows to control whether you want to
	// search attributes with inheritance involved or exact attribute class.
	// is_instance_of is set to true by default - what means that it will consider inheritance
	// (children classes of an attribute as well)
	is_instance_of: true,
	// You can perform a custom filtering by providing a callable
	filter: $filter
);

print_r($first_spell);

```

Output:
```text
spaf\metamagic\spells\SpellField Object
(
    [target] => spaf\metamagic\components\TargetReference Object
        (
            [reflection] => ReflectionProperty Object
                (
                    [name] => my_field
                    [class] => MyClass
                )

            [object] => MyClass Object
                (
                    [my_field] => 
                )

        )

    [attr] => MyCommonAttr Object
        (
        )

)
```

> [!IMPORTANT]
> Filtering by params and especially by `filter: $filter` param allows to reduce some overhead
> for not processing every single spell when those are not needed.
> 
> This is especially important when you are searching only first occurrence with `MetaMagic::findSpellOne()`


## Magic Methods syntactic sugar
The magic methods are ugly and inherently inconsistent in PHP.

So special MetaMagic attributes implemented for a few reasons:
1. To demonstrate the power of the MetaMagic pico-framework (purpose of example)
2. To prettify the class code (it's value is very small, 
   but can improve readability what can cause better maintenance)

> [!NOTICE]
> There is no much reason to use these attributes besides slightly improving readability and code analysis.


[//]: # (TODO   Re-implement Magic-Methods syntactic sugar attributes)

----

## License is "MIT"

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.


----

## Installation

> [!IMPORTANT]
> This code is a very minimal prototype. It means that all the planned optimizations
> are not yet applied. So further improvements of performance should be coming 
> in the future versions

Minimal PHP version: **8.4**

Current framework version: **0.0.1**

