=== Supervisor ===
Contributors: tiagohillebrandt
Donate link: https://www.paypal.com/donate/?hosted_button_id=45R6Q8J8JERVS
Tags: performance, transients, autoload, healthcheck, load time, ssl, https, check, site performance
Requires at least: 5.5
Tested up to: 6.8.3
Requires PHP: 7.2
Stable tag: 1.3.3
License: GPLv3+
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Supervisor is a powerful plugin designed to enhance both the performance and security of your WordPress installation.

== Description ==

**Optimize and Secure Your WordPress Site with Supervisor**

Boost the performance and security of your WordPress site effortlessly with our powerful plugin. Supervisor provides vital insights into your site's health directly through your WordPress Dashboard.

**Key Features:**

- **Performance Optimization:** Improve your site's speed by cleaning up transients and deactivating unnecessary autoload options.
- **Brute Force Protection:** Shield your site from attacks with robust security measures designed to prevent unauthorized access.
- **Web Server Software Verification:** Ensure your server software is up-to-date, keeping your site running smoothly and securely.
- **SSL Certificate Monitoring:** Stay informed about your SSL certificate status with dashboard notifications for impending expirations or expired certificates.

Experience a faster and more secure WordPress site with Supervisor. Download it today and feel the difference!

== Frequently Asked Questions ==

= Where can I get support and talk to other users? =

For questions and community interaction, you can start a new thread in our [Community Forum](https://wordpress.org/support/plugin/supervisor/) on WordPress.org.

We review the forum weekly, and our team is ready to assist you.

For Premium Support, please contact us at **tiago AT tiagohillebrandt DOT com**.

= Where can I report bugs? =

Report any bugs on our [issues](https://github.com/supervisorwp/supervisor/issues) page on GitHub.

If you're unsure whether something is a bug, feel free to seek guidance in our [Community Forum](https://wordpress.org/support/plugin/supervisor).

= How can I translate Supervisor into my language? =

You can help translate Supervisor into your language using the [WordPress translations platform](https://translate.wordpress.org/projects/wp-plugins/supervisor/stable/).

= How can I contribute to the project? =

If you're a developer and would like to contribute by adding new features, enhancements, bug fixes, or tests, please submit your Pull Requests to our [GitHub repository](https://github.com/supervisorwp/supervisor).

== Changelog ==

= [1.3.3] - 2025-10-23 =
- Security fix: Added a capability check to prevent unauthorized AJAX requests.
- Fixed an incorrect constant name that could cause a PHP fatal error.

= [1.3.2] - 2025-06-24 =
- Fixed: Fixed the Secure Login implementation to remove empty IP entries after the "Reset Entries" time is reached.
- Changed: Added support for the new autoload values introduced in WordPress 6.6.
- Changed: Bumped the minimum required WordPress version to 5.5.
- Changed: Bumped the minimum required PHP version to 7.2.
- Changed: Bumped the tested up to WordPress version to 6.8.1.

= [1.3.1] - 2024-07-15 =
* Changed: Bumped the tested WP version up to 6.5.5.
* Fixed: Disabled the autoloading of the login attempts log option for performance reasons.

= [1.3.0] - 2023-11-14 =
* Added: Implemented Brute Force Protection.
* Changed: Formatted the numbers to always include 2 decimals.
* Changed: Updated some buttons to include a CSS loading spinner on AJAX requests.
* Changed: Bumped minimum PHP version to 7.0.
* Changed: Bumped minimum WordPress version to 5.0.
* Fixed: Renamed the page title from Dashboard to Supervisor.
* Fixed: Fixed the condition to include the correct WP version array index.

= [1.2.0] - 2023-03-01 =
* Added: Ability to manage WordPress auto updates policy.
* Added: Implemented some filters so developers can customize the plugin data.
* Added: Cleaned up the plugin transients after WP core or plugin updates.
* Changed: Minor improvements to the UI.

= [1.1.0] - 2023-02-22 =
* Added: Implemented a check to confirm whether the server software is updated or not.
* Changed: Added a border to the top of the header on the Supervisor screen.
* Changed: Hides notices on Supervisor screen.

= [1.0.0] - 2023-02-22 =
* Initial release.
