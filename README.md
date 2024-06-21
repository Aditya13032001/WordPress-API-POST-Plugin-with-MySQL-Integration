# Send API Request WordPress Plugin

## Description

The **Send API Request** plugin for WordPress is designed to facilitate user creation via an external API and store form data directly in your WordPress MySQL database. This plugin is perfect for integrating external user management systems like Teachable with your WordPress site, ensuring seamless data handling and storage.

## Features

- **Create Users via External API**: Automate user creation by sending POST requests to your specified API.
- **Secure Data Storage**: Safely store user data in the WordPress database with sanitization and validation.
- **Customizable Registration Form**: Display a user-friendly registration form on your site using a simple shortcode.
- **No CORS Errors**: Handle API requests server-side to avoid cross-origin resource sharing (CORS) issues.

## Installation

1. **Upload Plugin**:

   - Upload the plugin files to the `/wp-content/plugins/send-api-request` directory, or install the plugin through the WordPress plugins screen directly.

2. **Activate Plugin**:

   - Activate the plugin through the 'Plugins' screen in WordPress.

3. **Create Database Table**:
   - Upon activation, the plugin will automatically create a custom table in your WordPress database to store user data.

## Usage

1. **Configure API Settings**:

   - Open the `send_teachable_request_activate` function in the plugin code.
   - Replace `'Enter_db_name'` with the name of your custom database table.
   - Replace `'Enter_api_url'` with the URL of the API endpoint.
   - Replace `'Enter Api_Key'` with your API key.

2. **Add Shortcode to Page**:

   - Use the `[send_button]` shortcode to display the user registration form on any page or post.
   - Example:
     ```plaintext
     [send_button]
     ```

3. **Handle Form Submissions**:
   - Users can fill out the form, and their data will be submitted to the external API and stored in the WordPress database.
   - The plugin will display success or error messages based on the API response.

## Shortcode

Use the following shortcode to display the form on any page or post:

```plaintext
[send_button]
```
