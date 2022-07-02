# Laravel DeepL API Translation Package
[![GitHub issues](https://img.shields.io/github/issues/waithawoo/laravel-deeplapi-translate)](https://github.com/waithawoo/laravel-deeplapi-translate/issues)
[![GitHub stars](https://img.shields.io/github/stars/waithawoo/laravel-deeplapi-translate)](https://github.com/waithawoo/laravel-deeplapi-translate/stargazers)
[![GitHub forks](https://img.shields.io/github/forks/waithawoo/laravel-deeplapi-translate)](https://github.com/waithawoo/laravel-deeplapi-translate/network)


## To translate languages with Deep L Translator API 

Easy to use Deep L API for translation in Laravel projects
 - **[Installation](#installation)**
 - **[Usage](#usage)**

## Installation

Install this package via [Composer](https://getcomposer.org/).

```
composer require waithaw/deeplapitranslate
```
You can publish the config file using the following command:
```
php artisan vendor:publish --provider="WaiThaw\DeeplTranslate\DeeplTranslateServiceProvider"
```
This will create the package's config file called deepltranslate.php in the config directory. The contents of the published config file are as follows.
```php
return [
    /*
      |-------------------------------------------------------------------------------
      | Api Key generated from Deep L account.
      |-------------------------------------------------------------------------------
      */
    'auth_key' => env('DEEPL_TRANSLATE_API_KEY'),

    /*
      |-------------------------------------------------------------------------------
      | Api Endpoint URL from Deep L account.
      |-------------------------------------------------------------------------------
      */
    'api_url' => env('DEEPL_TRANSLATE_API_ENDPOINT_URL'),
];
```

## Usage
- After setting up the config file values in .env, you can use translation methods.

### Create DeeplTranslate object

```php
use WaiThaw\DeeplTranslate\DeeplTranslate;

$deepl = new DeeplTranslate();
```

### Translate
```php
// parameters are $text, $source_language, $target_language
$translated_text = $deepl->translate('are you hungry?', 'EN', 'JA'); // Output translated text string

echo $translated_text;
```

### Supported languages
```php
$languages = $deepl->languages();
foreach($languages as $lang){
    echo $lang['language']."-".$lang['name'].'<br>';
}
```
For supported Source-languages
```php
$source_languages = $deepl->languages('source');
foreach($source_languages as $lang){
    echo $lang['language']."-".$lang['name'].'<br>';
}
```

For supported Target-languages
```php
$target_languages = $deepl->languages('target');
foreach($target_languages as $lang){
    echo $lang['language']."-".$lang['name'].'<br>';
}
```

### Monitoring Usage
You can monitor the number of traslated characters and maximun limitations
```php
$usage= $deepl->usage();
echo $usage['character_count'].' characters have been used. Maximum number of characters that can be translated in the current billing period are '.$usage['character_limit'];
```

### Setting for cURL timeout
You can setup a timeout to prevent waiting in case of api unreachable
```php
$deepl->setTimeout(10);
```

## Security

If you discover any security related issues, please email them to [waithawoocw@gmail.com](mailto:waithawoocw@gmail.com) instead of using the issue tracker.

## License

The MIT License (MIT). Please see the [License File](LICENSE) for more information.
