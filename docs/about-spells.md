# About Spells

Spells are just specific by member type (class, class const, field/property, method) objects
that aggregate relevant information about a single case of a found member + attribute (if applicable).

Members are represented through `\spaf\metamagic\components\TargetReference` class.

Keep in mind that single Spell is involving a single member and single attribute (if applicable).
It means that if you specify 2 separate attributes to your member and then search both of them,
The result will contain 2 occurrences of Spell for the same member 
but with different attributes assigned to them.

If you'd need to find an intersection of different attributes, 
much better way would be searching a first attribute and then through `$filter`
checking other available attributes on the member 
(`\spaf\metamagic\components\TargetReference` contains reflection object of the member for convenience).