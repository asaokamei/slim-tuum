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

not registered to packagist.org. So...

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

### Demo Site

This repository also contains more elaborate demo site, 
which can be accessed by running the following command at 
`public` directory. 

```sh
$ php -S localhost:8888 demo.php
```


Code Snippets
-----

This section demonstrates some code used in demo site. 

### Redirection and Route Name

`/app-demo/routes.php` file defines routes for the `/toHome` URL; 


```php
// home page
$app->get('/', function (ServerRequestInterface $request) {
    // 1. renders home page, index. 
    return $this->responder->view($request)->render('index');
})->setName('home'); // 2. set route name as "home"

// redirect to home page.
$app->get('/toHome', function (ServerRequestInterface $request) {
    // redirect to "home" page
    return $this->responder->redirect($request)
                           ->setSuccess('redirected to "HOME"')
                           ->toRoute('home');
```

1. renders home page at route: `/`.
2. set route name as "home" to the route `/`.
3. redirect to "home" url. 


### Validation Error

`app-demo/Controller/JumpController.php` file defines routes for 
validation error sample. 

`onGet` method to set default values for each input fields, 
then renders form using `showForm` method.

```php
    /**
     * 1. show upload form.
     *
     * @return ResponseInterface
     */
    public function onGet()
    {
        $this->view()
            ->setData([
                // 2. set default values.
            ]);
        return $this->showForm();
    }

    /**
     * @return mixed
     */
    private function showForm()
    {
        return $this->view()->render('jump');
    }
```

`onPost` method is responsible to take care of the form input.
The form is rendered with validation errors and messages as a 
default behavior. But if, `_redirect` value is set, ... 

```php
    /**
     * take care the uploaded file.
     * show the upload form with uploaded file information.
     *
     * @return ResponseInterface
     */
    public function onPost()
    {
        // 3. set error and validation errors and message.s
        $this->getViewData()
            ->setInput($this->getPost())
            ->setInputErrors([
                'jumped' => 'redirected error message',
                'date'   => 'your date',
                'gender' => 'your gender',
                'movie'  => 'selected movie',
                'happy'  => 'be happy!'
            ]);

        // 4. redirect back to the form page
        if ($this->getPost('_redirect')) {
            return $this->redirect()
                ->setError('redirected back!')
                ->toPath('jump');
        }
        // 5. or show form page with validation errors. 
        $this->view()                          
            ->setError('redrawn form!');

        return $this->showForm();
    }
```

this code redirect back to the form url (i.e. `onGet` method). 

The validation errors and messages are saved in session flash storage, 
and restored automatically in the form page. 


### Templates and Forms

The validation errors, messages, and form input values are 
restored in the form, with some magical code as shown in 
the following twig code. 

```html
{% set value = view.inputs.raw(name, view.data.raw(name)) %}
{% set attribute = view.forms.newAttribute({
    'class': 'form-control'
}) %}
{% if attr is defined %}
    {% set attribute = attribute.fillAttributes(attr) %}
{% endif %}
<div class="form-group{{ view.errors.ifExists(name, null, ' has-error') }}">
    <div class="col-sm-2">
        <label for="{{ name }}">{{ title }}</label>
    </div>
    <div class="col-sm-10">
        <input type="text" name="{{ name }}" id="{{ name }}" value="{{ value }}" {{ attribute|raw }}>
        {% if view.errors.exists(name) %}
            <p class="text-danger">{{ view.errors.raw(name)}}</p>
        {% endif %}
    </div>
    <div class="clearfix"></div>
</div>
```

* `view`: contains all the values set by the responder. 
* `view.inputs`: contains input data by `setInputs` method.  
* `view.errors`: contains validation errors by `setInputErrors` method. 
* `view.data`: contains data set by `setData` method.

