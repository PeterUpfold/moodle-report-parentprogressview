<?php

/**
 * Set up capabilities for this report plugin.
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */

defined('MOODLE_INTERNAL') || die();


$capabilities = array(
	'report/parentprogressview:view' => array(
		'riskbitmask'            => RISK_PERSONAL,
		'captype'                => 'read',
		'contextlevel'           => CONTEXT_MODULE,
		'archetypes'             => array(
			'parent'           => CAP_ALLOW,
			'editingteacher'   => CAP_ALLOW,
		),
	)
);
