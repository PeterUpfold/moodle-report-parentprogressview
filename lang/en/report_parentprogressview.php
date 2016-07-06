<?php
/*
Parent Progress View, a module for Moodle to allow the viewing of documents and pupil data by authorised parents.
    Copyright (C) 2016-17 Test Valley School.

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
 * Strings in English for this plugin 
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */

 $string['pluginname'] = 'Parent Progress View';
 $string['intro'] = 'This report allows parents to view reports and other documents as well as see other information about the progress of their children.';
 $string['eventreportviewed'] = 'Parent Progress View page viewed';
 $string['eventreportvieweddescription'] = 'The user with id \'%d\' viewed the main Parent Progress View page.';
 $string['eventdocumentviewed'] = 'Published document viewed or downloaded';
 $string['eventdocumentvieweddescription'] = 'The user with id \'%d\' viewed the document with id \'%d\' and filename \'%s\'.';
 $string['eventachievementviewed'] = 'Parent Progress View achievements viewed';
 $string['eventachievementvieweddescription'] = 'The user with id \'%d\' viewed the achievements for \'%s\'.';
 $string['eventattendanceviewed'] = 'Parent Progress View attendance viewed';
 $string['eventattendancevieweddescription'] = 'The user with id \'%d\' viewed attendance for \'%s\'.';
 $string['eventbehaviourviewed'] = 'Parent Progress View behaviour viewed';
 $string['eventbehaviourvieweddescription'] = 'The user with id \'%d\' viewed behaviour for \'%s\'.';
 $string['report_parentprogressview'] = 'Parent Progress View';
 $string['parentprogressview:view'] = 'Parent Progress View';
 $string['report/parentprogressview:view'] = 'View Main Parent Progress Page';
 $string['nodocumentstoview'] = 'There are no documents for you to view for this pupil in this time range.';
 $string['nodocumentshelp1'] = 'Please check the <a href="{$a}">Key Dates for Parents</a> to see when reports are expected to be published to parents, or adjust the dates under <em>Show Items Published</em>.';
 $string['nodocumentshelp2'] = 'If you are expecting documents to be available to you, you may need to check with the school to see if your Moodle account is correctly linked to your children.';
 $string['noattachedpupils'] = 'There are no pupil Moodle accounts attached to your parent Moodle account. Please check with the school to see if your child has logged onto Moodle successfully and ask to get the accounts connected.';
 $string['noachievementsinrange'] = 'There are no achievements recorded in this system for that date range.';
 $string['noattendancemarksinrange'] = 'There are no attendance marks recorded in this system for that date range.';
 $string['nobehaviourinrange'] = 'There are no behaviour events recorded in this system for that date range.';
 $string['documentsfeaturename'] = 'Documents (Reports)';
 $string['achievementfeaturename'] = 'Achievement';
 $string['behaviourfeaturename'] = 'Behaviour';
 $string['attendancefeaturename'] = 'Attendance';
 $string['achievementfeaturehelp'] = 'The Achievements page shows the recent achievement points awarded to your child. Please remember that this system is updated once per day. If you have any questions about achievements, do not hesitate to contact your child\'s form tutor in the first instance.';
 $string['behaviourfeaturehelp'] = 'The Behaviour page shows the recent behaviour points your child has accumulated. Please remember that this system is updated once per day. If you have any questions about behaviour points, do not hesitate to contact your child\'s form tutor in the first instance.';
 $string['attendancefeaturehelp'] = 'The Attendance page shows your child\'s recent attendance marks for both morning and afternoon registration. Please note that this system is updated twice per day and there will therefore be a delay before your child\'s registration marks appear here. If you have any questions about attendance, do not hesitate to contact your child\'s form tutor in the first instance.';
 $string['nopermission'] = 'The specified document does not exist, or you do not have permission to view it.';
 $string['documentmeta:filename'] = 'Name';
 $string['documentmeta:datepublished'] = 'Date Published to Parents';
 $string['documentmeta:filetype'] = 'File type';
 $string['daterangeintro'] = 'Show Items Published:';
 $string['daterangeshowbutton'] = 'Show';
 $string['date'] = 'Date';
 $string['type'] = 'Type';
 $string['lesson_period'] = 'Period';
 $string['lesson_class'] = 'Class Code';
 $string['lesson_subject'] = 'Subject';
 $string['points'] = 'Points';
 $string['mark_date'] = 'Date';
 $string['am_pm'] = 'AM/PM';
 $string['mark'] = 'Mark';
 $string['mark_description'] = 'Description';

 $string['noattendancesummaryplaceholder'] = '--';
 $string['noattendancesummary'] = 'Apologies, but the attendance summary information is not currently available.';
 $string['attendancesummarysynopsis'] = 'Attendance Statistics for the Academic Year {$a}';
 $string['pcntpresent'] = '% Present or Approved Educational Activity';
 $string['pcntauthorisedabsences'] = '% Authorised Absences';
 $string['pcntunauthorisedabsences'] = '% Unauthorised Absences';
 $string['pcntunexplainedabsences'] = '% Unexplained Absences';
 $string['pcntlatebeforereg'] = '% Late before Reg';
 $string['pcntlateafterreg'] = '% Late after Reg';
 $string['present'] = 'Present or Approved Educational Activity';
 $string['authorisedabsences'] = 'Authorised Absences';
 $string['unauthorisedabsences'] = 'Unauthorised Absences';
 $string['unexplainedabsences'] = 'Unexplained Absences';
 $string['latebeforereg'] = 'Late before Reg';
 $string['lateafterreg'] = 'Late after Reg';
 $string['attendancemarkstableheading'] = 'Attendance Marks';

 $string['config_database_header'] = 'Database Configuration for Documents';
 $string['config_database_information'] = 'Documents (reports) are accessed through direct access to the database table where these are stored. They are expected to be stored in a format consistent with the <strong>tvs-mis-documents-to-moodle</strong> WordPress plugin.';

 $string['config_documents_dbuser'] = 'Database Username';
 $string['config_documents_dbuser_desc'] = 'The username to use to connect to the database where documents are stored.';
 $string['config_documents_dbpass'] = 'Database Password';
 $string['config_documents_dbpass_desc'] = 'The password to use to connect to the database where documents are stored.';
 $string['config_documents_dbhost'] = 'Database Hostname/IP Address';
 $string['config_documents_dbhost_desc'] = 'The hostname or IP address of the database server where documents are stored.';
 $string['config_documents_dbname'] = 'Database Name';
 $string['config_documents_dbname_desc'] = 'The name of the database where documents are stored.';
 $string['config_documents_dbtable'] = 'Database Table';
 $string['config_documents_dbtable_desc'] = 'The table in the database where the documents are stored.';

 $string['config_achievements_api_header'] = 'API Access for Achievements';
 $string['config_achievements_api_information'] = 'These configuration settings define how Parent Progress View will query for achievements.';
 $string['config_achievements_api_base'] = 'Achievements API Base URI';
 $string['config_achievements_api_base_desc'] = 'The base URI of the API endpoint from which achievement data can be queried. Usually will end with <em>/wp-json</em>.';

 $string['config_achievements_api_namespace'] = 'Achievements API Namespace';
 $string['config_achievements_api_namespace_desc'] = 'The API namespace from which achievement data can be queried. Usually <em>/wp/v2/</em>.';
 
 $string['config_achievements_api_route'] = 'Achievements API Route';
 $string['config_achievements_api_route_desc'] = 'The API route for achievements. Usually <em>achievements</em>.';

 $string['config_achievements_api_user'] = 'Achievements API Username';
 $string['config_achievements_api_user_desc'] = 'The username to use to authenticate with the achievements API.';

 $string['config_achievements_api_pass'] = 'Achievements API Password';
 $string['config_achievements_api_pass_desc'] = 'The password to use to authenticate with the achievements API.';

 $string['config_behaviour_api_header'] = 'API Access for Behaviour';
 $string['config_behaviour_api_information'] = 'These configuration settings define how Parent Progress View will query for behaviour.';
 $string['config_behaviour_api_base'] = 'Behaviour API Base URI';
 $string['config_behaviour_api_base_desc'] = 'The base URI of the API endpoint from which behaviour data can be queried. Usually will end with <em>/wp-json</em>.';

 $string['config_behaviour_api_namespace'] = 'Behaviour API Namespace';
 $string['config_behaviour_api_namespace_desc'] = 'The API namespace from which behaviour data can be queried. Usually <em>/wp/v2/</em>.';
 
 $string['config_behaviour_api_route'] = 'Behaviour API Route';
 $string['config_behaviour_api_route_desc'] = 'The API route for behaviour. Usually <em>behaviour-incidents</em>.';

 $string['config_behaviour_api_user'] = 'Behaviour API Username';
 $string['config_behaviour_api_user_desc'] = 'The username to use to authenticate with the behaviour API.';

 $string['config_behaviour_api_pass'] = 'Behaviour API Password';
 $string['config_behaviour_api_pass_desc'] = 'The password to use to authenticate with the behaviour API.';

 $string['config_terms_api_header'] = 'API Access for Academic Terms';
 $string['config_terms_api_information'] = 'These configuration settings define how Parent Progress View will query for information about academic term dates.';
 $string['config_terms_api_base'] = 'Terms API Base URI';
 $string['config_terms_api_base_desc'] = 'The base URI of the API endpoint from which academic term data can be queried. Usually will end with <em>/wp-json</em>.';

 $string['config_terms_api_namespace'] = 'Terms API Namespace';
 $string['config_terms_api_namespace_desc'] = 'The API namespace from which academic term data can be queried. Usually <em>/wp/v2/</em>.';
 
 $string['config_terms_api_route'] = 'Terms API Route';
 $string['config_terms_api_route_desc'] = 'The API route for academic terms. Usually <em>terms</em>.';

 $string['config_attendance_marks_api_header'] = 'API Access for Attendance Marks';
 $string['config_attendance_marks_api_information'] = 'These configuration settings define how Parent Progress View will query for attendance marks.';
 $string['config_attendance_marks_api_base'] = 'Attendance Marks API Base URI';
 $string['config_attendance_marks_api_base_desc'] = 'The base URI of the API endpoint from which attendance marks data can be queried. Usually will end with <em>/wp-json</em>.';

 $string['config_attendance_marks_api_namespace'] = 'Attendance Marks API Namespace';
 $string['config_attendance_marks_api_namespace_desc'] = 'The API namespace from which attendance marks data can be queried. Usually <em>/wp/v2/</em>.';
 
 $string['config_attendance_marks_api_route'] = 'Attendance Marks API Route';
 $string['config_attendance_marks_api_route_desc'] = 'The API route for attendance marks. Usually <em>attendance-marks</em>.';

 $string['config_attendance_marks_api_user'] = 'Attendance Marks API Username';
 $string['config_attendance_marks_api_user_desc'] = 'The username to use to authenticate with the attendance marks API.';

 $string['config_attendance_marks_api_pass'] = 'Attendance Marks API Password';
 $string['config_attendance_marks_api_pass_desc'] = 'The password to use to authenticate with the attendance marks API.';

 $string['config_attendance_summaries_api_header'] = 'API Access for Attendance Summaries';
 $string['config_attendance_summaries_api_information'] = 'These configuration settings define how Parent Progress View will query for attendance summary data.';
 $string['config_attendance_summaries_api_base'] = 'Attendance Summaries API Base URI';
 $string['config_attendance_summaries_api_base_desc'] = 'The base URI of the API endpoint from which attendance summaries data can be queried. Usually will end with <em>/wp-json</em>.';

 $string['config_attendance_summaries_api_namespace'] = 'Attendance Summaries API Namespace';
 $string['config_attendance_summaries_api_namespace_desc'] = 'The API namespace from which attendance summaries data can be queried. Usually <em>/wp/v2/</em>.';
 
 $string['config_attendance_summaries_api_route'] = 'Attendance Summaries API Route';
 $string['config_attendance_summaries_api_route_desc'] = 'The API route for attendance summaries. Usually <em>attendance_summaries</em>.';

 $string['config_attendance_summaries_api_user'] = 'Attendance Summaries API Username';
 $string['config_attendance_summaries_api_user_desc'] = 'The username to use to authenticate with the attendance summaries API.';

 $string['config_attendance_summaries_api_pass'] = 'Attendance Summaries API Password';
 $string['config_attendance_summaries_api_pass_desc'] = 'The password to use to authenticate with the attendance summaries API.';

 $string['config_parent_roleid'] = 'Parent Role';
 $string['config_parent_roleid_desc'] = 'Choose the \'Parent\' role within your VLE system. This is the role that will be used to verify the relationship between the pupil account and parent account.';
 /*In order to be able to determine which users are attached to a parent account, the system must look up the roles associated with the current user, find the parent role, and then determine the contexts in which that parent role is assigned.';*/

 $string['config_display_settings_header'] = 'Report Display Settings';
 $string['config_display_settings_header_desc'] = 'Settings pertaining to the display of the report page.';

 $string['config_no_documents_help_page'] = 'No Documents to View Help Page';
 $string['config_no_documents_help_page_desc'] = 'A link to a page with explanation/help which is displayed when no documents are available to view.';
