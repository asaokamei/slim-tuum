Slim3 + Tuum/Respond
=========

Code like Laravel on top of `Slim 3 framework` by using `Tuum/Respond` module.

Overview
-----

### License

MIT License

### PSR

PSR-1, PSR-2, PSR-4, and PSR-7. 

### Installation

not registered to packagist.org, yet. So...

```sh
$ git clone https://github.com/asaokamei/slim-tuum
$ cd slim-tuum
$ composer install
```

to view the Slim+Tuum in action,

```sh
$ cd public
$ php -S localhost:8888
```

and view the local site at `http://localhost:8888` using browser.

Code Snippets
-----

Tuum/Respond simplifies the construction of a PSR-7 response object. 

Let's take a close look at the PHP code for `http://localhost:8888/jump` as an example. 

### router.php

In `/app/routes.php` file defines routes for the `/jump` URL, which performs the following.

1. construct an HTML view response with default values, 
2. constructs a redirect with input data, errors, and a message, then 
3. constructs the HTML view response with the redirected information from #2. 

```php
/**
 * 1 and 3. constructing a HTML view response. 
 */
$app->get('/jump', function ($request, Response $response) {
    return Respond::view($request, $response)
        ->withReqAttribute('_token')
        ->asView('jump');
});

/**
 * 2. constructing a redirect response with various info. 
 */
$app->post('/jump', function (Request $request, Response $response) {
    return Respond::redirect($request, $response)
        ->withMessage('redirected back!')
        ->withInputData(['jumped' => 'redirected text'])
        ->withInputErrors(['jumped' => 'redirected error message'])
        ->toPath('jump');
});
```

### jump.twig and helpers

The view file, `app/twigs/jump.twig`, uses `viewData` helpers to manage redirected input values and errors. 

```html
{% extends "layouts/layout.twig" %}

{% block content %}

    <h1>Let's Jump!!</h1>

    {{ viewData.message|raw }}

    <p>This sample shows how to create a form input and shows the error message from the redirection.</p>

    <h3>Sample Form</h3>

    <form method="post" action="" class="">
        <input type="hidden" name="_token" value="{{ _token }}">

        <div class="form-group">
            <label for="text2">extra text</label>
            <input type="text" name="text2" id="text2" value="{{ viewData.inputs.get('jumped', 'original text') }}"
                   class="form-control"/>
            {{ viewData.errors.get('jumped')|raw }}
        </div>

        <input type="submit" value="jump!" class="btn btn-primary"/>&nbsp;
        <input type="button" value="clear" onclick="location.href='jump'" class="btn btn-default"/>

    </form>

{% endblock %}
```

* `{{ viewData.message }}` for messages. 
* `{{ viewData.inputs }}` for redirected input values. 
* `{{ viewData.errors }}` for redirected input errors. 
* There are `{{ viewData.forms }}` helper as well, which helps to generate html forms. The forms helper already contains the `inputs` helper to simplify the code even further. 

To understand how these helpers work, please check out the repository at github, [https://github.com/TuumPHP/Form](https://github.com/TuumPHP/Form)

Setup Slim 3
----

To use `Tuum/Respond` with Slim 3 framework, you must configure the $app. 

### TuumStack Middleware

TuumStack class is a middleware to setup respond module. The `app/app-twig.php` file constructs the middleware and adds it to the Slim3 application. 

```php
/**
 * Tuum/Respond extension
 */
$app->add(
    TuumStack::forgeTwig(
        __DIR__ . '/twigs',  // location of twig templates
        [
            // configs for twigs here.
        ],
        'layouts/contents', // an empty template to render any html inside a layout. 
        [
            // error html setting. 
            'default' => 'errors/error',
            'status'  => [
                '404' => 'errors/notFound',
                '403' => 'errors/forbidden',
            ],
            'handler' => false,
        ],
        $_COOKIE
        ));

```


#### twig and view

The `Tuum/Respond` comes with its own view renderer, `Tuum/View`, which is a really simple PHP based template. The advantage of using the raw PHP template is, that you can use the debugger's breakpoint. 

