<?php
/*
Plugin Name: Send Teachable Request
Description: A plugin to create user via Teachable API and store form data in MySQL database.
Version: 1.4
Author: Aditya Gaikwad
*/

// Create plugin table upon activation
function send_teachable_request_activate() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'teachable_users';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        fullname varchar(100) NOT NULL,
        email varchar(100) NOT NULL,
        password varchar(100) NOT NULL,
        mobile varchar(20),
        address varchar(255),
        profession varchar(50),
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'send_teachable_request_activate');

// Process form submission
function send_teachable_request() {
    global $wpdb;

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send'])) {
        // Sanitize and validate form data
        $fullname = sanitize_text_field($_POST['fullname']);
        $email = sanitize_email($_POST['email']);
        $password = sanitize_text_field($_POST['password']);
        $mobile = sanitize_text_field($_POST['mobile']);
        $address = sanitize_text_field($_POST['address']);
        $profession = sanitize_text_field($_POST['profession']);

        // Validate required fields
        if (empty($fullname) || empty($email) || empty($password)) {
            echo '<p>Please fill in all required fields.</p>';
            return;
        }

        // Store form data in database
        $table_name = $wpdb->prefix . 'teachable_users';
        $wpdb->insert(
            $table_name,
            array(
                'fullname' => $fullname,
                'email' => $email,
                'password' => $password,
                'mobile' => $mobile,
                'address' => $address,
                'profession' => $profession,
            )
        );

        // Send simplified request to Teachable API
        $url = 'https://developers.teachable.com/v1/users';
        $body = json_encode([
            'name' => $fullname,
            'email' => $email,
            'password' => $password
        ]);

        $response = wp_remote_post($url, [
            'body' => $body,
            'headers' => [
                'Accept' => 'application/json',
                'apiKey' => 'ZKfQc5TH3gFREory8EXT8ttdwCrR41Tk',
                'Content-Type' => 'application/json',
            ],
        ]);

        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            echo "<p>Request failed: $error_message</p>";
        } else {
            $response_code = wp_remote_retrieve_response_code($response);
            if ($response_code == 201) {
                echo "<p>User created successfully via Teachable API.</p>";
            } else {
                echo "<p>Request failed with status code $response_code.</p>";
            }
        }
    }
}

add_action('init', 'send_teachable_request');

// Shortcode to display the form
function add_send_button_shortcode() {
    ob_start(); ?>

    <form method="POST">
        <label for="fullname">Full Name:</label>
        <input type="text" id="fullname" name="fullname" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="mobile">Mobile:</label>
        <input type="text" id="mobile" name="mobile"><br><br>

        <label for="address">Address:</label>
        <textarea id="address" name="address"></textarea><br><br>

        <label for="profession">Profession:</label>
        <select id="profession" name="profession">
            <option value="Self learner">Self learner</option>
            <option value="Professional User">Professional User</option>
            <option value="Employee">Employee</option>
            <option value="Partner">Partner</option>
            <option value="Customer">Customer</option>


        </select><br><br>

        <button type="submit" name="send">SEND</button>
    </form>

    <?php
    return ob_get_clean();
}
add_shortcode('send_button', 'add_send_button_shortcode');
?>
