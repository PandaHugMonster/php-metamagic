# Objects' default params from file

A very primitive example of how 
the MetaMagic pico-framework could be used to 
preload default values to a newly created object from a file.

1. [example.php](example.php) - entrypoint
2. [my-bio-defaults.json](my-bio-defaults.json) - The default values in JSON file
3. [components](components) - components
   * [DefaultsAttr.php](components/DefaultsAttr.php) - The attribute with configuration
   * [DefaultsTrait.php](components/DefaultsTrait.php) - The meta-magic attribute searching functionality
   * [MyBio.php](components/MyBio.php) - The target class
