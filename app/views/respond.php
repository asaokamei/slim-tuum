<?php
/** @var DataView $view */
use Tuum\Form\DataView;

$this->setLayout('layout');
?>
<h1>Slim and Tuum/Respond</h1>

<p>Hello!!!</p>

<?php

var_dump($view->data->raw('args', []));