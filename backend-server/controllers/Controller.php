<?php
namespace common\controllers;

use \Exception;

abstract class Controller
{
    public $params;
    public function __construct($params)
    {
        $this->params = $params;
    }
    abstract protected function init();
}
?>
