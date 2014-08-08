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
        $this->savePageFromJSON($data);
    }

    protected  function savePageFromJSON($data, $pageID = 0) {
        $slug = $data["slug"];
        $title = $data["title"];
        $content = isset($data["content"])? $data["content"] : "";
        $status = isset($data["status"])? $data["status"] : "draft";
        $pageTemplate = isset($data["template"])? $data["template"] : "";
        $parentSlug = isset($data["parent_slug"])? $data["parent_slug"] : "";


        $post = array();
        if($pageID != 0) {
            $post["ID"] = $pageID;
        }
        $post["post_type"] = "page";
        $post["post_name"] = $slug;
        $post["post_title"] = $title;
        $post["post_content"] = $content;
        $post["post_status"] = $status;
        if(!empty($parentSlug)) {
            $parentPage = get_page_by_path($parentSlug);
            if(!empty($parentPage)) {
                $post["post_parent"] = $parentPage->ID;
            }
        }
        if(!empty($pageTemplate)) {
            $post["page_template"] = $pageTemplate;
        }

        $result = wp_insert_post( $post, true);
        if (is_wp_error( $result ) ) {
            $this->errors[] = $result->get_error_message();
            return false;
        }
        return true;

    }
}