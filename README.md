# M-PESA PHP SDK 🚀

An amazing PHP SDK for seamless integration with the M-PESA API, built to simplify payment processing for developers. 🌟

# Features ✨

- 🔑 **Easy Authentication**: Generate access tokens effortlessly.
- 📲 **STK Push (NI Push)**: Initiate customer-to-business payments with USSD prompts.
- 🏪 **C2B Support**: Register URLs and handle customer payments.
- 💸 **B2C Payouts**: Process business-to-customer disbursements.
- 🧩 **Modular Design**: Well-structured and extensible codebase.
- 🚨 **Error Handling**: Robust, developer-friendly error management.

# Installation 📦

Install the SDK via Composer from your terminal:

```bash
composer require kahsay-gt/mpesa-sdk-php:dev-main

```

# M-PESA SDK Integration 🚀

Easily integrate M-PESA APIs into your PHP or Laravel applications.

## Requirements ✅

- 🐘 PHP 7.4 or higher
- 🌐 cURL extension enabled
- 📥 Composer

## Using a Custom Repository 🌍

Since this package is hosted on GitHub, add the repository to your `composer.json`:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/kahsay-GT/mpesa-sdk-php.git"
    }
],
"require": {
    "kahsay-gt/mpesa-sdk-php": "dev-main"
},
"minimum-stability": "dev"
```

### Run the following command:

```sh
composer update
```

### Publish Config:

```sh
php artisan vendor:publish --tag=mpesa-config
```

## Usage 🛠️

### 1. Authentication 🔐

Generate an access token to interact with M-PESA APIs:

```php
use Mpesa\Sdk\Config;
use Mpesa\Sdk\Auth;

$config = new Config([
    'environment' => 'sandbox',
    'consumer_key' => 'your_consumer_key',
    'consumer_secret' => 'your_consumer_secret',
    'endpoint' => 'https://apisandbox.safaricom.et/v1/token/generate'
]);

$auth = new Auth($config);
$token = $auth->generateToken();
echo $token->access_token; // Outputs your access token
```

### 2. STK Push (NI Push) 📱

Initiate a payment request:

```php
use Mpesa\Sdk\StkPush;

$stk = new StkPush($config, $token->access_token);
$response = $stk->processRequest([
    'BusinessShortCode' => '554433',
    'Amount' => '10.00',
    'PhoneNumber' => '251700404789',
    'CallBackURL' => 'https://yourdomain.com/callback',
    'TransactionDesc' => 'Test Payment'
]);

echo $response->ResponseDescription; // "Success. Request accepted for processing"
```

### 3. Laravel Integration ⚙️

Add this to a controller:

```php
namespace App\Http\Controllers;

use Mpesa\Sdk\Config;
use Mpesa\Sdk\Auth;

class MpesaController extends Controller {
    public function testSdk() {
        $config = new Config([...]); // Same as above
        $auth = new Auth($config);
        $token = $auth->generateToken();
        return response()->json(['token' => $token->access_token]);
    }
}
```

## Configuration ⚙️

The `Config` class accepts an array with these keys:

- 🌐 **environment**: `'sandbox'` or `'production'`
- 🔑 **consumer_key**: Your M-PESA consumer key
- 🔒 **consumer_secret**: Your M-PESA consumer secret
- 🌍 **endpoint**: API base URL (e.g., `'https://apisandbox.safaricom.et/v1/token/generate'`)

## Contributing 🤝

### 🍴 Fork the repository.

- 🌿 Create a feature branch:
  ```sh
  git checkout -b feature/awesome-feature
  ```
- 💾 Commit your changes:
  ```sh
  git commit -m 'Add awesome feature'
  ```
- 🚀 Push to the branch:
  ```sh
  git push origin feature/awesome-feature
  ```
- 📬 Open a Pull Request.

## License 📜

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support 📧

- For issues or questions, open an issue on GitHub or contact **kahsay21a@gmail.com**
- Reach out on Telegram: [@KahsayG21](https://t.me/KahsayG21) 📩
