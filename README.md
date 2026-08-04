# PixVerse PHP SDK for RunAPI

[![Packagist](https://img.shields.io/packagist/v/runapi-ai/pixverse)](https://packagist.org/packages/runapi-ai/pixverse)
[![License](https://img.shields.io/github/license/runapi-ai/pixverse-php)](https://github.com/runapi-ai/pixverse-php/blob/main/LICENSE)

The PixVerse PHP SDK is the language-specific package for PixVerse
on RunAPI. Use this package when your application needs Composer installs,
associative-array request bodies, task status lookup, and consistent RunAPI
errors in PHP.

This README is the PHP package guide for the public `pixverse-php` split
repository. For model details, use https://runapi.ai/models/pixverse; for API
reference, use https://runapi.ai/docs/api/pixverse/text-to-video; for SDK docs, use
https://runapi.ai/docs/resources/sdks.

## Install

```bash
composer require runapi-ai/pixverse
```

## Quick start

```php
<?php

require __DIR__ . "/vendor/autoload.php";

use RunApi\Pixverse\PixverseClient;

$client = new PixverseClient(); // reads RUNAPI_API_KEY

$imageToVideoTask = $client->imageToVideo->create([
    'model' => 'pixverse-v6',
    'duration_seconds' => 1,
    'enable_audio' => true,
    'enable_multi_clip' => true,
    'first_frame_image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg',
    'output_resolution' => '360p',
    'prompt' => 'Make it golden hour',
    'seed' => 1,
]);

$task = $client->textToVideo->create([
    'model' => 'pixverse-v6',
    'aspect_ratio' => '16:9',
    'duration_seconds' => 1,
    'enable_audio' => true,
    'enable_multi_clip' => true,
    'output_resolution' => '360p',
    'prompt' => 'A precise product render on white marble',
    'seed' => 1,
]);

$status = $client->textToVideo->get($task->id);

$result = $client->textToVideo->run([
    'model' => 'pixverse-v6',
    'aspect_ratio' => '16:9',
    'duration_seconds' => 1,
    'enable_audio' => true,
    'enable_multi_clip' => true,
    'output_resolution' => '360p',
    'prompt' => 'A serene mountain lake at dawn',
    'seed' => 1,
]);

echo $result->videos[0]->url . PHP_EOL;
```

Use `create()` to submit a task and return quickly, `get()` to fetch the latest
task state, and `run()` when a script should create and poll until completion.
In web request handlers, prefer `create()` plus webhook or later `get()`
polling so a worker is not held open.


RunAPI-generated file URLs are temporary. Download and store generated files
in your own durable storage within the retention window; do not treat returned
URLs as long-term assets.

## Language notes

Pass request parameters as associative arrays with snake_case keys. The
available resources are `textToVideo`, `imageToVideo`, `editVideo`, `extendVideo`, `transitionVideo`. Keep `RUNAPI_API_KEY` in the environment
or your secret manager; never commit API keys or callback secrets.

## Links

- Model page: https://runapi.ai/models/pixverse
- SDK docs: https://runapi.ai/docs/resources/sdks
- Product docs: https://runapi.ai/docs/api/pixverse/text-to-video
- Pricing and rate limits: https://runapi.ai/models/pixverse/pixverse-v6
- Full catalog: https://runapi.ai/models
- GitHub repository: https://github.com/runapi-ai/pixverse-php
- Multi-language SDK repository: https://github.com/runapi-ai/pixverse-sdk

## License

Licensed under the Apache License, Version 2.0.
