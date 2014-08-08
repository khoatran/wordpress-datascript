<?php
/*
Plugin Name: WordPress DataScript
Plugin URI:
Description: A plugin that help you to put data changes of wordpress in post/page/cagegory in scripting
Author: Tran Dang Khoa
Author URI: excitingthing.com
Version: 0.6.1
Text Domain: wordpress-datascript
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

if(!function_exists('wp_get_current_user')) {
    require_once(ABSPATH . "wp-includes/pluggable.php");
}
if(!function_exists('wp_insert_category')) {
    require_once(ABSPATH . "wp-admin/includes/taxonomy.php");
}

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPDataScript' ) ) :
$wp_rewrite = new WP_Rewrite();

/**
 * WordPress DataScript class to handle the data script loading and processing
 */

class WPDataScript {
    private $errors = array();
    private $success = false;
    private  $dataScript;
    private function includes() {
        include_once( 'includes/class-ds-command.php' );
        include_once( 'includes/class-command-factory.php' );
        include_once( 'includes/class-add-page-command.php' );
        include_once( 'includes/class-update-page-command.php');
        include_once( 'includes/class-add-category-command.php' );
        include_once( 'includes/class-update-category-command.php');
    }

    /**
     * WPDataScript Constructor.
     * @access public
     * @return
     */
    public function __construct() {

        $this->includes();
        if(isset($_POST['wp-datascript-action']) && $_POST['wp-datascript-action'] == "process" ){
            $this->processSubmitData();
        }
        add_action('admin_menu', array($this, 'addToolsMenu'));

    }

    public function processDataScript($dataScript) {
        $commandData = json_decode($dataScript, true);
        if($commandData == null) {
            $this->errors[] = "Invalid JSON data";
            return;
        }
        foreach($commandData as $command) {
            $cmdName = $command['cmd'];
            $actionCommand = DSCommandFactory::getCommand($cmdName);
            if(!empty($actionCommand)) {
                $result = $actionCommand->execute($command);
                $commandErrors = $actionCommand->getErrors();
                if(!$result && !empty($commandErrors)) {
                    $this->errors[] = $commandErrors[0];
                }
            } else {
                $this->errors[] = "Command [".$cmdName."] is not supported";
            }
        }

        $this->success = empty($this->errors);

    }

    protected function processSubmitData() {
        $this->dataScript = $_POST['datascript'];
        $this->processDataScript($this->dataScript);
    }


    public function addToolsMenu(){
        add_menu_page( "Wordpress Datascript",
                        "Wordpress DataScript",
                        "manage_options",
                        'wp-datascript',
                        array($this, 'wpDataScriptCallBack'));

    }

    public function wpDataScriptCallBack() { ?>

        <div class="wrap"><div id="icon-tools" class="icon32"></div>
            <h2>Wordpress DataScript</h2>
        </div>
        <div id="submit-script-form">
            <form action="" method="post" id="datascript-form">
                <?php
                if(isset($_POST['wp-datascript-action'])) {

                    ?>

                    <textarea id="datascript" name="datascript" rows="25" cols="80"><?php echo $this->dataScript; ?></textarea>
                    <?php
                    if(!empty($this->errors)) {
                        ?>
                        <p>
                                <span>
                                    <?php echo $this->errors[0]; ?>
                                </span>
                        </p>

                    <?php
                    } else { ?>
                        <p>
                                <span>
                                    Commands run successfully
                                </span>
                        </p>
                    <?php }
                } else {
                    ?>
                    <textarea id="datascript" name="datascript" rows="25" cols="80"></textarea>
                    <?php
                }
                ?>

                <p class="submit">
                    <input type="hidden" name="wp-datascript-action" value="process"/>
                    <input type="submit" class="button-primary" name="Submit" value="Submit"/>
                </p>

            </form>

        </div>
    <?php
    }
}




    $GLOBALS['WPDataScript'] = new WPDataScript();


endif;

?>