<?php
require_once( ABSPATH . '/wp-load.php');
abstract class DSCommand {
    protected  $errors = array();
    public function getErrors() {
        return $this->errors;
    }
    public abstract function execute($jsonCommand);
}