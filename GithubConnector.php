<?php
/*
Plugin Name: Github Connector
Description: plugin de test
Version: 0.1
Author: Jean Michel Tatout
Author URI: https://www.linkedin.com/in/jmtatout/
License: GPL3
*/

class GitConnector
{
    public function __construct()
    {
        include_once plugin_dir_path( __FILE__ ).'/GitConnector_Widget.php';
        add_action('widgets_init', function(){register_widget('GitConnector_Widget');});
    }
}

new GitConnector;