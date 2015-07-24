<?php

use Tuum\Form\DataView;
use Tuum\View\Renderer;

/** @var Renderer $this */
/** @var $view DataView  */

$this->setLayout('layouts/layout');

?>

<?= $view->data->raw('contents'); ?>