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
 * Output a pupil's timetable as HTML content, where the currently logged in Moodle
 * user has the right to view information relating to that pupil.
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */

require(dirname(__FILE__).'/../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once(dirname(__FILE__) . '/classes/local/common_utilities.php');
require_once(dirname(__FILE__) . '/classes/local/hub_api_request.php');

header('X-Robots-Tag: noindex, nofollow');
header('Cache-Control: private');

require_login(null, false);
$target_username = required_param('username', PARAM_ALPHANUM);

if (!in_array($target_username, \report_parentprogressview\local\common_utilities::get_attached_usernames($USER))) {
	header('HTTP/1.0 403 Forbidden');
	throw new Exception(get_string('timetable_nopermission', 'report_parentprogressview'));
}

$request = new \report_parentprogressview\local\WP_REST_API_Request(
	$CFG->report_parentprogressview_timetables_api_base,
	$CFG->report_parentprogressview_timetables_api_namespace,
	$CFG->report_parentprogressview_timetables_api_route,
	$CFG->report_parentprogressview_timetables_api_user,
	$CFG->report_parentprogressview_timetables_api_pass);

$request->add_query_argument('status', 'private');
$request->add_query_argument('orderby', 'date');
$request->add_query_argument('order', 'desc');
$request->add_query_argument('per_page', 1);
$request->add_meta_query('username', $target_username, '=');

$result = $request->request();

// attempt to parse rendered content
if (count($result) < 1) {
	header('HTTP/1.1 404 Not Found');
	throw new Exception(get_string('timetable_nocontent', 'report_parentprogressview'));
}

echo $CFG->report_parentprogressview_timetables_html_prepend;

echo $result[0]->content->rendered;

