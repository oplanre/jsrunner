# Server side rendering JavaScript in your PHP application

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lanre/jsrunner.svg?style=flat-square)](https://packagist.org/packages/lanre/jsrunner)
[![Build Status](https://img.shields.io/travis/lanre/jsrunner/master.svg?style=flat-square)](https://travis-ci.org/lanre/jsrunner)
[![Total Downloads](https://img.shields.io/packagist/dt/lanre/jsrunner.svg?style=flat-square)](https://packagist.org/packages/lanre/jsrunner)

```php
use Lame\JSRunner\Renderer;
use Lame\JSRunner\V8Engine as V8;

$engine = new V8();

$renderer = new Renderer($engine);

echo $renderer
    ->entry(__DIR__.'/../../public/js/app-server.js')
    ->render();

// <div>My server rendered app!</div>
```

- Works with any JavaScript framework that allows for server side rendering
- Runs with or without the V8Js PHP extension
- Requires minimal configuration
This readme assumes you already have some know-how about building server rendered JavaScript apps.

## Who's this package for?
- Projects that want to call JavaScript from PHP seamlessly.
- Bilingual(PHP and JS/TS) projects that want to render their JS/TS on the server side.
## Installation

You can install the package via composer:

```bash
composer require lanre/jsrunner
```

## Usage

### Engines

An engine executes a JS script on the server. This library ships with three engines: `V8Engine` which wraps some `V8Js` calls, so you'll need to install a PHP extension for this one, 
 `BunEngine` and `NodeEngine` which build a bun/node script (respectively) at runtime and executes it in a new process. An engine can run a script, or an array of multiple scripts.

`V8Engine` is a lightweight wrapper around the `V8Js` class. You'll need to install the [v8js extension](https://github.com/phpv8/v8js) to use this engine.

`BunEngine` and `NodeEngine`   write a temporary file with the necessary scripts to render your app, and executes it 
<!-- in a node.js process. You'll need to have [node.js](https://nodejs.org) installed to use this engine. -->
in the appropriate environment. You'll need to have [node.js](https://nodejs.org) (for `NodeEngine`) or [bun](https//bun.sh) (for `BunEngine`) installed to use this engine.


### Rendering options

You can chain any amount of options before rendering the app to control how everything's going to be displayed.

```php
echo $renderer
    ->enableIf($user->isAuthenticated())
    ->context('user', $user)
    ->entry(__DIR__.'/../../public/js/app-server.js')
    ->render();
```

#### `enable(): $this`

Enables server side rendering. This is the default state.

#### `disable(): $this`

Disables server side rendering. When disabled, the client script and the fallback html will be rendered instead.

#### `enableIf(bool $enabled = true): $this`

Conditionally enables server side rendering.

#### `debug(bool $debug = true): $this`

When debug is enabled, JavaScript errors will cause a php exception to throw. Without debug mode, the client script and the fallback html will be rendered instead so the app can be rendered from a clean slate.

#### `entry(string $entry): $this`

The path to your server script. The contents of this script will be run in the engine.

#### `context($context, $value = null): $this`

Context is passed to the server script in the `context` variable. This is useful for hydrating your application's state. Context can contain anything that json-serializable.

```php
echo $renderer
    ->entry(__DIR__.'/../../public/js/app-server.js')
    ->context('user', ['name' => 'Sebastian'])
    ->render();
```

```js
// app-server.js

store.user = context.user // { name: 'Sebastian' }

// Render the app...
```

Context can be passed as key & value parameters, or as an array.

```php
$renderer->context('user', ['name' => 'Sebastian']);
```

```php
$renderer->context(['user' => ['name' => 'Sebastian']]);
```

#### `env($env, $value = null): $this`

Env variables are placed in `process.env` when the server script is executed. Env variables must be primitive values like numbers, strings or booleans.

```php
$renderer->env('NODE_ENV', 'production');
```

```php
$renderer->env(['NODE_ENV' => 'production']);
```

#### `fallback(string $fallback): $this`

Sets the fallback html for when server side rendering fails or is disabled. You can use this to render a container for the client script to render the fresh app in.

```php
$renderer->fallback('<div id="app"></div>');
```

#### `resolveEntryWith(callable $resolver): $this`

Add a callback to transform the entry when it gets resolved. It's useful to do this when creating the renderer so you don't have to deal with complex paths in your views.

```php
echo $renderer
    ->resolveEntryWith(function (string $entry): string {
        return __DIR__."/../../public/js/{$entry}-server.js";
    })
    ->entry('app')
    ->render();
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/oplanre/jsrunner/blob/main/CONTRIBUTING.md) for details.

## Credits
- [Lanre Waju](hjttps://github.com/oplanre) for creating this package
- [Sebastian De Deyne](https://github.com/sebastiandedeyne) for spatie/server-side-rendering which this package is based on
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
