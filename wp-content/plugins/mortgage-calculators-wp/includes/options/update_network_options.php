<?php

    add_action('network_admin_edit_wpmc_update_network_options', 'wpmc_update_network_options');
    function wpmc_update_network_options()
    {
        // Check if current user is a site administrator
        if (!current_user_can('manage_network_options')) {
            wp_die('You don\t have the privileges to do this operation (should be: site administrator).');
        }

        // $_POST[ 'option_page' ] below comes from a hidden input that Wordpress automatically generates for admin forms. The value equals to the admin page slug.
        $page_slug = sanitize_text_field($_POST[ 'option_page' ]);
        // Check that the request is coming from the administration area
        check_admin_referer($page_slug . '-options');
        // Cycle through the settings we're submitting. If there are any changes, update them.
        global $new_whitelist_options;
        $options = $new_whitelist_options[ $page_slug ];


        //cronCall($options);

        foreach ($options as $option) {
            if (isset($_POST[ $option ])) {
                if ('wpmc_one_msg_bdy' == $option || 'wpmc_mail_message' == $option || 'wpmc_two_msg_bdy' == $option || 'wpmc_three_msg_bdy' == $option || 'wpmc_five_msg_bdy' == $option || 'wpmc_six_msg_bdy' == $option) {
                    update_site_option($option, wp_kses_post($_POST[ $option ]));
                } else {
                    update_site_option($option, sanitize_text_field($_POST[ $option ]));
                }
            }
        }

        // Finally, after saving the settings, redirect to the settings page. ()
        $query_args = array( 'page' => 'wpmc' );
        if ($page_slug == 'wpmc_one') {
            $query_args['action'] = 'cal-one';
        } elseif ($page_slug == 'wpmc_two') {
            $query_args['action'] = 'cal-two';
        } elseif ($page_slug == 'wpmc_three') {
            $query_args['action'] = 'cal-three';
        } elseif ($page_slug == 'wpmc_four') {
            $query_args['action'] = 'cal-four';
        } elseif ($page_slug == 'wpmc_five') {
            $query_args['action'] = 'cal-five';
        } elseif ($page_slug == 'wpmc_six') {
            $query_args['action'] = 'cal-six';
        }
        $query_args['settings-updated'] = 'true';
        wp_redirect(add_query_arg($query_args, network_admin_url('admin.php')));
        exit();
    }
