<?php

/**
 * Class UpdateCategoriesCommand
 * JSON Example:
 *     {
 *        "cmd": "update-category",
 *        "data": [
 *           {
 *              "title": "Category title",
 *              "slug": "cat-slug",
 *              "new_slug": "new-slug-name",
 *              "description": "description of category"
 *           }
 *         ]
 *     }
 */

class UpdateCategoryCommand extends DSCommand {

    public function execute($jsonCommand) {
        $data = $jsonCommand["data"];
        return $this->updateCategory($data);
    }

    private function updateCategory($jsonNode, $parentID = 0) {

        $slug = isset($jsonNode["slug"]) ? $jsonNode["slug"] : "";
        $currentCategory = get_category_by_slug($slug);
        if(empty($currentCategory)) {
            $this->errors[] = "Category does not exist";
            return false;
        }

        $parentSlug = isset($jsonNode["parent-slug"]) ? $jsonNode["parent-slug"] : "";
        $data = array();
        if(!empty($parentSlug)) {
            $parentCat = get_category_by_slug($parentSlug);
            $data["parent"] = $parentCat->ID;

        }
        if(isset($jsonNode["title"]) && !empty($jsonNode["title"])) {
            $data["name"] = $jsonNode["title"];
        }

        if(isset($jsonNode["new_slug"]) && !empty($jsonNode["new_slug"])) {
            $data["slug"] = $jsonNode["new_slug"];
        }

        if(isset($jsonNode["description"]) && !empty($jsonNode["description"])) {
            $data["description"] = $jsonNode["description"];;
        }

        $result = wp_update_term($currentCategory->ID, 'category', $data);
        if (is_wp_error( $result ) ) {
            $this->errors[] = $result->get_error_message();
            return false;
        }

        return true;
    }
}