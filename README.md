<p align="center">
    <p align="center">
        <a href="https://github.com/futuremeng/RAGFlow-laravel/actions"><img alt="GitHub Workflow Status (master)" src="https://img.shields.io/github/actions/workflow/status/futuremeng/RAGFlow-laravel/tests.yml?branch=main&label=tests&style=round-square"></a>
        <a href="https://packagist.org/packages/futuremeng/RAGFlow-laravel"><img alt="Total Downloads" src="https://img.shields.io/packagist/dt/futuremeng/RAGFlow-laravel"></a>
        <a href="https://packagist.org/packages/futuremeng/RAGFlow-laravel"><img alt="Latest Version" src="https://img.shields.io/packagist/v/futuremeng/RAGFlow-laravel"></a>
        <a href="https://packagist.org/packages/futuremeng/RAGFlow-laravel"><img alt="License" src="https://img.shields.io/github/license/futuremeng/RAGFlow-laravel"></a>
    </p>
</p>

---

**RAGFlow PHP** for Laravel is a community-maintained PHP API client that allows you to interact with the [RAGFlow API](https://beta.ragflow.com/docs/api-reference/introduction). If you or your business relies on this package, it's important to support the developers who have contributed their time and effort to create and maintain this valuable tool:

-   Nuno Maduro: **[github.com/sponsors/nunomaduro](https://github.com/sponsors/nunomaduro)**
-   Sandro Gehri: **[github.com/sponsors/gehrisandro](https://github.com/sponsors/gehrisandro)**

> **Note:** This repository contains the integration code of the **RAGFlow PHP** for Laravel. If you want to use the **RAGFlow PHP** client in a framework-agnostic way, take a look at the [futuremeng/RAGFlow-php-client](https://github.com/futuremeng/RAGFlow-php-client) repository.

## Get Started

> **Requires [PHP 8.1+](https://php.net/releases/)**

First, install RAGFlow via the [Composer](https://getcomposer.org/) package manager:

```bash
composer require futuremeng/RAGFlow-laravel
```

Next, execute the install command:

```bash
php artisan ragflow:install
```

This will create a `config/ragflow.php` configuration file in your project, which you can modify to your needs
using environment variables.
Blank environment variables for the RAGFlow API key and RAGFLOW_ENDPOINT id are already appended to your `.env` file.

```env
RAGFLOW_API_KEY=sk-...
and RAGFLOW_ENDPOINT=https://ragflow.com/api/v1
```

Finally, you may use the `RAGFlow` facade to access the RAGFlow API:

```php
use RAGFlow\Laravel\Facades\RAGFlow;

$result = RAGFlow::chat()->create([
    'message' =>'Hello!',
]);

echo $result->completion->content; // Hello! How can I assist you today?
```

## Configuration

Configuration is done via environment variables or directly in the configuration file (`config/ragflow.php`).

### RAGFlow API Key and RAGFLOW_ENDPOINT

Specify your RAGFlow API Key and RAGFLOW_ENDPOINT. This will be
used to authenticate with the RAGFlow API - you can find your API key
on your RAGFlow dashboard, at https://ragflow.com.

```env
RAGFLOW_API_KEY=
RAGFLOW_ENDPOINT=
```

### Request Timeout

The timeout may be used to specify the maximum number of seconds to wait
for a response. By default, the client will time out after 30 seconds.

```env
RAGFLOW_REQUEST_TIMEOUT=
```

## Usage

For usage examples, take a look at the [futuremeng/RAGFlow-php-client](https://github.com/futuremeng/RAGFlow-php-client) repository.

## Testing

The `RAGFlow` facade comes with a `fake()` method that allows you to fake the API responses.

The fake responses are returned in the order they are provided to the `fake()` method.

All responses are having a `fake()` method that allows you to easily create a response object by only providing the parameters relevant for your test case.

```php
use RAGFlow\Laravel\Facades\RAGFlow;
use RAGFlow\Responses\Completions\CreateResponse;

RAGFlow::fake([
    CreateResponse::fake([
        'choices' => [
            [
                'text' => 'awesome!',
            ],
        ],
    ]),
]);

$completion = RAGFlow::completions()->create([
    'model' => 'gpt-3.5-turbo-instruct',
    'prompt' => 'PHP is ',
]);

expect($completion['choices'][0]['text'])->toBe('awesome!');
```

After the requests have been sent there are various methods to ensure that the expected requests were sent:

```php
// assert completion create request was sent
RAGFlow::assertSent(Completions::class, function (string $method, array $parameters): bool {
    return $method === 'create' &&
        $parameters['model'] === 'gpt-3.5-turbo-instruct' &&
        $parameters['prompt'] === 'PHP is ';
});
```

For more testing examples, take a look at the [futuremeng/RAGFlow-php-client](https://github.com/futuremeng/RAGFlow-php-client#testing) repository.

---

RAGFlow PHP for Laravel is an open-sourced software licensed under the **[MIT license](https://opensource.org/licenses/MIT)**.
