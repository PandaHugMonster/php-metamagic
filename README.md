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


Minimal PHP version: **8.4**

Current framework version: **0.0.1**

