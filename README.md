# M-PESA PHP SDK Integration 🚀

Easily integrate M-PESA APIs into your PHP or Laravel applications using the `kahsaygt/mpesa-sdk-php` package.🌟

# Features ✨

- 🔑 **Easy Authentication**: Generate access tokens effortlessly.
- 📲 **STK Push (NI Push)**: Initiate customer-to-business payments with USSD prompts.
- 🏪 **C2B Support**: Register URLs and handle customer payments.
- 💸 **B2C Payouts**: Process business-to-customer disbursements.
- 🧩 **Modular Design**: Well-structured and extensible codebase.
- 🚨 **Error Handling**: Robust, developer-friendly error management.

---

## Installation 📦

Install the SDK via Composer:

```bash
composer require kahsaygt/mpesa-sdk-php:dev-main
```

### Using a Custom Repository 🌍

Since this package is hosted on GitHub, add the repository to your `composer.json`:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/kahsay-GT/mpesa-sdk-php.git"
    }
],
"require": {
    "kahsaygt/mpesa-sdk-php": "dev-main"
},


"minimum-stability": "dev"
```

Then run:

```bash
composer update
```

### Laravel (Optional)

If using Laravel, publish the configuration file (if supported by your SDK):

```bash
php artisan vendor:publish --tag=mpesa-config
```

---

## Requirements ✅

- 🐘 PHP 7.4 or higher
- 🌐 cURL extension enabled
- 📥 Composer

---

## Usage 🛠️

This SDK provides a `Client` class for API interactions and service classes for each M-PESA API endpoint. Below are usage examples for authentication and various API operations.

### 1. Authentication 🔐

All API requests require an access token. Use `Authentication.php` to authenticate a `Client` instance:

```php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/Authentication.php'; // Adjust path as needed

use Mpesa\Sdk\Client;

$client = getClient();
authenticate($client, __DIR__ . '/logs/auth.log');

// The client is now ready for API requests
```

### 2. STK Push (Lipa Na M-PESA Online) 📱

Initiate an STK Push payment request using `StkPushService`:

```php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/Authentication.php';
require __DIR__ . '/StkPushService.php';

use Ramsey\Uuid\Uuid;

$service = new StkPushService();
$service->run();
```

### 3. Account Balance Query 📊

Check your account balance using `AccountBalanceService`:

```php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/Authentication.php';
require __DIR__ . '/AccountBalanceService.php';

$service = new AccountBalanceService();
$service->run();
```

### 4. B2C Payout 💸

Initiate a B2C payout using `B2CPayOutService`:

```php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/Authentication.php';
require __DIR__ . '/B2CPayOutService.php';

$service = new B2CPayOutService();
$service->run();

```

### 5. C2B URL Registration 🌐

Register C2B callback URLs using `C2BRegisterService`:

```php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/Authentication.php';
require __DIR__ . '/C2BRegisterService.php';

$service = new C2BRegisterService();
$service->run();
```

### 6. Transaction Status Query 🔍

Query transaction status using `TransactionStatusService`:

```php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/Authentication.php';
require __DIR__ . '/TransactionStatusService.php';

$service = new TransactionStatusService();
$service->run();
```

### 7. Transaction Reversal 🔄

Reverse a transaction using `TransactionReversalService`:

```php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/Authentication.php';
require __DIR__ . '/TransactionReversalService.php';

$service = new TransactionReversalService();
$service->run();
```

### 8. B2C Simulation 🧪

Simulate a B2C transaction using `SimulateTransactionService`:

```php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/Authentication.php';
require __DIR__ . '/SimulateTransactionService.php';

$service = new SimulateTransactionService();
$service->run();
```

---

## Configuration in pure PHP ⚙️

The `Authentication.php` file centralizes configuration. Customize it with:

```php
$config = [
    'base_url' => 'https://apisandbox.safaricom.et/mpesa/',
    'consumer_key' => 'your_consumer_key',
    'consumer_secret' => 'your_consumer_secret',
];
$securityCredential = 'your_security_credential';
```

## Configuration in Laravel ⚙️

Configuration is managed via `config/mpesa.php` in Laravel projects. After publishing the config file with `php artisan vendor:publish --tag=mpesa-config`, customize it with your credentials. Use environment variables in your `.env` file:

````env
APP_ENV=development #production
DEV_MPESA_BASE_URL=https://apisandbox.safaricom.et/
DEV_MPESA_CONSUMER_KEY=your_consumer_key
DEV_MPESA_CONSUMER_SECRET=your_consumer_secret
DEV_SECURITY_CREDENTIAL=your_security_credential

PROD_MPESA_BASE_URL=https://apisandbox.safaricom.et/
PROD_MPESA_CONSUMER_KEY=your_consumer_key
PROD_MPESA_CONSUMER_SECRET=your_consumer_secret
PROD_SECURITY_CREDENTIAL=your_security_credential

---

## Laravel Integration ⚙️

In a Laravel controller:

```php
namespace App\Http\Controllers;

use Mpesa\Sdk\Client;

class MpesaController extends Controller {
    public function testStkPush() {
        require __DIR__ . '/../../Authentication.php'; // Adjust path
        $client = getClient();
        authenticate($client);
        $stk = new \Mpesa\Sdk\StkPush($client);
        $response = $stk->processPayment([...]);
        return response()->json($response);
    }
}
````

---

## Notes 📝

- **Service Classes**: Each service class (e.g., `StkPushService`) encapsulates an API operation. Copy the full implementations from your project files.
- **Logging**: Logs are written to the `logs/` directory. Ensure it exists and is writable.
- **UUID**: Some examples use `ramsey/uuid`. Install it with:

  ```bash
  composer require ramsey/uuid
  ```

- **Sandbox vs Production**: Update `base_url` in `Authentication.php` for production use:

  ```php
  'base_url' => 'https://apisandbox.safaricom.et/mpesa'
  ```

---

## Instructions

1. Open your `README.md` file in your project.
2. Copy the entire content above.
3. Paste it into your `README.md`, replacing the existing content.
4. Save the file.

---

## Testing

This package uses Phpunit for testing. To run the tests, use the following command:

```bash
vendor/bin/phpunit  -
```

## Customization

- **Paths**: Adjust `require` paths based on where you place `Authentication.php` and service files.
- **Credentials**: Replace placeholders (`your_consumer_key`, `your_security_credential`) with your actual values.
- **Extra Sections**: Need "Contributing," "License," or "Troubleshooting" sections? Let me know, and I’ll expand it!

## Support 📧

- For issues or questions, open an issue on GitHub or contact **kahsay21a@gmail.com**
- Reach out on Telegram: [@KahsayG21](https://t.me/KahsayG21) 📩
