<?php

/**
 * Class AddCategoriesCommand
 * Syntax:
 *     {
 *        "cmd": "add-category",
 *        "data": [
 *           {
 *              "title": "Category title",
 *              "slug": "cat-slug",
 *              "description": "description",
 *              "parent-slug": "slug of parent category"
 *              "children": [
 *                  {
 *                    "title": "Category title",
 *                    "slug": "cat-slug",
 *                    "description": "description"
 *                  },
 *                  ...
 *
 *              ]
 *           }
 *         ]
 *     }
 */

class AddCategoryCommand extends DSCommand {

    public function execute($jsonCommand) {
        $data = $jsonCommand["data"];
        return $this->createCategory($data, 0);
    }

    private function createCategory($jsonNode, $parentID = 0) {
        $title = $jsonNode["title"];
        $description = isset($jsonNode["description"]) ? $jsonNode["description"] : "";
        $slug = isset($jsonNode["slug"]) ? $jsonNode["slug"] : "";
        $category = array('cat_name' => $title,
                          'category_description' => $description,
                          'category_nicename' => $slug);
        if($parentID == 0) {
            $parentSlug = isset($jsonNode["parent-slug"]) ? $jsonNode["parent-slug"] : "";
            if(!empty($parentSlug)) {
                $parentCat = get_category_by_slug($parentSlug);
                if(!empty($parentCat)) {
                    $category['category_parent'] = $parentCat->term_id;
                }
            }
        } else {
            $category['category_parent'] = $parentID;
        }

        $result = wp_insert_category($category, true);
        if (is_wp_error( $result ) ) {
            $this->errors[] = $result->get_error_message();
            return false;
        }

        if(isset($jsonNode["children"]) &&
          !empty($jsonNode["children"])) {
            $children = $jsonNode["children"];
            foreach($children as $child) {
                $categoryResult = $this->createCategory($child, $result);
                if(!$categoryResult) {
                    return false;
                }
            }
        }
        return $result;
    }
}