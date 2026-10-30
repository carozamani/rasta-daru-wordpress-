<?php

if (!function_exists('irantheme_init_smtp')) {
    function irantheme_init_smtp($phpmailer)
    {
        // Get smtp fields from settings options
        $smtp_host           = get_option('irantheme_smtp_set_host', '');
        $smtp_secure         = get_option('irantheme_smtp_set_secure', '');
        $smtp_port           = get_option('irantheme_smtp_set_port', 587);
        $smtp_auth_checkbox  = get_option('irantheme_smtp_set_auth', 'off');
        $smtp_heading        = get_option('irantheme_smtp_set_heading', '');
        $smtp_username       = get_option('irantheme_smtp_set_username', '');
        $smtp_password       = get_option('irantheme_smtp_set_password', '');
        $smtp_sender         = get_option('irantheme_smtp_set_sender', '');

        // Check if smtp_host is empty, if so, return early
        if (empty($smtp_host)) {
            return;
        }

        // Validate smtp_secure value
        if (!in_array($smtp_secure, array('ssl', 'tls', ''), true)) {
            $smtp_secure = '';
        }

        // auth status
        $smtp_auth = ($smtp_auth_checkbox === 'on');

        // Set php mailer smtp
        $phpmailer->isSMTP();
        $phpmailer->Host       = $smtp_host;
        $phpmailer->SMTPSecure = $smtp_secure;
        $phpmailer->Port       = absint($smtp_port);
        $phpmailer->SMTPAuth   = $smtp_auth;
        $phpmailer->Username   = $smtp_username;
        $phpmailer->Password   = $smtp_password;

        // From و FromName
        if (!empty($smtp_sender) && is_email($smtp_sender)) {
            $phpmailer->setFrom($smtp_sender, sanitize_text_field($smtp_heading));
        }
    }
    add_action('phpmailer_init', 'irantheme_init_smtp');
}


if (!function_exists('irantheme_set_sender_mail')) {
    function irantheme_set_sender_mail($email)
    {
        $smtp_sender = get_option('irantheme_smtp_set_sender', '');
        if (!empty($smtp_sender) && is_email($smtp_sender)) {
            return $smtp_sender;
        }
        return $email; // Return the original email if no custom sender is set
    }
    add_filter('wp_mail_from', 'irantheme_set_sender_mail');
}


if (!function_exists('irantheme_set_sender_name')) {
    function irantheme_set_sender_name($name)
    {
        $smtp_heading = get_option('irantheme_smtp_set_heading', '');
        if (!empty($smtp_heading)) {
            return sanitize_text_field($smtp_heading);
        }
        return $name;
    }
    add_filter('wp_mail_from_name', 'irantheme_set_sender_name');
}
