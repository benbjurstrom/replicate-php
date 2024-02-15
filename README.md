# Replicate PHP client
This is a framework-agnostic PHP client for [Replicate.com](https://replicate.com/) built on the amazing [Saloon v3](https://docs.saloon.dev/) 🤠 library. Use it to easily interact with machine learning models such as Stable Diffusion right from your PHP application.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/benbjurstrom/replicate-php.svg?style=flat-square)](https://packagist.org/packages/benbjurstrom/replicate-php)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/benbjurstrom/replicate-php/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/benbjurstrom/replicate-php/actions?query=workflow%3tests+branch%3Amain)

## Table of contents
- [Quick Start](https://github.com/benbjurstrom/replicate-php#-quick-start)
- [Using with Laravel](https://github.com/benbjurstrom/replicate-php#using-with-laravel)
- [Response Data](https://github.com/benbjurstrom/replicate-php#response-data)
- [Webhooks](https://github.com/benbjurstrom/replicate-php#webhooks)
- [Prediction Methods](https://github.com/benbjurstrom/replicate-php#available-prediction-methods)
    - [get](https://github.com/benbjurstrom/replicate-php#get)
    - [list](https://github.com/benbjurstrom/replicate-php#list)
    - [create](https://github.com/benbjurstrom/replicate-php#create)

## 🚀 Quick start

Install with composer.

```bash
composer require benbjurstrom/replicate-php
```
### 

Create a new api instance.
```php
use BenBjurstrom\Replicate\Replicate;
...

$api = new Replicate(
    apiToken: $_ENV['REPLICATE_API_TOKEN'],
);
```
###

Then use it to invoke your model (or in replicate terms "create a prediction").
```php
$version = 'db21e45d3f7023abc2a46ee38a23973f6dce16bb082a930b0c49861f96d1e5bf';
$input = [
    'model' => 'stable-diffusion-2-1',
    'prompt' => 'a photo of an astronaut riding a horse on mars',
    'negative_prompt' => 'moon, alien, spaceship',
    'width' => 768,
    'height' => 768,
    'num_inference_steps' => 50,
    'guidance_scale' => 7.5,
    'scheduler' => 'DPMSolverMultistep',
    'seed' => null,
];

$data = $api->predictions()->create($version, $input);
$data->id; // yfv4cakjzvh2lexxv7o5qzymqy
```
Note that the input parameters will vary depending on what version (model) you're using. In this example version [db21e45d3f7023abc2a46ee38a23973f6dce16bb082a930b0c49861f96d1e5bf](https://replicate.com/stability-ai/stable-diffusion/versions/db21e45d3f7023abc2a46ee38a23973f6dce16bb082a930b0c49861f96d1e5bf) is a Stable Diffusion 2.1 model optimized for speed.
###

## Using with Laravel
Begin by adding your credentials to your services config file.
```php
// config/services.php
'replicate' => [
    'api_token' => env('REPLICATE_API_TOKEN'),
],
```
###

Bind the `Replicate` class in a service provider.
```php
// app/Providers/AppServiceProvider.php
public function register()
{
    $this->app->bind(Replicate::class, function () {
        return new Replicate(
            apiToken: config('services.replicate.api_token'),
        );
    });
}
````
###

And use anywhere in your application.
```php
$data = app(Replicate::class)->predictions()->get($id);
```
###

Test your integration using Saloon's amazing [response recording](https://docs.saloon.dev/testing/recording-requests#fixture-path).
```php
use Saloon\Laravel\Saloon; // composer require sammyjo20/saloon-laravel "^2.0"
...
Saloon::fake([
    MockResponse::fixture('getPrediction'),
]);

$id = 'yfv4cakjzvh2lexxv7o5qzymqy';

// The initial request will check if a fixture called "getPrediction" 
// exists. Because it doesn't exist yet, the real request will be
// sent and the response will be recorded to tests/Fixtures/Saloon/getPrediction.json.
$data = app(Replicate::class)->predictions()->get($id);

// However, the next time the request is made, the fixture will 
// exist, and Saloon will not make the request again.
$data = app(Replicate::class)->predictions()->get($id);
```

## Response Data
All responses are returned as data objects. Detailed information can be found by inspecting the following class properties:

* [PredictionData](https://github.com/benbjurstrom/replicate-php/blob/main/src/Data/PredictionData.php)
* [PredictionsData](https://github.com/benbjurstrom/replicate-php/blob/main/src/Data/PredictionsData.php)

## Webhooks
Replicate allows you to configure a webhook to be called when your prediction is complete. To do so chain `withWebhook($url)` onto your api instance before calling the `create` method. For example:

```php
$api->predictions()->withWebhook('https://www.example.com/webhook')->create($version, $input);
$data->id; // la5xlbbrfzg57ip5jlx6obmm5y
```

## Available Prediction Methods
### get()
Use to get details about an existing prediction. If the prediction has completed the results will be under the output property.
```php
use BenBjurstrom\Replicate\Data\PredictionData;
...
$id = 'la5xlbbrfzg57ip5jlx6obmm5y'
/* @var PredictionData $data */
$data = $api->predictions()->get($id);
$data->output[0]; // https://replicate.delivery/pbxt/6UFOVtl1xCJPAFFiTB2tfveYBNRLhLmJz8yMQAYCOeZSFhOhA/out-0.png
```

### list()
Use to get a cursor paginated list of predictions. Returns an PredictionsData object.
```php
use BenBjurstrom\Replicate\Data\PredictionsData
...

/* @var PredictionsData $data */
$data = $api->predictions()->list(
    cursor: '123', // optional
);

$data->results[0]->id; // la5xlbbrfzg57ip5jlx6obmm5y

```
### create()
Use to create a new prediction (invoke a model). Returns an PredictionData object.
```php
use BenBjurstrom\Replicate\Data\PredictionData;
...
$version = '5c7d5dc6dd8bf75c1acaa8565735e7986bc5b66206b55cca93cb72c9bf15ccaa';
$input = [
    'text' => 'Alice'
];

/* @var PredictionData $data */
$data = $api->predictions()
    ->withWebhook('https://www.example.com/webhook') // optional
    ->create($version, $input);
$data->id; // la5xlbbrfzg57ip5jlx6obmm5y
```

## Credits

- [Ben Bjurstrom](https://github.com/benbjurstrom)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
