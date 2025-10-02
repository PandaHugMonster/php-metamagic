# General information

The project of "SimpUtils" framework grew over the expected borders, and
the need for structural refactoring has appeared.

One of the major theoretical issue of the framework was loosely defined concept of the "MetaMagic".

To address the above-mentioned issues, these subprojects came to life.

Besides the "MetaMagic" subproject a few other features were planned to be extracted into
their respective repos. The following subprojects about to be extracted:
1. `metamgic` (simputils)
2. `caster` (simputils)
3. `props` (simputils)

In addition to the above-mentioned, such modularity, allows to integrate
these pieces of functionality without pulling the whole `simputils` project (which now is 
bigger than anticipated)

> [!IMPORTANT]
> None of those subprojects are finished and integrated into the newer versions of the SimpUtils framework.
> There are a lot of work, and as soon as they are release-ready and integrated - it will be
> reflected in the `CHANGELOG` of the `simputils` project.


> [!INFO]
> `metamagic` naming is purposefully chosen in reference to "Magic Methods" of PHP and
> as a slightly higher layer of abstraction. To hide away complexities of the raw PHP engine.
>
> Besides, some of the simputils projects having the naming related to "Magic" or "Meta Magic".
> This allows to create a small story and relations around those projects, which in turn can
> improve and simplify an intuitive approach to usage of the functionality.
>
> In addition, this special story extends the vocabulary to use in class/function names,
> what can reduce chances of name-collisions.


## About `metamagic` project

The major purpose of this project is to simplify work for developers with PHP Attributes.
Such as: searching, filtering, finding marked place, etc.

Technically it's just a simple mechanics to perform searching for marked references by PHP Attributes.

Which enables lots of positive opportunities, while poses some serious architectural pitfalls.

The simple rule here is to be cautious, and if you are not sure whether you should use the `metamagic` or
standard PHP features - always choose latter.



## About `caster` project

Caster project is dedicated for functionality that should simplify casting different data-types.

For example: in case of compatible data-type during the assigning of the `property`


## About `props` project

This is a key-stone of the `simputils` project family. It's major purpose to simplify creation of dynamic
properties (getters/setters) through PHP Attributes, 
instead of the custom implementations through `__get()` nad `__set()` magic methods. 

