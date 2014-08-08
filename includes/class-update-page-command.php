<?php

/**
 * Class UpdatePageCommand
 * JSON Example: {
 *           "cmd": "update-page",
 *           "data": {
 *           "title": "test page",
 *           "slug": "test page",
 *           "content": "This is a test page"
 *          }
},
 */
class UpdatePageCommand extends AddPageCommand {

    public function execute($jsonCommand) {
        $data = $jsonCommand["data"];
        $existingPage = get_page_by_path($data["slug"]);
        if(empty($existingPage)) {
            $this->errors[] = "Page does not exist";
            return false;
        }
        return $this->savePageFromJSON($data, $existingPage->ID);
    }
}