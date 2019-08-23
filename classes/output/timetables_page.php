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
 * Output renderable (handler to set up data) for timetable page mustache template.
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
 * Output renderable (handler to set up data) for timetable page mustache template.
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */
class timetables_page implements renderable, templatable {

	/**
	 * Holds configuration information and credentials for accessing the REST API.
	 */
	private $configuration;

	/**
	 * Array of usernames for pupils/mentees that are attached to the current user.
	 */
	private $attached_usernames;

	/**
	 * Set up the renderable with the specified timestamps
	 * detailing the required attendance range.
	 */
	public function __construct($user) {
		
		global $CFG;

		$this->configuration = new stdClass();	

		if (empty($CFG->report_parentprogressview_timetables_api_user)) {
			\debugging('No username is set for API access to the timetables API. Normally this should be set in the plugin\'s settings in Site Administration.', DEBUG_NORMAL);
		}
		if (empty($CFG->report_parentprogressview_timetables_api_pass)) {
			\debugging('No password is set for API access to the timetables API. Normally this should be set in the plugin\'s settings in Site Administration.', DEBUG_NORMAL);
		}
		if (empty($CFG->report_parentprogressview_timetables_api_base)) {
			\debugging('No base URI is set for API access to the timetables API. Normally this should be set in the plugin\'s settings in Site Administration.', DEBUG_NORMAL);
		}
		if (empty($CFG->report_parentprogressview_timetables_api_namespace)) {
			\debugging('No namespace is set for API access to the timetables API. Normally this should be set in the plugin\'s settings in Site Administration.', DEBUG_NORMAL);
		}
		if (empty($CFG->report_parentprogressview_timetables_api_route)) {
			\debugging('No route is set for API access to the timetables API. Normally this should be set in the plugin\'s settings in Site Administration.', DEBUG_NORMAL);
		}

		$this->configuration->username       = $CFG->report_parentprogressview_timetables_api_user;
		$this->configuration->password       = $CFG->report_parentprogressview_timetables_api_pass;

		$this->configuration->base           = $CFG->report_parentprogressview_timetables_api_base;
		$this->configuration->namespace      = $CFG->report_parentprogressview_timetables_api_namespace;
		$this->configuration->route          = $CFG->report_parentprogressview_timetables_api_route;

		$this->attached_usernames = \report_parentprogressview\local\common_utilities::get_attached_usernames($user);

		require_once(dirname(__FILE__) . '/../local/hub_api_request.php');

	}

	/**
	 * Return the data for use in the template.
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
					$this->configuration->base,
					$this->configuration->namespace,
					$this->configuration->route,
					$this->configuration->username,
					$this->configuration->password);

                                $request->add_query_argument('status', 'private');
                                $request->add_query_argument('orderby', 'date');
                                $request->add_query_argument('order', 'desc');
                                $request->add_query_argument('per_page', 1);
                                $request->add_meta_query('username', $username, '=');

				$result = $request->request();

                                if ($request->status == 200) {
                                    $output[$o_count]->timetables = [];

                                    foreach($result as $result_timetable) {
                                        $timetable = new stdClass();
                                        //$timetable->content = $result_timetable->content->rendered;
                                        $timetable->content_base64 = base64_encode($CFG->report_parentprogressview_timetables_html_prepend . $result_timetable->content->rendered);
                                        $timetable->printable_link = new \moodle_url('/report/parentprogressview/timetable.php', [
                                            'username' => $username,
                                            'action'   => 'invokeprint'
                                        ]);
                                        $output[$o_count]->timetables[] = $timetable;
                                    }
                                }

				++$o_count;

			}
		}


		$data->link_documents_page = new \moodle_url('/report/parentprogressview/index.php');
		$data->link_behaviour_page = new \moodle_url('/report/parentprogressview/behaviour.php');
		$data->link_attendance_page = new \moodle_url('/report/parentprogressview/attendance.php');
		$data->link_achievement_page = new \moodle_url('/report/parentprogressview/achievements.php');	
		$data->link_timetables_page = new \moodle_url('/report/parentprogressview/timetables.php');	

		$data->timetables_by_pupil = $output;


		return $data;
	}

	/**
	 * Export the data for use in the Mustache template.
	 */
	public function export_for_template(renderer_base $output) {
	
		return $this->prepare_data(true);
	}
};
