<?php
// Prevent direct access to the file.
defined('MOODLE_INTERNAL') || die();

// Define the capabilities for the block plugin.
$capabilities = array(

    // Capability to allow users to add this block to their Dashboard (My Page).
    'block/test_course:myaddinstance' => array(
        'captype' => 'write', // This is a write capability.
        'contextlevel' => CONTEXT_BLOCK, // The capability applies at the block context level.
        'archetypes' => array(
            'user' => CAP_ALLOW, // Allow regular users to add the block to their personal dashboard.
        ),
    ),

    // Capability to allow users to add this block to a course page.
    'block/test_course:addinstance' => array(
        'riskbitmask' => RISK_SPAM | RISK_XSS, // Indicate potential risks (SPAM, XSS) when adding content to blocks.
        'captype' => 'write', // This is a write capability.
        'contextlevel' => CONTEXT_BLOCK, // The capability applies at the block context level.
        'archetypes' => array(
            'editingteacher' => CAP_ALLOW, // Allow editing teachers to add the block to a course.
            'manager' => CAP_ALLOW, // Allow managers to add the block to a course.
        ),
        // Clone permissions from the site-wide capability for managing blocks.
        'clonepermissionsfrom' => 'moodle/site:manageblocks',
    ),
);
