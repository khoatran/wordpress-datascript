<?php
require_once( ABSPATH . '/wp-load.php');
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

        $wp_error = array();

        wp_insert_post( $post, $wp_error);

        var_dump($wp_error); die;




    }
}