<?php
    // Define Options When Admin Initializes
    function wpmc_admin_init()
    {
        // Calculator Mail Options
        // Set Section or Option-Group
        add_settings_section('wpmc_mail', '', 'wpmc_mail_display_shortcode', 'wpmc-settings-mail');

        if (is_multisite() && !is_network_admin()) {
            add_settings_field('wpmc_mail_use_network_settings', __('Use Network Settings', 'wpmc'), 'mcwp_checkbox', 'wpmc-settings-mail', 'wpmc_mail', array('wpmc_mail_use_network_settings','wpmc_mail'));
            register_setting('wpmc_mail', 'wpmc_mail_use_network_settings');
        }
        $text = 'display_text_element';
        $textarea = 'mcwp_textarea';

        $allfields = array(
            array( 'mcwp_currency', 'type'=> 'mcwp_currency', 'section_name'=>'wpmc-settings-mail', 'label'=>__('Currency', 'wpmc'), 'group'=>'wpmc_mail', 'placeholder' => __('Choose Currency', 'wpmc'), ),
            array( 'mcwp_color', 'type'=> 'color_input', 'section_name'=>'wpmc-settings-mail', 'label'=> __('Calculator Color', 'wpmc'), 'group'=>'wpmc_mail',  'placeholder' => __('Choose Color', 'wpmc'), ),
            array( 'wpmc_mail_from', 'type'=> $text, 'section_name'=>'wpmc-settings-mail', 'label'=>__('From Email', 'wpmc'), 'group'=>'wpmc_mail',  'placeholder' => __('Email address your leads will receive email from...', 'wpmc'), ),
            array( 'wpmc_mail_from_name', 'type'=> $text, 'section_name'=>'wpmc-settings-mail', 'label'=>__('From Name', 'wpmc'), 'group'=>'wpmc_mail',  'placeholder' => __('Name that your leads will receive email from...', 'wpmc'), ),
            array( 'wpmc_mail_reply_to', 'type'=> $text, 'section_name'=>'wpmc-settings-mail', 'label'=>__('Reply-To Email', 'wpmc'), 'group'=>'wpmc_mail',  'placeholder' => __('Email address your leads will reply to if different from above...', 'wpmc'), ),
            array( 'wpmc_mail_message', 'type'=> $textarea, 'section_name'=>'wpmc-settings-mail', 'label'=>__('Message', 'wpmc'), 'group'=>'wpmc_mail',  'placeholder' => __('This message will appear at the top of the emails sent to your leads...', 'wpmc'), ),
                    );

        foreach ($allfields as $key=>$val) {
            $argsF = array();
            $fId = $val[0];
            $callback = $val['type'];
            $fTitle = __($val['label'], 'wpmc');
            $fPage = $val['section_name'];
            $fGroup = $val['group'];
            //add_settings_field($val[0], $val['label'], $val['type'], $val['section_name'], $val['group'], $val);
            if ($callback == 'color_input') {
                $val['sanitize_callback'] = 'sanitize_hex_color';
                /*
                $argsF = array(
                    //'default' => '#000000',
                    'sanitize_callback' => 'sanitize_hex_color' //validates 3 or 6 digit HTML hex color code
                );
                */
            }
            add_settings_field($fId, $fTitle, $callback, $fPage, $fGroup, $val);
            register_setting($val['group'], $val[0]);
        }

        // Calculator One Options
        // Set Section or Option-Group
        add_settings_section('wpmc_one', '', 'wpmc_one_display_shortcode', 'wpmc-settings-one');

        if (is_multisite() && !is_network_admin()) {
            add_settings_field('wpmc_one_use_network_settings', __('Use Network Settings', 'wpmc'), 'mcwp_checkbox', 'wpmc-settings-one', 'wpmc_one', array('wpmc_one_use_network_settings','wpmc_one'));
            register_setting('wpmc_one', 'wpmc_one_use_network_settings');
        }
        $allfields = array(
             array( 'wpmc_one_email', 'type'=> $text, 'section_name'=>'wpmc-settings-one', 'label'=>__('Email Address', 'wpmc'), 'group'=>'wpmc_one',
                            'placeholder' => __('Email address your leads will be sent to...', 'wpmc'), ),
            array(
              'wpmc_one_msg_bdy',
              'type'=> 'msg_body',
              'section_name'=>'wpmc-settings-one',
              'label'=>'
              '.__('Message Body', 'wpmc').' <br /><br />
              <span style="font-weight: 400">'.__('Available Tags', 'wpmc').': <br />
			  [calculation_result]<br />
              [principal-and-interest]<br />
              [monthly-taxes]<br />
              [monthly-insurance]<br />
              [monthly-hoa]<br />
              [purchase-price]<br />
              [mortgage-term]<br />
              [down-payment]<br />
              [annual-taxes]<br />
              [annual-insurance]<br />
              </span>',
              'group'=>'wpmc_one', ),

            array( 'wpmc_one_disclaimer', 'type'=> $textarea, 'section_name'=>'wpmc-settings-one', 'label'=>__('Disclaimer', 'wpmc'), 'group'=>'wpmc_one',
                            'placeholder' => __('If you would like to display a disclaimer under the calculator\'s values, place that here...', 'wpmc'), ),

            array( 'wpmc_one_pp', 'type'=> $text, 'section_name'=>'wpmc-settings-one', 'label'=> __('Purchase Price', 'wpmc'), 'group'=>'wpmc_one', ),
            array( 'wpmc_one_dp', 'type'=> $text, 'section_name'=>'wpmc-settings-one', 'label'=> __('Down Payment', 'wpmc'), 'group'=>'wpmc_one', ),
            array( 'wpmc_one_ir', 'type'=> $text, 'section_name'=>'wpmc-settings-one', 'label'=> __('Interest Rate', 'wpmc'), 'group'=>'wpmc_one', ),
            array( 'wpmc_one_mt', 'type'=> $text, 'section_name'=>'wpmc-settings-one', 'label'=> __('Mortgage Term (Year)', 'wpmc'), 'group'=>'wpmc_one', ),
            array( 'wpmc_one_at', 'type'=> $text, 'section_name'=>'wpmc-settings-one', 'label'=> __('Annual Taxes', 'wpmc'), 'group'=>'wpmc_one', ),

            array( 'mcwp_hide_insurance_one', 'type'=> 'mcwp_dropdown', 'section_name'=>'wpmc-settings-one', 'label'=> __('Hide Insurance', 'wpmc'), 'group'=>'wpmc_one', ),
            array( 'wpmc_one_ai', 'type'=> $text, 'section_name'=>'wpmc-settings-one', 'label'=> __('Annual Insurance', 'wpmc'), 'group'=>'wpmc_one', ),

            array( 'mcwp_hide_hoa_one', 'type'=> 'mcwp_dropdown', 'section_name'=>'wpmc-settings-one', 'label'=> __('Hide HOA', 'wpmc'), 'group'=>'wpmc_one', ),
            array( 'wpmc_one_mhoa', 'type'=> $text, 'section_name'=>'wpmc-settings-one', 'label'=> __('Monthly HOA', 'wpmc'), 'group'=>'wpmc_one', ),

            array( 'wpmc_one_pp_initial', 'type'=> $text, 'section_name'=>'wpmc-settings-one', 'label'=> __('Purchase Price', 'wpmc'), 'group'=>'wpmc_one', 'placeholder' => __('Default: 250,000', 'wpmc'), ),
            array( 'wpmc_one_dp_initial', 'type'=> $text, 'section_name'=>'wpmc-settings-one', 'label'=> __('Down Payment %', 'wpmc'), 'group'=>'wpmc_one', 'placeholder' => __('Default: 5', 'wpmc'), ),
            array( 'wpmc_one_ir_min', 'type'=> $text, 'section_name'=>'wpmc-settings-one', 'label'=> __('Interest Rate %', 'wpmc'), 'group'=>'wpmc_one', 'placeholder' => __('Default: 5', 'wpmc'), ),
            array( 'wpmc_one_at_initial', 'type'=> $text, 'section_name'=>'wpmc-settings-one', 'label'=>__('Annual Taxes %', 'wpmc'), 'group'=>'wpmc_one', 'placeholder' => __('Default: 1', 'wpmc'), ),

            array( 'wpmc_one_ai_initial', 'type'=> $text, 'section_name'=>'wpmc-settings-one', 'label'=>__('Annual Insurance', 'wpmc'), 'group'=>'wpmc_one', 'placeholder' => __('Default: 600', 'wpmc'), ),
            array( 'wpmc_one_mhoa_initial', 'type'=> $text, 'section_name'=>'wpmc-settings-one', 'label'=>__('Monthly HOA', 'wpmc'), 'group'=>'wpmc_one', 'placeholder' => __('Default: 50', 'wpmc'), ),
        );

        foreach ($allfields as $key=>$val) {
            add_settings_field($val[0], __($val['label'], 'wpmc'), $val['type'], $val['section_name'], $val['group'], $val);
            register_setting($val['group'], $val[0]);
        }

        // Calculator Two Options
        // Set Section or Option-Group
        add_settings_section('wpmc_two', '', 'wpmc_two_display_shortcode', 'wpmc-settings-two');

        if (is_multisite() && !is_network_admin()) {
            add_settings_field('wpmc_two_use_network_settings', __('Use Network Settings', 'wpmc'), 'mcwp_checkbox', 'wpmc-settings-two', 'wpmc_two', array('wpmc_two_use_network_settings','wpmc_two'));
            register_setting('wpmc_two', 'wpmc_two_use_network_settings');
        }

        $allfields = array(
            array( 'wpmc_two_email', 'type'=> $text, 'section_name'=>'wpmc-settings-two', 'label'=>__('Email Address', 'wpmc'), 'group'=>'wpmc_two',
                            'placeholder' => __('Email address your leads will be sent to...', 'wpmc'), ),

            array(
              'wpmc_two_msg_bdy',
              'type'=> 'msg_body',
              'section_name'=>'wpmc-settings-two',
              'label'=>'
              '.__('Message Body', 'wpmc').' <br /><br />
              <span style="font-weight: 400">'.__('Available Tags', 'wpmc').': <br />
			  [calculation_result]<br />
              [principal-and-interest]<br />
              [monthly-taxes]<br />
              [monthly-insurance]<br />
              [monthly-hoa]<br />
              [purchase-price]<br />
              [mortgage-term]<br />
              [down-payment]<br />
              [annual-taxes]<br />
              [annual-insurance]<br />
              [interest-rate]<br />
              [monthly-mortgage-insurance]<br />
              [purchase-price]',
              'group'=>'wpmc_two', ),


            array( 'wpmc_two_disclaimer', 'type'=> $textarea, 'section_name'=>'wpmc-settings-two', 'label'=>__('Disclaimer', 'wpmc'), 'group'=>'wpmc_two',
                            'placeholder' => __('If you would like to display a disclaimer under the calculator\'s values, place that here...', 'wpmc'), ),

            array( 'wpmc_two_pp', 'type'=> $text, 'section_name'=>'wpmc-settings-two', 'label'=>__('Purchase Price', 'wpmc'), 'group'=>'wpmc_two', ),
            array( 'wpmc_two_dp', 'type'=> $text, 'section_name'=>'wpmc-settings-two', 'label'=>__('Down Payment', 'wpmc'), 'group'=>'wpmc_two', ),
            array( 'wpmc_two_ir', 'type'=> $text, 'section_name'=>'wpmc-settings-two', 'label'=>__('Interest Rate', 'wpmc'), 'group'=>'wpmc_two', ),
            array( 'wpmc_two_mt', 'type'=> $text, 'section_name'=>'wpmc-settings-two', 'label'=>__('Mortgage Term (Year)', 'wpmc'), 'group'=>'wpmc_two', ),
            array( 'wpmc_two_at', 'type'=> $text, 'section_name'=>'wpmc-settings-two', 'label'=>__('Annual Taxes', 'wpmc'), 'group'=>'wpmc_two', ),

            array( 'mcwp_hide_insurance_two', 'type'=> 'mcwp_dropdown', 'section_name'=>'wpmc-settings-two', 'label'=> __('Hide Insurance', 'wpmc'), 'group'=>'wpmc_two', ),
            array( 'wpmc_two_ai', 'type'=> $text, 'section_name'=>'wpmc-settings-two', 'label'=>__('Annual Insurance', 'wpmc'), 'group'=>'wpmc_two', ),

            array( 'mcwp_hide_hoa_two', 'type'=> 'mcwp_dropdown', 'section_name'=>'wpmc-settings-two', 'label'=> __('Hide HOA', 'wpmc'), 'group'=>'wpmc_two', ),
            array( 'wpmc_two_mhoa', 'type'=> $text, 'section_name'=>'wpmc-settings-two', 'label'=>__('Monthly HOA', 'wpmc'), 'group'=>'wpmc_two', ),

            array( 'wpmc_two_pp_initial', 'type'=> $text, 'section_name'=>'wpmc-settings-two', 'label'=>__('Purchase Price', 'wpmc'), 'group'=>'wpmc_two',  'placeholder' =>__('Default: 250,000', 'wpmc'), ),
            array( 'wpmc_two_dp_initial', 'type'=> $text, 'section_name'=>'wpmc-settings-two', 'label'=>__('Down Payment %', 'wpmc'), 'group'=>'wpmc_two',  'placeholder' => __('Default: 5', 'wpmc'), ),
            array( 'wpmc_two_ir_min', 'type'=> $text, 'section_name'=>'wpmc-settings-two', 'label'=>__('Interest Rate %', 'wpmc'), 'group'=>'wpmc_two',  'placeholder' => __('Default: 5', 'wpmc'), ),
            array( 'wpmc_two_at_initial', 'type'=> $text, 'section_name'=>'wpmc-settings-two', 'label'=>__('Annual Taxes %', 'wpmc'), 'group'=>'wpmc_two',  'placeholder' => __('Default: 1', 'wpmc'), ),
            array( 'wpmc_two_ai_initial', 'type'=> $text, 'section_name'=>'wpmc-settings-two', 'label'=>__('Annual Insurance', 'wpmc'), 'group'=>'wpmc_two',  'placeholder' => __('Default: 600', 'wpmc'), ),
            array( 'wpmc_two_mhoa_initial', 'type'=> $text, 'section_name'=>'wpmc-settings-two', 'label'=>__('Monthly HOA', 'wpmc'), 'group'=>'wpmc_two',  'placeholder' => __('Default: 50', 'wpmc'), ),
        );

        foreach ($allfields as $key=>$val) {
            add_settings_field($val[0], $val['label'], $val['type'], $val['section_name'], $val['group'], $val);
            register_setting($val['group'], $val[0]);
        }

        // Calculator Three Options
        // Set Section or Option-Group
        add_settings_section('wpmc_three', '', 'wpmc_three_display_shortcode', 'wpmc-settings-three');

        if (is_multisite() && !is_network_admin()) {
            add_settings_field('wpmc_three_use_network_settings', __('Use Network Settings', 'wpmc'), 'mcwp_checkbox', 'wpmc-settings-three', 'wpmc_three', array('wpmc_three_use_network_settings','wpmc_three'));
            register_setting('wpmc_three', 'wpmc_three_use_network_settings');
        }

        $allfields = array(
            array( 'wpmc_three_email', 'type'=> $text, 'section_name'=>'wpmc-settings-three', 'label'=> __('Email Address', 'wpmc'), 'group'=>'wpmc_three',
                            'placeholder' => __('Email address your leads will be sent to...', 'wpmc'),
                        ),
            array(
              'wpmc_three_msg_bdy',
              'type'=> 'msg_body',
              'section_name'=>'wpmc-settings-three',
              'label'=>'
              '.__('Message Body', 'wpmc').' <br /><br />
              <span style="font-weight: 400">'.__('Available Tags', 'wpmc').': <br />
			  [calculation-result]<br />
              [amount-financed]<br />
              [annual-insurance]<br />
              [annual-taxes]<br />
              [down-payment]<br />
              [interest-rate]<br />
              [monthly-hoa]<br />
              [monthly-insurance]<br />
              [monthly-taxes]<br />
              [mortgage-term]<br />
              [principal-and-interest]<br />
              [purchase-price]<br />
              [service-type]<br />
              [first-time]<br />
              [funding-fee]',
              'group'=>'wpmc_three', ),


            array( 'wpmc_three_disclaimer', 'type'=> $textarea, 'section_name'=>'wpmc-settings-three', 'label'=>__('Disclaimer', 'wpmc'), 'group'=>'wpmc_three','placeholder' => __('If you would like to display a disclaimer under the calculator\'s values, place that here...', 'wpmc'), ),

            array( 'wpmc_three_ftvl', 'type'=> $text, 'section_name'=>'wpmc-settings-three', 'label'=> __('First Time VA Loan?', 'wpmc'), 'group'=>'wpmc_three', ),
            array( 'wpmc_three_tos', 'type'=> $text, 'section_name'=>'wpmc-settings-three', 'label'=> __('Type of Service', 'wpmc'), 'group'=>'wpmc_three', ),
            array( 'wpmc_three_dp', 'type'=> $text, 'section_name'=>'wpmc-settings-three', 'label'=> __('Down Payment', 'wpmc'), 'group'=>'wpmc_three', ),
            array( 'wpmc_three_pp', 'type'=> $text, 'section_name'=>'wpmc-settings-three', 'label'=> __('Purchase Price', 'wpmc'), 'group'=>'wpmc_three', ),
            array( 'wpmc_three_ir', 'type'=> $text, 'section_name'=>'wpmc-settings-three', 'label'=> __('Interest Rate', 'wpmc'), 'group'=>'wpmc_three', ),
            array( 'wpmc_three_mt', 'type'=> $text, 'section_name'=>'wpmc-settings-three', 'label'=> __('Mortgage Term (Year)', 'wpmc'), 'group'=>'wpmc_three', ),
            array( 'wpmc_three_at', 'type'=> $text, 'section_name'=>'wpmc-settings-three', 'label'=> __('Annual Taxes', 'wpmc'), 'group'=>'wpmc_three', ),

            array( 'mcwp_hide_insurance_three', 'type'=> 'mcwp_dropdown', 'section_name'=>'wpmc-settings-three', 'label'=> __('Hide Insurance', 'wpmc'), 'group'=>'wpmc_three', ),
            array( 'wpmc_three_ai', 'type'=> $text, 'section_name'=>'wpmc-settings-three', 'label'=> __('Annual Insurance', 'wpmc'), 'group'=>'wpmc_three', ),

            array( 'mcwp_hide_hoa_three', 'type'=> 'mcwp_dropdown', 'section_name'=>'wpmc-settings-three', 'label'=> __('Hide HOA', 'wpmc'), 'group'=>'wpmc_three', ),
            array( 'wpmc_three_mhoa', 'type'=> $text, 'section_name'=>'wpmc-settings-three', 'label'=> __('Monthly HOA', 'wpmc'), 'group'=>'wpmc_three', ),

            array( 'wpmc_three_pp_initial', 'type'=> $text, 'section_name'=>'wpmc-settings-three', 'label'=> __('Purchase Price', 'wpmc'), 'group'=>'wpmc_three',         'placeholder' => __('Default: 250,000', 'wpmc'), ),
            array( 'wpmc_three_dp_initial', 'type'=> $text, 'section_name'=>'wpmc-settings-three', 'label'=> __('Down Payment %', 'wpmc'), 'group'=>'wpmc_three', 'placeholder' => __('Default: 0', 'wpmc'), ),
            array( 'wpmc_three_ir_initial', 'type'=> $text, 'section_name'=>'wpmc-settings-three', 'label'=> __('Interest Rate %', 'wpmc'), 'group'=>'wpmc_three','placeholder' => __('Default: 5', 'wpmc'), ),
            array( 'wpmc_three_at_initial', 'type'=> $text, 'section_name'=>'wpmc-settings-three', 'label'=> __('Annual Taxes %', 'wpmc'), 'group'=>'wpmc_three','placeholder' => __('Default: 1', 'wpmc'), ),
            array( 'wpmc_three_ai_initial', 'type'=> $text, 'section_name'=>'wpmc-settings-three', 'label'=> __('Annual Insurance', 'wpmc'), 'group'=>'wpmc_three','placeholder' => __('Default: 600', 'wpmc'), ),
            array( 'wpmc_three_mhoa_initial', 'type'=> $text, 'section_name'=>'wpmc-settings-three', 'label'=> __('Monthly HOA', 'wpmc'), 'group'=>'wpmc_three','placeholder' => __('Default: 50', 'wpmc'), ),
        );


        foreach ($allfields as $key => $val) {
            add_settings_field($val[0], __($val['label'], 'wpmc'), $val['type'], $val['section_name'], $val['group'], $val);
            register_setting($val['group'], $val[0]);
        }

        // Calculator Five Options
        // Set Section or Option-Group
        add_settings_section('wpmc_five', '', 'wpmc_five_display_shortcode', 'wpmc-settings-five');

        if (is_multisite() && !is_network_admin()) {
            add_settings_field('wpmc_five_use_network_settings', __('Use Network Settings', 'wpmc'), 'mcwp_checkbox', 'wpmc-settings-five', 'wpmc_five', array('wpmc_five_use_network_settings','wpmc_five'));
            register_setting('wpmc_five', 'wpmc_five_use_network_settings');
        }

        $allfields = array(
            array( 'wpmc_five_email', 'type'=> $text, 'section_name'=>'wpmc-settings-five', 'label'=> __('Email Address', 'wpmc'), 'group'=>'wpmc_five',
                            'placeholder' => __('Email address your leads will be sent to...', 'wpmc'),
                        ),

            array(
              'wpmc_five_msg_bdy',
              'type'=> 'msg_body',
              'section_name'=>'wpmc-settings-five',
              'label'=>'
              '.__('Message Body', 'wpmc').' <br /><br />
              <span style="font-weight: 400">'.__('Available Tags', 'wpmc').': <br />
              [annual-income]<br />
              [cal-result-home-afford]<br />
              [down-payment]<br />
              [estimated-annual-home-insurance]<br />
              [estimated-annual-property-taxes]<br />
              [interest-rate]<br />
              [insurance-value]<br />
              [monthly-payment]<br />
              [principal-interest]<br />
              [tax-value]<br />
              [monthly-debts]<br />
              [mortgage-term]',
              'group'=>'wpmc_five', ),


            array( 'wpmc_five_disclaimer', 'type'=> $textarea, 'section_name'=>'wpmc-settings-five', 'label'=>__('Disclaimer', 'wpmc'), 'group'=>'wpmc_five','placeholder' => __('If you would like to display a disclaimer under the calculator\'s values, place that here...', 'wpmc'), ),

            array( 'wpmc_five_ai', 'type'=> $text, 'section_name'=>'wpmc-settings-five', 'label'=> __('Annual Income', 'wpmc'), 'group'=>'wpmc_five', ),
            array( 'wpmc_five_md', 'type'=> $text, 'section_name'=>'wpmc-settings-five', 'label'=> __('Monthly debts', 'wpmc'), 'group'=>'wpmc_five', ),
            array( 'wpmc_five_eapt', 'type'=> $text, 'section_name'=>'wpmc-settings-five', 'label'=> __('Estimated annual property taxes', 'wpmc'), 'group'=>'wpmc_five', ),
            array( 'wpmc_five_eahi', 'type'=> $text, 'section_name'=>'wpmc-settings-five', 'label'=> __('Estimated annual home insurance', 'wpmc'), 'group'=>'wpmc_five', ),
            array( 'wpmc_five_dp', 'type'=> $text, 'section_name'=>'wpmc-settings-five', 'label'=> __('Down payment', 'wpmc'), 'group'=>'wpmc_five', ),
            array( 'wpmc_five_ir', 'type'=> $text, 'section_name'=>'wpmc-settings-five', 'label'=> __('Interest rate', 'wpmc'), 'group'=>'wpmc_five', ),
            array( 'wpmc_five_lt', 'type'=> $text, 'section_name'=>'wpmc-settings-five', 'label'=> __('Loan term', 'wpmc'), 'group'=>'wpmc_five', ),


            array( 'wpmc_five_mhaai_initial', 'type'=> $text, 'section_name'=>'wpmc-settings-five', 'label'=> __('Annual Income', 'wpmc'), 'group'=>'wpmc_five', 'placeholder' => __('Default: 100,000', 'wpmc'), ),
            array( 'wpmc_five_mhamd_initial', 'type'=> $text, 'section_name'=>'wpmc-settings-five', 'label'=> __('Monthly debts', 'wpmc'), 'group'=>'wpmc_five', 'placeholder' => __('Default: 2,000', 'wpmc'), ),
            array( 'wpmc_five_mhaeapt_initial', 'type'=> $text, 'section_name'=>'wpmc-settings-five', 'label'=> __('Estimated annual property taxes', 'wpmc'), 'group'=>'wpmc_five', 'placeholder' => __('Default: 1,500', 'wpmc'), ),
            array( 'wpmc_five_mhaeahi_initial', 'type'=> $text, 'section_name'=>'wpmc-settings-five', 'label'=> __('Estimated annual home insurance', 'wpmc'), 'group'=>'wpmc_five','placeholder' => __('Default: 1,000', 'wpmc'), ),
            array( 'wpmc_five_mhadp_initial', 'type'=> $text, 'section_name'=>'wpmc-settings-five', 'label'=> __('Down payment', 'wpmc'), 'group'=>'wpmc_five','placeholder' => __('Default: 10,000', 'wpmc'), ),
            array( 'wpmc_five_mhair_initial', 'type'=> $text, 'section_name'=>'wpmc-settings-five', 'label'=> __('Interest rate', 'wpmc'), 'group'=>'wpmc_five','placeholder' => __('Default: 4', 'wpmc'), ),
        );


        foreach ($allfields as $key => $val) {
            add_settings_field($val[0], __($val['label'], 'wpmc'), $val['type'], $val['section_name'], $val['group'], $val);
            register_setting($val['group'], $val[0]);
        }

        // Calculator Six RC Options
        // Set Section or Option-Group
        add_settings_section('wpmc_six', '', 'wpmc_six_display_shortcode', 'wpmc-settings-six');

        if (is_multisite() && !is_network_admin()) {
            add_settings_field('wpmc_six_use_network_settings', __('Use Network Settings', 'wpmc'), 'mcwp_checkbox', 'wpmc-settings-six', 'wpmc_six', array('wpmc_six_use_network_settings','wpmc_six'));
            register_setting('wpmc_six', 'wpmc_six_use_network_settings');
        }

        $allfields = array(
            array( 'wpmc_six_email', 'type'=> $text, 'section_name'=>'wpmc-settings-six', 'label'=> __('Email Address', 'wpmc'), 'group'=>'wpmc_six',
                            'placeholder' => __('Email address your leads will be sent to...', 'wpmc'),
                        ),
            array(
                          'wpmc_six_msg_bdy',
                          'type'=> 'msg_body',
                          'section_name'=>'wpmc-settings-six',
                          'label'=>'
                          '.__('Message Body', 'wpmc').' <br /><br />
                          <span style="font-weight: 400">'.__('Available Tags', 'wpmc').': <br />
						  [cal-result-home-afford]<br />
                          [current-term]<br />
                          [interest-rate]<br />
                          [new-interest-rate]<br />
                          [new-loan-amount]<br />
                          [new-loan-term]<br />
                          [new-monthly-payment]<br />
                          [original-loan-amount]<br />
                          [origination-year]<br />
                          [lifetime-value]<br />
                          [rc-refinance-fees]',
                          'group'=>'wpmc_six', ),

            array( 'wpmc_six_disclaimer', 'type'=> $textarea, 'section_name'=>'wpmc-settings-six', 'label'=>__('Disclaimer', 'wpmc'), 'group'=>'wpmc_six','placeholder' => __('If you would like to display a disclaimer under the calculator\'s values, place that here...', 'wpmc'), ),


            array( 'wpmc_six_first_heading', 'type'=> $text, 'section_name'=>'wpmc-settings-six', 'label'=> __('First Heading', 'wpmc'), 'group'=>'wpmc_six', ),
            array( 'wpmc_six_second_heading', 'type'=> $text, 'section_name'=>'wpmc-settings-six', 'label'=> __('Second Heading', 'wpmc'), 'group'=>'wpmc_six', ),
            array( 'wpmc_six_la', 'type'=> $text, 'section_name'=>'wpmc-settings-six', 'label'=> __('Original loan amount', 'wpmc'), 'group'=>'wpmc_six', ),
            array( 'wpmc_six_ir', 'type'=> $text, 'section_name'=>'wpmc-settings-six', 'label'=> __('Interest rate', 'wpmc'), 'group'=>'wpmc_six', ),
            array( 'wpmc_six_ct', 'type'=> $text, 'section_name'=>'wpmc-settings-six', 'label'=> __('Current term', 'wpmc'), 'group'=>'wpmc_six', ),
            array( 'wpmc_six_oy', 'type'=> $text, 'section_name'=>'wpmc-settings-six', 'label'=> __('Origination year', 'wpmc'), 'group'=>'wpmc_six', ),
            array( 'wpmc_six_nla', 'type'=> $text, 'section_name'=>'wpmc-settings-six', 'label'=> __('New loan amount', 'wpmc'), 'group'=>'wpmc_six', ),
            array( 'wpmc_six_nir', 'type'=> $text, 'section_name'=>'wpmc-settings-six', 'label'=> __('New interest rate', 'wpmc'), 'group'=>'wpmc_six', ),
            array( 'wpmc_six_nlt', 'type'=> $text, 'section_name'=>'wpmc-settings-six', 'label'=> __('New loan term', 'wpmc'), 'group'=>'wpmc_six', ),
            array( 'wpmc_six_nrf', 'type'=> $text, 'section_name'=>'wpmc-settings-six', 'label'=> __('New refinance fees', 'wpmc'), 'group'=>'wpmc_six', ),

            array( 'wpmc_six_la_initial', 'type'=> $text, 'section_name'=>'wpmc-settings-six', 'label'=> __('Original loan amount', 'wpmc'), 'group'=>'wpmc_six', 'placeholder' => __('Default: 300,000', 'wpmc'), ),
            array( 'wpmc_six_ir_initial', 'type'=> $text, 'section_name'=>'wpmc-settings-six', 'label'=> __('Interest rate', 'wpmc'), 'group'=>'wpmc_six', 'placeholder' => __('Default: 5', 'wpmc'), ),
            array( 'wpmc_six_ct_initial', 'type'=> $text, 'section_name'=>'wpmc-settings-six', 'label'=> __('Current term', 'wpmc'), 'group'=>'wpmc_six','placeholder' => __('Default: 360', 'wpmc'), ),
            array( 'wpmc_six_oy_initial', 'type'=> $text, 'section_name'=>'wpmc-settings-six', 'label'=>__('Origination year', 'wpmc'), 'group'=>'wpmc_six','placeholder' => __('Default: 2020', 'wpmc'), ),
            array( 'wpmc_six_nla_initial', 'type'=> $text, 'section_name'=>'wpmc-settings-six', 'label'=> __('New loan amount', 'wpmc'), 'group'=>'wpmc_six','placeholder' => __('Default: 250,000', 'wpmc'), ),
            array( 'wpmc_six_nir_initial', 'type'=> $text, 'section_name'=>'wpmc-settings-six', 'label'=> __('New interest rate', 'wpmc'), 'group'=>'wpmc_six','placeholder' => __('Default: 4', 'wpmc'), ),
            array( 'wpmc_six_nlt_initial', 'type'=> $text, 'section_name'=>'wpmc-settings-six', 'label'=> __('New loan term', 'wpmc'), 'group'=>'wpmc_six','placeholder' => __('Default: 360', 'wpmc'), ),
            array( 'wpmc_six_nrf_initial', 'type'=> $text, 'section_name'=>'wpmc-settings-six', 'label'=> __('New refinance fees', 'wpmc'), 'group'=>'wpmc_six','placeholder' => __('Default: 1,000', 'wpmc'), ),
        );


        foreach ($allfields as $key => $val) {
            add_settings_field($val[0], __($val['label'], 'wpmc'), $val['type'], $val['section_name'], $val['group'], $val);
            register_setting($val['group'], $val[0]);
        }
    }
    function wpmc_mail_display_shortcode()
    {
        /*echo '<p style="background: #fff; border-left: 4px solid #008ec2; padding: 5px 10px;">Use this shortcode for administration email: <strong>[email]</strong></p>';
        */
        echo '';
    }
    function copyShortText($text)
    {
        echo('<p style="background: #fff; border-left: 4px solid #008ec2; padding: 5px 10px;">
            '.__('Copy this <strong>shortcode</strong> and paste it into your <strong>post, page, or text widget</strong> content: ', 'wpmc').'
            <strong>[mcwp type="'.esc_html($text).'"]</strong></p>
        ');
    }
    // Calculator One Options
    function wpmc_one_display_shortcode()
    {
        copyShortText('cv');
    }
    // Calculator Two Options
    function wpmc_two_display_shortcode()
    {
        copyShortText('fha');
    }
    // Calculator Three Options
    function wpmc_three_display_shortcode()
    {
        copyShortText('va');
    }
    // Calculator Five Options
    function wpmc_five_display_shortcode()
    {
        copyShortText('mha');
    }
    // Calculator Six Options
    function wpmc_six_display_shortcode()
    {
        copyShortText('rc');
    }

    function mcwp_checkbox($args)
    {
        $options = get_wpmc_option($args[0]);
        $val = ($options == '0') ? '0' : '1';
        $main_val = ($val == '0') ? '0' : '1';
        $checked = ($val === '0') ? 'checked' : ''; ?>

		    <input type="checkbox" name="<?php echo esc_attr($args[0]); ?>" class="<?php echo esc_attr($args[1]); ?>"
         value="<?php echo esc_attr($main_val); ?>"  size="64" <?php echo esc_attr($checked); ?> />

	<?php
    }
    function color_input($args)
    {
        $options = get_wpmc_option($args[0]); ?>
		<input type="text" name="<?php echo esc_attr($args[0]); ?>" class="<?php echo esc_attr($args['group']); ?> color-picker" placeholder="<?php echo esc_attr($args['placeholder']); ?>" value="<?php  echo !empty($options) ? esc_attr($options) : esc_attr('#bada55'); ?>" id="<?php echo esc_attr($args[0]); ?>" size="64" />
	<?php
    }
    function display_text_element($args)
    {
        //var_dump($args);
        $options = get_wpmc_option($args[0]); ?>

		<input type="text" name="<?php echo esc_attr($args[0]); ?>" id="<?php echo esc_attr($args[0]); ?>" class="<?php echo esc_attr($args['group']); ?>" placeholder="<?php echo empty($args['placeholder']) ? esc_attr($args['label']) : esc_attr($args['placeholder']); ?>" value="<?php echo isset($options) ? esc_attr($options) : ''; ?>" size="64" />
	<?php
    }


    function mcwp_dropdown($args)
    {
        $options = get_wpmc_option($args[0]); ?>
        <select name="<?php echo esc_attr($args[0]); ?>" class="<?php echo esc_attr($args['group']); ?>">
        	<option value="no" <?php echo $options == 'no' ? 'selected' : ''; ?>><?php echo __('No', 'wpmc'); ?></option>
        	<option value="yes" <?php echo $options == 'yes' ? 'selected' : ''; ?>><?php echo __('Yes', 'wpmc'); ?></option>
        </select>

	<?php
    }
    function mcwp_textarea($args)
    {
        $options = get_wpmc_option($args[0]); ?>
		<textarea name="<?php echo esc_attr($args[0]); ?>" class="<?php echo esc_attr($args['group']); ?>" placeholder="<?php echo esc_attr($args['placeholder']); ?>" rows="5" cols="65" size="64" ><?php echo isset($options) ? esc_attr($options) : ''; ?></textarea>
	<?php
    }


    function mcwp_currency($args)
    {
        $options = get_wpmc_option($args[0]);

        $currencies = array(
            '$' => __('United States Dollar', 'wpmc'),
            '£' => __('United Kingdom Pounds', 'wpmc'),
            '€' => __('Euro', 'wpmc'),
            'A$' => __('Australia Dollar', 'wpmc'),
        ); ?>
        <select name="<?php echo esc_attr($args[0]); ?>" class="<?php echo esc_attr($args['group']); ?>">
        	<?php
                foreach ($currencies as $key=>$val) {
                    $selected = '';
                    if ($key ===  $options) {
                        $selected = ' selected="selected"';
                    }
                    echo '<option value="'.esc_attr($key).'" '.esc_attr($selected).'>'.esc_html($val).'</option>';
                } ?>
        </select>
	<?php
    }
    function msg_body($args)
    {
        $options = get_wpmc_option($args[0]);
        switch ($args['group']) {
        case
            "wpmc_one": // Conventional
            $msg_body = __('Based on a purchase price of', 'wpmc').' <strong>$[purchase-price]</strong>, '.__('and a down payment of', 'wpmc').' <strong>$[down-payment]</strong>, '.__('your new', 'wpmc').' <strong>[mortgage-term] '.__('year', 'wpmc').'</strong> '.__('loan with an interest rate of', 'wpmc').' <strong>[interest-rate]%</strong> '.__('will have a payment of', 'wpmc').' <strong>$[calculation-result]</strong>. '.__('This includes monthly taxes of', 'wpmc').' <strong>$[monthly-taxes]</strong>, '.__('monthly insurance of', 'wpmc').' <strong>$[monthly-insurance]</strong>, '.__('and monthly hoa of', 'wpmc').' <strong>$[monthly-hoa]</strong>.';
            break;
        case
            "wpmc_two": // FHA
            $msg_body = __('Based on a purchase price of', 'wpmc').' <strong>$[purchase-price]</strong>, '.__('and a down payment of', 'wpmc').' <strong>$[down-payment]</strong>, '.__('your new', 'wpmc').' <strong>[mortgage-term] '.__('year', 'wpmc').'</strong> '.__('FHA loan with an interest rate of', 'wpmc').' <strong>[interest-rate]%</strong> '.__('will have a payment of', 'wpmc').' <strong>$[calculation-result]</strong>. '.__('This includes monthly taxes of', 'wpmc').' <strong>$[monthly-taxes]</strong>, '.__('monthly insurance of', 'wpmc').' <strong>$[monthly-insurance]</strong>, '.__('and monthly hoa of', 'wpmc').' <strong>$[monthly-hoa]</strong>.';
            break;
        case
             "wpmc_three": // VA

             $msg_body = __('Based on a purchase price of', 'wpmc').' <strong>$[purchase-price]</strong>, '.__('your new', 'wpmc').' <strong>[mortgage-term] '.__('year', 'wpmc').'</strong> '.__('VA loan in the amount of', 'wpmc').' <strong>$[amount-financed]</strong>, '.__('which includes a funding fee of', 'wpmc').' <strong>$[funding-fee]</strong>, '.__(' with an interest rate of', 'wpmc').' <strong>[interest-rate]%</strong> '.__('will have a payment of', 'wpmc').' <strong>$[calculation-result]</strong>. '.__('This includes monthly taxes of', 'wpmc').' <strong>$[monthly-taxes]</strong>, '.__('monthly insurance of', 'wpmc').' <strong>$[monthly-insurance]</strong>, '.__('and monthly hoa of', 'wpmc').' <strong>$[monthly-hoa]</strong>.';
             break;
        case
             "wpmc_five": // Affordability
             $msg_body = __('You may be able to afford a loan with a', 'wpmc').' <strong>[mortgage-term] '.__('year term', 'wpmc').'</strong> '.__('in the amount of', 'wpmc').' <strong>$[cal-result-home-afford]</strong> '.__('at', 'wpmc').' <strong>[interest-rate]%</strong> '.__('that has a total monthly payment of', 'wpmc').' <strong>$[monthly-payment]</strong>'.__('. This is based on your annual income of', 'wpmc').' <strong>$[annual-income]</strong> '.__('and monthly debts of', 'wpmc').' <strong>$[monthly-debts]</strong>.';

             break;

        case
             "wpmc_six": // Refinance
             $msg_body = __('Refinancing could save you', 'wpmc').' <strong>$[cal-result-home-afford]</strong> '.__('per month and', 'wpmc').' <strong>$[lifetime-value]</strong> '.__('over the life of the loan. This is based on a new loan amount of', 'wpmc').' <strong>$[new-loan-amount]</strong> '.__('at', 'wpmc').' <strong>[new-interest-rate]%</strong> '.__('for', 'wpmc').' <strong>[new-loan-term]</strong> '.__('months.', 'wpmc');

             break;
        } ?>
			<textarea name="<?php echo esc_attr($args[0]); ?>" class="<?php echo esc_attr($args['group']); ?>" rows="12" cols="65" ><?php echo !empty($options) ? esc_textarea($options) : esc_textarea($msg_body); ?></textarea>

	<?php
    }
