# PHP DataLayer

A helper package that help to generate Google's DataLayer script on pages.

## Installation

Require this package with composer.
```shell
composer require anthonypauwels/datalayer
```

### Laravel without auto-discovery:

If you do not use auto-discovery, add the ServiceProvider to the providers array in `config/app.php` :
```php
Anthonypauwels\DataLayer\Laravel\ServiceProvider::class,
```

Then add this line to your facades in `config/app.php` :
```php
'DataLayer' => Anthonypauwels\DataLayer\Laravel\DataLayer::class,
```

Finally, add this `GOOGLE_ID=YOUR_GOOGLE_ID` at the end of your .env file.

## Usage

### Without Laravel

You must create a SessionHandler object. Handler uses session to pass data through pages. Then you can pass the SessionHandler to the DataLayerHandler using the constructor :

```php
use Anthonypauwels\DataLayer\DataLayerHandler;
use Anthonypauwels\DataLayer\SessionHandler;

$datalayer = new DataLayerHandler( 
                new SessionHandler(), 
                'YOUR_GOOGLE_ID'
            );
```

### With Laravel

The package provides by default a Facade for Laravel application. You can call methods directly using the Facade or use the alias instead.
```php
use Anthonypauwels\DataLayer\Laravel\DataLayer;

DataLayer::push('foo', 'bar');
```

## API documentation

Examples bellow are using Laravel Facade `DataLayer`.

### In your controllers

#### Push one value in the DataLayer

```php
DataLayer::push('foo', 'bar');
```

#### Push an array of data in the DataLayer

```php
DataLayer::pushArray([
    'user_name' => 'John Doe',
    'age' => '42',
    'country' => 'Belgium',
]);
```

Do not hesitate to check the prototype of the method to view all possibles options.

### In your views

#### Publish the DataLayer in the view

Just call this method in your app layout before the closing <HEAD> tag.

```php
DataLayer::publish();
```

It will print this entire HTML code in your layout :

```html
<script>
    dataLayer = dataLayer || [];
</script>

<script>
    dataLayer.push({foo:'bar',user_name:'John Doe',age:42,country:'Belgium'});
</script>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','YOUR_GOOGLE_ID');</script>
<!-- End Google Tag Manager -->
```

Do not forget to call `DataLayer::noScript()` right after your <BODY> tag :

```php
DataLayer::noScript();
```

It will print the following :

```html
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=YOUR_GOOGLE_ID"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
```

You can use an optional array to choose if you do not want to initialise the global JS object, initialise the Google Tag Manager script or to clear data after publish.

```php
DataLayer::publish(['init' => false, 'script' => false, 'clear' => false]);
```

It will just print this :

```html
<script>
    dataLayer.push({foo:'bar',user_name:'John Doe',age:42,country:'Belgium'});
</script>
```

#### Blade directives

If you use DataLayerHandler with Laravel, you can use custom Blade directives to insert codes into the view without using the facade directly :

```blade
@datalayerInit()

instead of

{{ DataLayer::init() }}
```

```blade
@datalayerScript()

instead of

{{ DataLayer::script() }}
```

```blade
@datalayerNoScript()

instead of

{{ DataLayer::noScript() }}
```

```blade
@datalayerPublish()

instead of

{{ DataLayer::publish() }}
```

```blade
@datalayerPush([
    'user_name' => 'John Doe',
])

instead of

{{ DataLayer::pushData([
    'user_name' => 'John Doe',
]) }}
```

The DataLayer is cleared after each call to the DataLayer::publish() method except if the option `clear` is set to `false`.

#### Push an array of data in the DataLayer

```php
DataLayer::pushData([
    'user_name' => 'John Doe',
    'age' => '42',
    'country' => 'Belgium',
]);
```

### Others methods

#### Load the data from session

```php
DataLayer::load();
```

#### Save the data in the session

```php
DataLayer::save();
```

#### Clear the data in the session

```php
DataLayer::clear();
```

#### Get the array data

```php
DataLayer::getData();
```

#### Print the global JS object in the view

```php
DataLayer::init();
```

It will print this in the HTML :

```html
<script>
    window.dataLayer = window.dataLayer || [];
</script>
```

#### Print the Google Tag Manager script in the view

The `$google_id` parameter is optional. If omitted, it will use the Google ID set in your .env file.

```php
DataLayer::script([$google_id = null]);
```

```html
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','YOUR_GOOGLE_ID');</script>
<!-- End Google Tag Manager -->
```

Also, do not forget to add the <noscript> tag with.

```php
DataLayer::noScript([$google_id = null]);
```

```html
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=YOUR_GOOGLE_ID"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
```

#### Show the content of the DataLayer (debug purpose)

```php
DataLayer::dd();
```

### Requirement

PHP 8.0 or above

## See also

- [Dev Guide](https://developers.google.com/tag-manager/devguide)
- [Quick Start](https://developers.google.com/tag-manager/quickstart)

