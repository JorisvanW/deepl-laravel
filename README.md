# DeepL Laravel package

To integrate the deepl-laravel package into your Laravel project, follow these steps:

## 1.	Install the Package:
Add the package to your project using Composer:

```
composer require jorisvanw/deepl-laravel
```

## 2.	Configure the API Key

Obtain your DeepL API key from the DeepL Pro Account and set it in your `.env` file:

```
DEEPL_KEY=your_deepl_api_key
```

## 3.	Usage:
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
