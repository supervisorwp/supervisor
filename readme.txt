=== Supervisor ===
Contributors: tiagohillebrandt
Tags: performance, transients, autoload, healthcheck, load time, ssl, https, check, site performance
Requires at least: 5.0
Tested up to: 6.5.5
Requires PHP: 7.0
Stable tag: 1.3.1
License: GPLv3+
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Supervisor is a plugin to help improve the performance and security of your WordPress install.

== Description ==

[Supervisor](https://supervisorwp.com) is a plugin to help improve the performance and security of your WordPress install.

It detects some useful information about your site's health and conveniently displays it through the WordPress Dashboard.

This plugin can assist you in optimizing your site's performance by cleaning up transients and deactivating autoload options.

Additionally, it provides Brute Force Protection to safeguard your site from attacks attempting to collect user information.

Supervisor also verifies the software versions in use on your server.

Furthermore, it checks your SSL certificate expiration date and notifies you via the Dashboard when it is near expiration or has expired.

== Frequently Asked Questions ==

= Where can I get support and talk to other users? =

If you have any questions, you can post a new thread in our [Community Forum](https://wordpress.org/support/plugin/supervisor), available on WordPress.org.

We review it weekly and our team will be happy to assist you there.

For Premium Support, you can contact us through the email tiago AT tiagohillebrandt DOT com.

= Where can I report any bugs? =

Please report any bugs to our [issues](https://github.com/supervisorwp/supervisor/issues) page.

If you are not sure if something is a bug or not, you can always ask for guidance in our [Community Forum](https://wordpress.org/support/plugin/supervisor).

= How can I translate Supervisor to my language? =

You can translate it to your language through the [WordPress translations platform](https://translate.wordpress.org).

= How can I contribute to the project? =

If you are a developer and want to contribute by writing new features, enhancements, bug fixes, or tests, please send your Pull Requests to our [GitHub repository](https://github.com/supervisorwp/supervisor).

== Changelog ==

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
