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
 * Output renderable (handler to set up data) for achievements page mustache template.
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */

namespace report_parentprogressview\output;

use renderable;
use renderer_base;
use templatable;
use stdClass;

/**
 * Output renderable (handler to set up data) for index page mustache template.
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */
class attendance_page implements renderable, templatable {

	/**
	 * Holds configuration information and credentials for accessing the REST API.
	 */
	private $configuration;

	/**
	 * Array of usernames for pupils/mentees that are attached to the current user.
	 */
	private $attached_usernames;

	/**
	 * Earliest date from which to show attendance marks.
	 */
	private $earliest_date;

	/**
	 * Latest date at which to show attendance marks.
	 */
	private $latest_date;
	
	/**
	 * Set up the renderable with the specified timestamps
	 * detailing the required attendance range.
	 */
	public function __construct($user, $earliest_date, $latest_date) {
		global $CFG;

		$this->configuration = new stdClass();	


		if (empty($CFG->report_parentprogressview_attendance_marks_api_user)) {
			\debugging('No username is set for API access to the attendance_marks API. Normally this should be set in the plugin\'s settings in Site Administration.', DEBUG_NORMAL);
		}
		if (empty($CFG->report_parentprogressview_attendance_marks_api_pass)) {
			\debugging('No password is set for API access to the attendance_marks API. Normally this should be set in the plugin\'s settings in Site Administration.', DEBUG_NORMAL);
		}
		if (empty($CFG->report_parentprogressview_attendance_marks_api_base)) {
			\debugging('No base URI is set for API access to the attendance_marks API. Normally this should be set in the plugin\'s settings in Site Administration.', DEBUG_NORMAL);
		}
		if (empty($CFG->report_parentprogressview_attendance_marks_api_namespace)) {
			\debugging('No namespace is set for API access to the attendance_marks API. Normally this should be set in the plugin\'s settings in Site Administration.', DEBUG_NORMAL);
		}
		if (empty($CFG->report_parentprogressview_attendance_marks_api_route)) {
			\debugging('No route is set for API access to the attendance_marks API. Normally this should be set in the plugin\'s settings in Site Administration.', DEBUG_NORMAL);
		}
		if (empty($CFG->report_parentprogressview_attendance_summaries_api_user)) {
			\debugging('No username is set for API access to the attendance_summaries API. Normally this should be set in the plugin\'s settings in Site Administration.', DEBUG_NORMAL);
		}
		if (empty($CFG->report_parentprogressview_attendance_summaries_api_pass)) {
			\debugging('No password is set for API access to the attendance_summaries API. Normally this should be set in the plugin\'s settings in Site Administration.', DEBUG_NORMAL);
		}
		if (empty($CFG->report_parentprogressview_attendance_summaries_api_base)) {
			\debugging('No base URI is set for API access to the attendance_summaries API. Normally this should be set in the plugin\'s settings in Site Administration.', DEBUG_NORMAL);
		}
		if (empty($CFG->report_parentprogressview_attendance_summaries_api_namespace)) {
			\debugging('No namespace is set for API access to the attendance_summaries API. Normally this should be set in the plugin\'s settings in Site Administration.', DEBUG_NORMAL);
		}
		if (empty($CFG->report_parentprogressview_attendance_summaries_api_route)) {
			\debugging('No route is set for API access to the attendance_summaries API. Normally this should be set in the plugin\'s settings in Site Administration.', DEBUG_NORMAL);
		}
		if (empty($CFG->report_parentprogressview_terms_api_base)) {
			\debugging('No base URI is set for API access to the terms API. Normally this should be set in the plugin\'s settings in Site Administration.', DEBUG_NORMAL);
		}
		if (empty($CFG->report_parentprogressview_terms_api_namespace)) {
			\debugging('No namespace is set for API access to the terms API. Normally this should be set in the plugin\'s settings in Site Administration.', DEBUG_NORMAL);
		}
		if (empty($CFG->report_parentprogressview_terms_api_route)) {
			\debugging('No route is set for API access to the terms API. Normally this should be set in the plugin\'s settings in Site Administration.', DEBUG_NORMAL);
		}

		$this->configuration->marks_username  = $CFG->report_parentprogressview_attendance_marks_api_user;
		$this->configuration->marks_password  = $CFG->report_parentprogressview_attendance_marks_api_pass;
		$this->configuration->marks_base      = $CFG->report_parentprogressview_attendance_marks_api_base;
		$this->configuration->marks_namespace = $CFG->report_parentprogressview_attendance_marks_api_namespace;
		$this->configuration->marks_route     = $CFG->report_parentprogressview_attendance_marks_api_route;

		$this->configuration->summaries_username  = $CFG->report_parentprogressview_attendance_summaries_api_user;
		$this->configuration->summaries_password  = $CFG->report_parentprogressview_attendance_summaries_api_pass;
		$this->configuration->summaries_base      = $CFG->report_parentprogressview_attendance_summaries_api_base;
		$this->configuration->summaries_namespace = $CFG->report_parentprogressview_attendance_summaries_api_namespace;
		$this->configuration->summaries_route     = $CFG->report_parentprogressview_attendance_summaries_api_route;

		$this->configuration->terms_base      = $CFG->report_parentprogressview_terms_api_base;
		$this->configuration->terms_namespace = $CFG->report_parentprogressview_terms_api_namespace;
		$this->configuration->terms_route     = $CFG->report_parentprogressview_terms_api_route;

		$this->attached_usernames = \report_parentprogressview\local\common_utilities::get_attached_usernames($user);

		$this->prepare_date_ranges($earliest_date, $latest_date);

		require_once(dirname(__FILE__) . '/../local/hub_api_request.php');

	}

	/**
	 * Determine appropriate date ranges to use (custom, if the user passed them in the form)
	 * and set these.
	 */
	private function prepare_date_ranges($earliest_date, $latest_date) {
		$date_ranges = \report_parentprogressview\local\common_utilities::prepare_date_ranges($earliest_date, $latest_date);

		$this->earliest_date = $date_ranges->earliest_date;
		$this->latest_date = $date_ranges->latest_date;
	}

	/**
	 * Prepare the data for the template.
	 *
	 * @return \stdClass
	 */
	public function prepare_data($include_form = true) {
		global $DB, $CFG;

		$data = new stdClass();

		$output = array();
		$o_count = 0;

		// loop over attached pupils and set up data for Mustache
		if (is_array($this->attached_usernames) && count($this->attached_usernames) > 0) {
			foreach($this->attached_usernames as $username) {
				
				// get pupil actual name from Moodle DB
				$record = $DB->get_record('user', array(
					'username' => $username)
				);

				$output[$o_count] = new stdClass();
				$output[$o_count]->user = $record;

				$request = new \report_parentprogressview\local\WP_REST_API_Request(
					$this->configuration->marks_base,
					$this->configuration->marks_namespace,
					$this->configuration->marks_route,
					$this->configuration->marks_username,
					$this->configuration->marks_password);

				$request->add_query_argument('status', 'private');
				$request->add_query_argument('orderby', 'date am_pm');
				$request->add_query_argument('order', 'desc');
				$request->add_query_argument('per_page', 100);
				$request->add_meta_query('username', $username, '=');
				$request->add_meta_query('mark_date', $this->earliest_date, '>');
				$request->add_meta_query('mark_date', $this->latest_date, '<');

				$result = $request->request();

				ob_get_clean();
				ob_start();
				// make a table from these results
				$table = new \report_parentprogressview\local\attendance_marks_table($username, '/report/parentprogressview/attendance.php');
				$table->setup();
				
				if ($request->status == 200) {
					$table->fill_table_with_json_data($result);
				}

				$table->finish_output();
				$output[$o_count]->table = ob_get_clean();

				// fill summary data with placeholder strings for now -- replace below if we get a data match
				$output[$o_count]->pcnt_present = get_string('noattendancesummaryplaceholder', 'report_parentprogressview');
				$output[$o_count]->pcnt_authorised_absences = get_string('noattendancesummaryplaceholder', 'report_parentprogressview');
				$output[$o_count]->pcnt_unauthorised_absences = get_string('noattendancesummaryplaceholder', 'report_parentprogressview');
				$output[$o_count]->pcnt_unexplained_absences = get_string('noattendancesummaryplaceholder', 'report_parentprogressview');
				$output[$o_count]->pcnt_late_before_reg = get_string('noattendancesummaryplaceholder', 'report_parentprogressview');
				$output[$o_count]->pcnt_late_after_reg = get_string('noattendancesummaryplaceholder', 'report_parentprogressview');
				$output[$o_count]->present = get_string('noattendancesummaryplaceholder', 'report_parentprogressview');
				$output[$o_count]->authorised_absences = get_string('noattendancesummaryplaceholder', 'report_parentprogressview');
				$output[$o_count]->unauthorised_absences = get_string('noattendancesummaryplaceholder', 'report_parentprogressview');
				$output[$o_count]->unexplained_absences = get_string('noattendancesummaryplaceholder', 'report_parentprogressview');
				$output[$o_count]->late_before_reg = get_string('noattendancesummaryplaceholder', 'report_parentprogressview');
				$output[$o_count]->late_after_reg = get_string('noattendancesummaryplaceholder', 'report_parentprogressview');



				// get current term to query attendance summary
				$request = new \report_parentprogressview\local\WP_REST_API_Request(
					$this->configuration->terms_base,
					$this->configuration->terms_namespace,
					$this->configuration->terms_route,
					$this->configuration->marks_username, // auth should not particularly matter here
					$this->configuration->marks_password
				);

				$request->add_query_argument('orderby', 'date');
				$request->add_query_argument('per_page', 100);
				$request->add_meta_query('start', $this->latest_date, '<=');
				$request->add_query_argument('order', 'desc');
				$result = $request->request();

				if ($request->status == 200 && count($result) > 0) {
					$term = $result[0];
					// get summary of attendance figures
					$request = new \report_parentprogressview\local\WP_REST_API_Request(
						$this->configuration->summaries_base,
						$this->configuration->summaries_namespace,
						$this->configuration->summaries_route,
						$this->configuration->summaries_username,
						$this->configuration->summaries_password
					);

					$request->add_query_argument('status', 'private');
					$request->add_query_argument('orderby', 'date');
					$request->add_query_argument('per_page', 100);
					$request->add_meta_query('username', $username, '=');
					$request->add_meta_query('academic_term', $term->id, '=');

					$result = $request->request();

					if ($request->status == 200 && count($result) > 0) {
						$output[$o_count]->pcnt_present = format_string($result[0]->pcnt_present);
						$output[$o_count]->pcnt_authorised_absences = format_string($result[0]->pcnt_authorised_absences);
						$output[$o_count]->pcnt_unauthorised_absences = format_string($result[0]->pcnt_unauthorised_absences);
						$output[$o_count]->pcnt_unexplained_absences = format_string($result[0]->pcnt_unexplained_absences);
						$output[$o_count]->pcnt_late_before_reg = format_string($result[0]->pcnt_late_before_reg);
						$output[$o_count]->pcnt_late_after_reg = format_string($result[0]->pcnt_late_after_reg);
						$output[$o_count]->present = format_string($result[0]->present);
						$output[$o_count]->authorised_absences = format_string($result[0]->authorised_absences);
						$output[$o_count]->unauthorised_absences = format_string($result[0]->unauthorised_absences);
						$output[$o_count]->unexplained_absences = format_string($result[0]->unexplained_absences);
						$output[$o_count]->late_before_reg = format_string($result[0]->late_before_reg);
						$output[$o_count]->late_after_reg = format_string($result[0]->late_after_reg);

						$output[$o_count]->attendance_summary_synopsis = get_string('attendancesummarysynopsis', 'report_parentprogressview'); // Clear the synopsis which previously had a generic 'unavailable' msg

						// calculate background colour for percentage present
						$pcnt_present = floatval($result[0]->pcnt_present);
						$output[$o_count]->pcnt_present_class = '';

						// ensure leading space in the CSS class below
						if ($pcnt_present >= 98) {
							$output[$o_count]->pcnt_present_class = ' no-risk'; //#60ea8e';
						}
						else if ($pcnt_present >= 97) {
							$output[$o_count]->pcnt_present_class = ' risk-of-underachievement'; //'#f7fc9c';
						}
						else if ($pcnt_present >= 94) {
							$output[$o_count]->pcnt_present_class = ' serious-risk-of-underachievement'; //'#fcd388';
						}
						else if ($pcnt_present >= 90) {
							$output[$o_count]->pcnt_present_class = ' severe-risk-of-extreme-risk'; //''#fcb09f';
						}
						else if ($pcnt_present >= 85) {
							$output[$o_count]->pcnt_present_class = ' extreme-risk'; //''#fcb09f';
						}
						else {
							$output[$o_count]->pcnt_present_class = ' extreme-risk'; //''#fcb09f';
						}

						$output[$o_count]->academic_year = $term->academic_year;
					}
					else {
						$output[$o_count]->attendance_summary_none = get_string('noattendancesummary', 'report_parentprogressview');
					}
				}
				else {	
						$output[$o_count]->attendance_summary_none = get_string('noattendancesummary', 'report_parentprogressview');
				}


				++$o_count;

			}
		}


		$data->link_documents_page = new \moodle_url('/report/parentprogressview/index.php');
		$data->link_behaviour_page = new \moodle_url('/report/parentprogressview/behaviour.php');
		$data->link_attendance_page = new \moodle_url('/report/parentprogressview/attendance.php');
		$data->link_achievement_page = new \moodle_url('/report/parentprogressview/achievements.php');	

		$data->attendance_marks_by_pupil = $output;

		$data->featurehelp = get_string('attendancefeaturehelp', 'report_parentprogressview');
		$data->featurename = get_string('attendancefeaturename', 'report_parentprogressview');

		if ($include_form) {
			require_once( dirname(__FILE__) . '/../local/daterange_form.php');
			$form = new \report_parentprogressview\local\daterange_form(null, null, 'post');
			$form->setDefault('datefrom', strtotime($this->earliest_date));
			$form->setDefault('dateto',  strtotime($this->latest_date));
			$data->daterange_form = $form->render();
		}

		return $data;

	}

	/**
	 * Export the data for use in the Mustache template.
	 */
	public function export_for_template(renderer_base $output) {
		global $DB;

		return $this->prepare_data(true);
	}
};
