<?php
abstract class DSCommand {
    protected  $errors = array();
    public function getErrors() {
        return $this->errors;
    }
    public abstract function execute($jsonCommand);
}