<?php
/*
Parent Progress View, a module for Moodle to allow the viewing of documents and pupil data by authorised parents.
    Copyright (C) 2016-19 Test Valley School.

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License,
    or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


/**
 * Settings for the report plugin
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */

defined('MOODLE_INTERNAL') || die();

$ADMIN->add(
	'reports',
	new admin_category(
		'report_parentprogressview',
		get_string('pluginname', 'report_parentprogressview')
	)
);

$ADMIN->add(
 	'report_parentprogressview',
	new admin_externalpage(
		'report_parentprogressview_main',
		get_string('documentsfeaturename', 'report_parentprogressview'),
		"{$CFG->wwwroot}/report/parentprogressview/index.php",
		'report/parentprogressview:view'
	)
);

$ADMIN->add(
 	'report_parentprogressview',
	new admin_externalpage(
		'report_parentprogressview_attendance',
		get_string('attendancefeaturename', 'report_parentprogressview'),
		"{$CFG->wwwroot}/report/parentprogressview/attendance.php",
		'report/parentprogressview:view'
	)
);

$ADMIN->add(
 	'report_parentprogressview',
	new admin_externalpage(
		'report_parentprogressview_achievements',
		get_string('achievementfeaturename', 'report_parentprogressview'),
		"{$CFG->wwwroot}/report/parentprogressview/achievements.php",
		'report/parentprogressview:view'
	)
);
$ADMIN->add(
 	'report_parentprogressview',
	new admin_externalpage(
		'report_parentprogressview_behaviour',
		get_string('behaviourfeaturename', 'report_parentprogressview'),
		"{$CFG->wwwroot}/report/parentprogressview/behaviour.php",
		'report/parentprogressview:view'
	)
);

$ADMIN->add(
	'report_parentprogressview',
	new admin_externalpage(
		'report_parentprogressview_timetables',
		get_string('timetablesfeaturename', 'report_parentprogressview'),
		"{$CFG->wwwroot}/report/parentprogressview/timetables.php",
		'report/parentprogressview:view'
	)
);

/*** Define which role is parent role ***/

// prepare list of roles and role IDs
$roles = array();
$all_roles = role_get_names(); // accesslib.php
foreach($all_roles as $role) {
	$roles[$role->id] = $role->localname;
}

$settings->add(new admin_setting_configselect(
	'report_parentprogressview_parent_roleid',
	get_string('config_parent_roleid', 'report_parentprogressview'),
	get_string('config_parent_roleid_desc', 'report_parentprogressview'),
	'', 
	$roles	
));

/*** Database information for document access ***/
$settings->add(new admin_setting_heading(
	'report_parentprogressview_database_header',
	get_string('config_database_header', 'report_parentprogressview'),
	get_string('config_database_information', 'report_parentprogressview')
));

$settings->add(new admin_setting_configtext_with_maxlength(
	'report_parentprogressview_documents_dbuser',
	get_string('config_documents_dbuser', 'report_parentprogressview'),
	get_string('config_documents_dbuser_desc', 'report_parentprogressview'),
	'',
	PARAM_NOTAGS,
	null,
	16
));

$settings->add(new admin_setting_configpasswordunmask(
	'report_parentprogressview_documents_dbpass',
	get_string('config_documents_dbpass', 'report_parentprogressview'),
	get_string('config_documents_dbpass_desc', 'report_parentprogressview'),
	''
));

$settings->add(new admin_setting_configtext(
	'report_parentprogressview_documents_dbhost',
	get_string('config_documents_dbhost', 'report_parentprogressview'),
	get_string('config_documents_dbhost_desc', 'report_parentprogressview'),
	'',
	PARAM_NOTAGS,
	null
));

$settings->add(new admin_setting_configtext(
	'report_parentprogressview_documents_dbname',
	get_string('config_documents_dbname', 'report_parentprogressview'),
	get_string('config_documents_dbname_desc', 'report_parentprogressview'),
	'',
	PARAM_NOTAGS,
	null
));

$settings->add(new admin_setting_configtext(
	'report_parentprogressview_documents_dbtable',
	get_string('config_documents_dbtable', 'report_parentprogressview'),
	get_string('config_documents_dbtable_desc', 'report_parentprogressview'),
	'',
	PARAM_NOTAGS,
	null
));

/*** Achievements API settings ***/
$settings->add(new admin_setting_heading(
	'report_parentprogressview_achievements_api_header',
	get_string('config_achievements_api_header', 'report_parentprogressview'),
	get_string('config_achievements_api_information', 'report_parentprogressview')
));

$settings->add(new admin_setting_configtext(
	'report_parentprogressview_achievements_api_base',
	get_string('config_achievements_api_base', 'report_parentprogressview'),
	get_string('config_achievements_api_base_desc', 'report_parentprogressview'),
	'',
	PARAM_NOTAGS,
	null
));

$settings->add(new admin_setting_configtext(
	'report_parentprogressview_achievements_api_namespace',
	get_string('config_achievements_api_namespace', 'report_parentprogressview'),
	get_string('config_achievements_api_namespace_desc', 'report_parentprogressview'),
	'',
	PARAM_NOTAGS,
	null
));

$settings->add(new admin_setting_configtext(
	'report_parentprogressview_achievements_api_route',
	get_string('config_achievements_api_route', 'report_parentprogressview'),
	get_string('config_achievements_api_route_desc', 'report_parentprogressview'),
	'',
	PARAM_NOTAGS,
	null
));

$settings->add(new admin_setting_configtext(
	'report_parentprogressview_achievements_api_user',
	get_string('config_achievements_api_user', 'report_parentprogressview'),
	get_string('config_achievements_api_user_desc', 'report_parentprogressview'),
	'',
	PARAM_NOTAGS,
	null
));

$settings->add(new admin_setting_configpasswordunmask(
	'report_parentprogressview_achievements_api_pass',
	get_string('config_achievements_api_pass', 'report_parentprogressview'),
	get_string('config_achievements_api_pass_desc', 'report_parentprogressview'),
	''
));

$settings->add(new admin_setting_configtext(
	'report_parentprogressview_achievement_totals_api_route',
	get_string('config_achievement_totals_api_route', 'report_parentprogressview'),
	get_string('config_achievement_totals_api_user_desc', 'report_parentprogressview'),
	'',
	PARAM_NOTAGS,
	null
));


/*** Behaviour API settings ***/
$settings->add(new admin_setting_heading(
	'report_parentprogressview_behaviour_api_header',
	get_string('config_behaviour_api_header', 'report_parentprogressview'),
	get_string('config_behaviour_api_information', 'report_parentprogressview')
));

$settings->add(new admin_setting_configtext(
	'report_parentprogressview_behaviour_api_base',
	get_string('config_behaviour_api_base', 'report_parentprogressview'),
	get_string('config_behaviour_api_base_desc', 'report_parentprogressview'),
	'',
	PARAM_NOTAGS,
	null
));

$settings->add(new admin_setting_configtext(
	'report_parentprogressview_behaviour_api_namespace',
	get_string('config_behaviour_api_namespace', 'report_parentprogressview'),
	get_string('config_behaviour_api_namespace_desc', 'report_parentprogressview'),
	'',
	PARAM_NOTAGS,
	null
));

$settings->add(new admin_setting_configtext(
	'report_parentprogressview_behaviour_api_route',
	get_string('config_behaviour_api_route', 'report_parentprogressview'),
	get_string('config_behaviour_api_route_desc', 'report_parentprogressview'),
	'',
	PARAM_NOTAGS,
	null
));

$settings->add(new admin_setting_configtext(
	'report_parentprogressview_behaviour_api_user',
	get_string('config_behaviour_api_user', 'report_parentprogressview'),
	get_string('config_behaviour_api_user_desc', 'report_parentprogressview'),
	'',
	PARAM_NOTAGS,
	null
));

$settings->add(new admin_setting_configpasswordunmask(
	'report_parentprogressview_behaviour_api_pass',
	get_string('config_behaviour_api_pass', 'report_parentprogressview'),
	get_string('config_behaviour_api_pass_desc', 'report_parentprogressview'),
	''
));

/*** Terms API settings ***/
$settings->add(new admin_setting_heading(
	'report_parentprogressview_terms_api_header',
	get_string('config_terms_api_header', 'report_parentprogressview'),
	get_string('config_terms_api_information', 'report_parentprogressview')
));

$settings->add(new admin_setting_configtext(
	'report_parentprogressview_terms_api_base',
	get_string('config_terms_api_base', 'report_parentprogressview'),
	get_string('config_terms_api_base_desc', 'report_parentprogressview'),
	'',
	PARAM_NOTAGS,
	null
));

$settings->add(new admin_setting_configtext(
	'report_parentprogressview_terms_api_namespace',
	get_string('config_terms_api_namespace', 'report_parentprogressview'),
	get_string('config_terms_api_namespace_desc', 'report_parentprogressview'),
	'',
	PARAM_NOTAGS,
	null
));

$settings->add(new admin_setting_configtext(
	'report_parentprogressview_terms_api_route',
	get_string('config_terms_api_route', 'report_parentprogressview'),
	get_string('config_terms_api_route_desc', 'report_parentprogressview'),
	'',
	PARAM_NOTAGS,
	null
));


/*** Attendance Marks API settings ***/
$settings->add(new admin_setting_heading(
	'report_parentprogressview_attendance_marks_api_header',
	get_string('config_attendance_marks_api_header', 'report_parentprogressview'),
	get_string('config_attendance_marks_api_information', 'report_parentprogressview')
));

$settings->add(new admin_setting_configtext(
	'report_parentprogressview_attendance_marks_api_base',
	get_string('config_attendance_marks_api_base', 'report_parentprogressview'),
	get_string('config_attendance_marks_api_base_desc', 'report_parentprogressview'),
	'',
	PARAM_NOTAGS,
	null
));

$settings->add(new admin_setting_configtext(
	'report_parentprogressview_attendance_marks_api_namespace',
	get_string('config_attendance_marks_api_namespace', 'report_parentprogressview'),
	get_string('config_attendance_marks_api_namespace_desc', 'report_parentprogressview'),
	'',
	PARAM_NOTAGS,
	null
));

$settings->add(new admin_setting_configtext(
	'report_parentprogressview_attendance_marks_api_route',
	get_string('config_attendance_marks_api_route', 'report_parentprogressview'),
	get_string('config_attendance_marks_api_route_desc', 'report_parentprogressview'),
	'',
	PARAM_NOTAGS,
	null
));

$settings->add(new admin_setting_configtext(
	'report_parentprogressview_attendance_marks_api_user',
	get_string('config_attendance_marks_api_user', 'report_parentprogressview'),
	get_string('config_attendance_marks_api_user_desc', 'report_parentprogressview'),
	'',
	PARAM_NOTAGS,
	null
));

$settings->add(new admin_setting_configpasswordunmask(
	'report_parentprogressview_attendance_marks_api_pass',
	get_string('config_attendance_marks_api_pass', 'report_parentprogressview'),
	get_string('config_attendance_marks_api_pass_desc', 'report_parentprogressview'),
	''
));

/*** Attendance Summaries API settings ***/
$settings->add(new admin_setting_heading(
	'report_parentprogressview_attendance_summaries_api_header',
	get_string('config_attendance_summaries_api_header', 'report_parentprogressview'),
	get_string('config_attendance_summaries_api_information', 'report_parentprogressview')
));

$settings->add(new admin_setting_configtext(
	'report_parentprogressview_attendance_summaries_api_base',
	get_string('config_attendance_summaries_api_base', 'report_parentprogressview'),
	get_string('config_attendance_summaries_api_base_desc', 'report_parentprogressview'),
	'',
	PARAM_NOTAGS,
	null
));

$settings->add(new admin_setting_configtext(
	'report_parentprogressview_attendance_summaries_api_namespace',
	get_string('config_attendance_summaries_api_namespace', 'report_parentprogressview'),
	get_string('config_attendance_summaries_api_namespace_desc', 'report_parentprogressview'),
	'',
	PARAM_NOTAGS,
	null
));

$settings->add(new admin_setting_configtext(
	'report_parentprogressview_attendance_summaries_api_route',
	get_string('config_attendance_summaries_api_route', 'report_parentprogressview'),
	get_string('config_attendance_summaries_api_route_desc', 'report_parentprogressview'),
	'',
	PARAM_NOTAGS,
	null
));

$settings->add(new admin_setting_configtext(
	'report_parentprogressview_attendance_summaries_api_user',
	get_string('config_attendance_summaries_api_user', 'report_parentprogressview'),
	get_string('config_attendance_summaries_api_user_desc', 'report_parentprogressview'),
	'',
	PARAM_NOTAGS,
	null
));

$settings->add(new admin_setting_configpasswordunmask(
	'report_parentprogressview_attendance_summaries_api_pass',
	get_string('config_attendance_summaries_api_pass', 'report_parentprogressview'),
	get_string('config_attendance_summaries_api_pass_desc', 'report_parentprogressview'),
	''
));


/*** Timetables API settings ***/
$settings->add(new admin_setting_heading(
	'report_parentprogressview_timetables_api_header',
	get_string('config_timetables_api_header', 'report_parentprogressview'),
	get_string('config_timetables_api_information', 'report_parentprogressview')
));

$settings->add(new admin_setting_configtext(
	'report_parentprogressview_timetables_api_base',
	get_string('config_timetables_api_base', 'report_parentprogressview'),
	get_string('config_timetables_api_base_desc', 'report_parentprogressview'),
	'',
	PARAM_NOTAGS,
	null
));

$settings->add(new admin_setting_configtext(
	'report_parentprogressview_timetables_api_namespace',
	get_string('config_timetables_api_namespace', 'report_parentprogressview'),
	get_string('config_timetables_api_namespace_desc', 'report_parentprogressview'),
	'',
	PARAM_NOTAGS,
	null
));

$settings->add(new admin_setting_configtext(
	'report_parentprogressview_timetables_api_route',
	get_string('config_timetables_api_route', 'report_parentprogressview'),
	get_string('config_timetables_api_route_desc', 'report_parentprogressview'),
	'',
	PARAM_NOTAGS,
	null
));

$settings->add(new admin_setting_configtext(
	'report_parentprogressview_timetables_api_user',
	get_string('config_timetables_api_user', 'report_parentprogressview'),
	get_string('config_timetables_api_user_desc', 'report_parentprogressview'),
	'',
	PARAM_NOTAGS,
	null
));

$settings->add(new admin_setting_configpasswordunmask(
	'report_parentprogressview_timetables_api_pass',
	get_string('config_timetables_api_pass', 'report_parentprogressview'),
	get_string('config_timetables_api_pass_desc', 'report_parentprogressview'),
	''
));

$settings->add(new admin_setting_configtextarea(
	'report_parentprogressview_timetables_html_prepend',
	get_string('config_timetables_html_prepend', 'report_parentprogressview'),
	get_string('config_timetables_html_prepend_desc', 'report_parentprogressview'),
	'',
	PARAM_RAW,
	null
));

/*** Report Display Settings ***/
$settings->add(new admin_setting_heading(
	'report_parentprogressview_display_settings_header',
	get_string('config_display_settings_header', 'report_parentprogressview'),
	get_string('config_display_settings_header_desc', 'report_parentprogressview')
));

$settings->add(new admin_setting_configtext(
	'report_parentprogressview_link_no_documents_help_page',
	get_string('config_no_documents_help_page', 'report_parentprogressview'),
	get_string('config_no_documents_help_page_desc', 'report_parentprogressview'),
	'',
	PARAM_URL,
	null
));
