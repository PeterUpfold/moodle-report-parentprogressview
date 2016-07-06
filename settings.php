<?php

/**
 * Settings for the report plugin
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */

 defined('MOODLE_INTERNAL') || die();

 $ADMIN->add(
 	'reports',
	new admin_externalpage(
		'reportparentprogressview',
		get_string('pluginname', 'report_parentprogressview'),
		"{$CFG->wwwroot}/report/parentprogressview/index.php", 'report/parentprogressview:view'
	)
);

 $settings = null;

