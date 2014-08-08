<?php
class DSCommandFactory {
    static $commands = null;
    public static function getCommand($cmdName) {
        if(empty($commands)) {
            $commands = array();
            $commands["add-page"] = new AddPageCommand();
            $commands["update-page"] = new UpdatePageCommand();
            $commands["add-category"] = new AddCategoryCommand();
        }
        if(isset($commands[$cmdName])){
            return $commands[$cmdName];
        }
        return null;
    }

}