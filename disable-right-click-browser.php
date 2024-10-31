<?php

/*
* Plugin Name: Disable Right Click
* Author: Vaibhav Gangrade
* Version: 1.1
* Description: Disable right click and prevent screenshot and print screen to protect content.
* Author URI: https://example.com
* License: GPL-2.0+
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) exit; // Prevent Direct Access

if (!function_exists('DRC_load_scripts')) {
    function DRC_load_scripts() {
        // Enqueue jQuery
        wp_enqueue_script('jquery');

        // Register the main script
        wp_register_script('drc-main-script', false);

        // Add inline script
        $inline_script = '
        jQuery(document).ready(function($) {
            // Disable right-click
            jQuery("body").on("contextmenu", function() {
                return false;
            });

            // Disable specific key combinations
            jQuery(document).keydown(function(event) {
                if (event.keyCode == 123) { // Prevent F12
                    return false;
                } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) { // Prevent Ctrl+Shift+I
                    return false;
                } else if (event.ctrlKey && event.keyCode == 85) { // Prevent Ctrl+U
                    return false;
                } else if (event.keyCode == 44) { // Prevent PrintScreen
                    alert("Screenshots are disabled.");
                    return false;
                }
            });

            // Attempt to detect and prevent screenshot via clipboard
            jQuery(window).blur(function() {
                setTimeout(function() {
                    try {
                        let image = new Image();
                        image.src = "";
                        image.onerror = function() {
                            console.log("Attempt to capture screenshot detected.");
                            // You can add additional actions here, e.g., blur the content
                            jQuery("body").css("visibility", "hidden");
                            setTimeout(function() {
                                jQuery("body").css("visibility", "visible");
                            }, 2000);
                        };
                    } catch (e) {}
                }, 200);
            });
        });
        ';

        wp_add_inline_script('drc-main-script', $inline_script);
        wp_enqueue_script('drc-main-script');
    }
    add_action('wp_enqueue_scripts', 'DRC_load_scripts');
}
?>
