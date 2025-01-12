# DeepL Laravel package

To integrate the deepl-laravel package into your Laravel project, follow these steps:

## 1.	Install the Package:
Add the package to your project using Composer:

```
composer require jorisvanw/deepl-laravel
```

## 2.	Publish the Configuration:
Publish the package’s configuration file to customize its settings:

```
php artisan vendor:publish --provider="JorisvanW\DeepL\DeepLServiceProvider"
```

This command will create a deepl.php configuration file in your config directory.

## 3.	Configure the API Key

Obtain your DeepL API key from the DeepL Pro Account and set it in your `.env` file:

```
DEEPL_API_KEY=your_deepl_api_key
```

Ensure that the `DEEPL_API_KEY` environment variable is referenced in the `config/deepl.php` configuration file.

## 4.	Usage:
After configuration, you can use the DeepL translation service in your application. Here’s an example of translating text within a controller:

```php 
use JorisvanW\DeepL\DeepL;

class TranslationController extends Controller
{
    public function translate()
    {
        $deepl = new DeepL();
        $translatedText = $deepl->translate('Hello, world!', 'EN', 'DE');
        return $translatedText; // Outputs: 'Hallo, Welt!'
    }
}
```

In this example, the translate method of the DeepL class is used to translate the text ‘Hello, world!’ from English (‘EN’) to German (‘DE’).
