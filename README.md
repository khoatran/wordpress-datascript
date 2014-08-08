#wordpress-datascrip

A Wordpress plugin that allows developers to create a JSON data script to transfer configuration data (page, category, menu structure, settings) from dev to staging, production environment

## Usage
Just download and install it as normal Wordpress plugin. Then, go to Wordpress admin, click to WP Datascript menu item. You will see an editor for inputting the JSON script + 1 button to execute the script.

##JSON datascript

Here is the structure of the JSON datascript.

    [
        {
           "cmd": command_name,
           "data": {
               data_input_for_the_command
           }
        }
    ]
Depends on command, input data is different.

Below is the list of commands that I am supporting currently.

### Add page command

Syntax:

    [
        {
           "cmd": "add-page",
           "data": {
               "slug": "slug-of-page",
               "title": "title-of-page",
               "content": "page-content",
               "status" : "status-of-WP-post ['draft'|'publish'|'pending'| 'future' | 'private' | custom registered status ]",
               "template": "page-template",
               "parent_slug": "slug-of-parent-page if-any"
           }
        }
    ]
    
### Update page command

Syntax:

    [
        {
           "cmd": "update-page",
           "data": {
               "slug": "slug-of-page",
               "title": "title-of-page",
               "content": "page-content",
               "status" : "status-of-WP-post ['draft'|'publish'|'pending'| 'future' | 'private' | custom registered status ]",
               "template": "page-template",
               "parent_slug": "slug-of-parent-page if-any"
           }
        }
    ]
### Add category

Syntax:

    [
        {
           "cmd": "add-category",
           "data": {
               "slug": "slug-of-category",
               "title": "title-of-category",
               "description": "description",
               "parent-slug" : "slug of parent category - only apply at this level",
               "children": [
                  {
                  "slug": "slug-of-child-category",
                  "title": "title of child category",
                  "description": "description",
                  },
                  ...
               ]
           }
        }
    ]    