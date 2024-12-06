<?php
// Prevent direct access to the file.
defined('MOODLE_INTERNAL') || die();

// Define the component name for this plugin.
$plugin->component = 'block_test_course'; // Unique identifier for the plugin, matching the block directory name.

// Define the plugin version.
// Format: YYYYMMDDXX, where YYYYMMDD is the date and XX is an incremental counter for updates.
$plugin->version = 2024120600;

// Specify the minimum Moodle version required for the plugin to work.
// The version number corresponds to Moodle 4.1's release.
$plugin->requires = 2022112800;

// Set the plugin's maturity level.
// Possible values: MATURITY_ALPHA, MATURITY_BETA, MATURITY_RC, MATURITY_STABLE.
$plugin->maturity = MATURITY_STABLE;

// Define the plugin's release version as a human-readable string.
$plugin->release = '1.0'; // Indicates the initial release version of the plugin.
