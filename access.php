<?php
defined('MOODLE_INTERNAL') || die();

$capabilities = array(
    'block/test_course:myaddinstance' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(
            'user' => CAP_ALLOW,
        ),
    ),

    'block/test_course:addinstance' => array(
        'riskbitmask' => RISK_SPAM | RISK_XSS,
        'captype' => 'write',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW,
        ),
        'clonepermissionsfrom' => 'moodle/site:manageblocks',
    ),
);
