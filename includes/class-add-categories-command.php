<?php

/**
 * Class AddCategoriesCommand
 * JSON Example:
 *     {
 *        "cmd": "add-categories",
 *        "data": [
 *           {
 *              "title": "Category 1",
 *              "slug": "cat-1",
 *              "children": []
 *           }
 *         ]
 *     }
 */
class AddCategoriesCommand extends DSCommand {

    public function execute($jsonCommand) {
        $data = $jsonCommand["data"];

    }
}