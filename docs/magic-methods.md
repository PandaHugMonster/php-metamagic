# Magic Methods Attributes

## Not supported

Constructor and Destructor were purposefully skipped, they are the most essential,
and it's a bit sketchy to turn them into magic-method attrs.

* `__construct()`
* `__destruct()`
* Serialization magic methods are skipped as well due to very sad design of them
  * `__serialize()`
  * `__unserialize()`
  * `__sleep()`
  * `__wake()`
  * `__setState()`

## Supported

All the implemented magic-method attrs can be used `as-is` (even disregarded/deprecated)
and fully backward compatible with the standard PHP magic-methods (despite some renaming).

But it's strongly recommended to use Meta-Magic improved/advanced magic-methods attrs 
when applicable, and avoid using the disregarded/deprecated ones at all times!


### Advanced magic-methods
* `#[CopyShallow]` from `\spaf\metamagic\attributes\magic\CopyShallow`
  \- [PHP magic method __clone()](https://www.php.net/manual/en/language.oop5.cloning.php#object.clone)
* `CopyDeep` is not implemented, it might be implemented in the future


### Default magic-methods

* `#[Call]` from `\spaf\metamagic\attributes\magic\Call` on **dynamic** method
  \- [PHP magic method __call()](https://www.php.net/manual/en/language.oop5.overloading.php#object.call)
* `#[Call]` from `\spaf\metamagic\attributes\magic\Call` on **static** method
  \- [PHP magic method __callStatic()](https://www.php.net/manual/en/language.oop5.overloading.php#object.callstatic)
* `#[Get]` from `\spaf\metamagic\attributes\magic\Get`
  (If you use Properties better use its functionality)
  \- [PHP magic method __get()](https://www.php.net/manual/en/language.oop5.overloading.php#object.get)
* `#[Set]` from `\spaf\metamagic\attributes\magic\Set`
  (If you use Properties better use its functionality)
  \- [PHP magic method __set()](https://www.php.net/manual/en/language.oop5.overloading.php#object.set)
* `#[IsAssigned]` from `\spaf\metamagic\attributes\magic\IsAssigned`
  (If you use Properties better use its functionality)
  \- [PHP magic method __isset()](https://www.php.net/manual/en/language.oop5.overloading.php#object.isset)
* `#[Del]` from `\spaf\metamagic\attributes\magic\Del`
  \- [PHP magic method __unset()](https://www.php.net/manual/en/language.oop5.overloading.php#object.unset)
* `#[ToString]` from `\spaf\metamagic\attributes\magic\ToString`
  (If you use Caster or SimpUtils better use its functionality)
  \- [PHP magic method __toString()](https://www.php.net/manual/en/language.oop5.magic.php#object.tostring)
* `#[Invoke]` from `\spaf\metamagic\attributes\magic\Invoke`
  \- [PHP magic method __invoke()](https://www.php.net/manual/en/language.oop5.magic.php#object.invoke)
* `#[DebugInfo]` from `\spaf\metamagic\attributes\magic\DebugInfo`
  \- [PHP magic method __debugInfo()](https://www.php.net/manual/en/language.oop5.magic.php#object.debuginfo)


## Notes from the Author

### Serialization and Deserialization

> [!IMPORTANT]
> All serialization functionality is omitted from turning into MetaMagic 
> due to bad design of this functionality on the side of PHP engine.

PHP magic-methods of `__serialize()`, `__unserialize()`, `__sleep()`, `__wakeup()`
as well as `Serializable` interface and `JsonSerializable` (JSON module) interface
are very limited in many ways and very unfortunate ways.

Some of them:
* Mix of different approaches to achieve the same very limited thing (interface
  and magic-methods that affect negatively and confuse users)
* Serialized structure
  * Only one format in which partially the code structure could be exposed
  * The format is not easily used in some other languages
  * The format is not abstracted enough from the language perspective
  * Lack of flexibility (Impossible to inject alias for the class instead of 
    the original class name)


Almost all the magic methods of PHP are implemented in form of PHP Attributes,
and those could be used to improve your code quality and even provide additional features
to the code.

Exceptions are `__construct()` constructor and `__destruct()` destructor.
Implementing those from the perspective of IDE compatibility and performance standpoint
was considered unreasonable (this might change, but no plans for foreseeable future).

### Supported Magic Attributes

**Important note**: The initial intention was to name the Magic Attributes
as close as possible to their PHP magic methods, but due to
naming conflict with the PHP reserved names and wrong spelling, it was not always possible.

* `#[Call]` from `\spaf\metamagic\attributes\magic\Call` on **dynamic** method
  \- [PHP magic method __call()](https://www.php.net/manual/en/language.oop5.overloading.php#object.call)
* `#[Call]` from `\spaf\metamagic\attributes\magic\Call` on **static** method
  \- [PHP magic method __callStatic()](https://www.php.net/manual/en/language.oop5.overloading.php#object.callstatic)
* `#[Get]` from `\spaf\metamagic\attributes\magic\Get`
  (If you use Properties better use its functionality)
  \- [PHP magic method __get()](https://www.php.net/manual/en/language.oop5.overloading.php#object.get)
* `#[Set]` from `\spaf\metamagic\attributes\magic\Set`
  (If you use Properties better use its functionality)
  \- [PHP magic method __set()](https://www.php.net/manual/en/language.oop5.overloading.php#object.set)
* `#[IsAssigned]` from `\spaf\metamagic\attributes\magic\IsSpecified`
  (If you use Properties better use its functionality)
  \- [PHP magic method __isset()](https://www.php.net/manual/en/language.oop5.overloading.php#object.isset)
* `#[Del]` from `\spaf\metamagic\attributes\magic\Del`
  \- [PHP magic method __unset()](https://www.php.net/manual/en/language.oop5.overloading.php#object.unset)
* `#[ToString]` from `\spaf\metamagic\attributes\magic\ToString`
  (If you use Caster or SimpUtils better use its functionality)
  \- [PHP magic method __toString()](https://www.php.net/manual/en/language.oop5.magic.php#object.tostring)
* `#[Invoke]` from `\spaf\metamagic\attributes\magic\Invoke`
  \- [PHP magic method __invoke()](https://www.php.net/manual/en/language.oop5.magic.php#object.invoke)
* `#[CopyShallow]` from `\spaf\metamagic\attributes\magic\CopyShallow`
  \- [PHP magic method __clone()](https://www.php.net/manual/en/language.oop5.cloning.php#object.clone)
* `#[DebugInfo]` from `\spaf\metamagic\attributes\magic\DebugInfo`
  \- [PHP magic method __debugInfo()](https://www.php.net/manual/en/language.oop5.magic.php#object.debuginfo)
