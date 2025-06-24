# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Changed
- Bumped the minimum required WordPress version to 5.5.
- Bumped the minimum required PHP version to 7.2.

### Fixed
- Fixed the Secure Login implementation to remove empty IP entries after the "Reset Entries" time is reached. https://github.com/supervisorwp/supervisor/pull/42

## [1.3.1] - 2024-07-15
### Fixed
- Disabled the autoloading of the login attempts log option for performance reasons. https://github.com/supervisorwp/supervisor/pull/38

## [1.3.0] - 2023-11-14
### Added
- New Feature: Brute Force Protection. https://github.com/supervisorwp/supervisor/pull/23

### Changed
- Formats the numbers to always include 2 decimals. https://github.com/supervisorwp/supervisor/pull/29
- Updated some buttons to include a CSS loading spinner on AJAX requests. https://github.com/supervisorwp/supervisor/pull/35

### Fixed
- Renamed the page title from Dashboard to Supervisor. https://github.com/supervisorwp/supervisor/pull/28
- Fixed the condition to include the correct WP version array index. https://github.com/supervisorwp/supervisor/pull/27

## [1.2.0] - 2023-03-01
### Added
- Added feature to manage WordPress auto updates policy. https://github.com/supervisorwp/supervisor/pull/19
- Implemented some filters so developers can customize the plugin data. https://github.com/supervisorwp/supervisor/pull/21
- Cleans up the plugin transients after WP core or plugin updates. https://github.com/supervisorwp/supervisor/pull/18

## [1.1.0] - 2023-02-22
### Added
- Implemented a check to confirm if the server softwares are updated. https://github.com/supervisorwp/supervisor/pull/7
- Added a border to the top of the header on Supervisor screen. https://github.com/supervisorwp/supervisor/issues/15
- Hides notices on Supervisor screen. https://github.com/supervisorwp/supervisor/pull/14

## [1.0.0] - 2023-02-22
- Initial release.
