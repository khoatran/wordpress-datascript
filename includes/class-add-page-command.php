<?php

/**
 * Class AddPageCommand
 * JSON Example: {
 *           "cmd": "add-page",
 *           "data": {
 *           "title": "test page",
 *           "slug": "test page",
 *           "content": "This is a test page"
 *          }
},
 */
class AddPageCommand extends DSCommand {

    public function execute($jsonCommand) {
        $data = $jsonCommand["data"];
        $slug = $data["slug"];
        $title = $data["title"];
        $content = isset($data["content"])? $data["content"]: "";
        $status = isset($data["status"])? $data["status"]: "draft";
        $pageTemplate = isset($data["template"])? $data["template"]: "";

        $post = array();
        $post["post_name"] = $slug;
        $post["post_title"] = $title;
        $post["post_content"] = $content;
        $post["post_status"] = $status;
        if(!empty($pageTemplate)) {
            $post["page_template"] = $pageTemplate;
        }
        //TODO handle other fields: category (need to force user input category slug)
        $result = wp_insert_post( $post, true);
        if (is_wp_error( $result ) ) {
            $this->errors[] = $result->get_error_message();
            return false;
        }
        return true;
    }
}