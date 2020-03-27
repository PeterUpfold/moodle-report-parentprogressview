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
class achievements_page implements renderable, templatable {

	/**
	 * Holds configuration information and credentials for accessing the REST API.
	 */
	private $configuration;

	/**
	 * Array of usernames for pupils/mentees that are attached to the current user.
	 */
	private $attached_usernames;

	/**
	 * Earliest date from which to show achievements.
	 */
	private $earliest_date;

	/**
	 * Latest date at which to show achievements.
	 */
	private $latest_date;
	
	/**
	 * Set up the renderable with the specified timestamps
	 * detailing the required achievements range.
	 */
	public function __construct($user, $earliest_date, $latest_date) {
		global $CFG;

		$this->configuration = new stdClass();	

		if (empty($CFG->report_parentprogressview_achievements_api_user)) {
			\debugging('No username is set for API access to the achievements API. Normally this should be set in the plugin\'s settings in Site Administration.', DEBUG_NORMAL);
		}
		if (empty($CFG->report_parentprogressview_achievements_api_pass)) {
			\debugging('No password is set for API access to the achievements API. Normally this should be set in the plugin\'s settings in Site Administration.', DEBUG_NORMAL);
		}
		if (empty($CFG->report_parentprogressview_achievements_api_base)) {
			\debugging('No base URI is set for API access to the achievements API. Normally this should be set in the plugin\'s settings in Site Administration.', DEBUG_NORMAL);
		}
		if (empty($CFG->report_parentprogressview_achievements_api_namespace)) {
			\debugging('No namespace is set for API access to the achievements API. Normally this should be set in the plugin\'s settings in Site Administration.', DEBUG_NORMAL);
		}
		if (empty($CFG->report_parentprogressview_achievements_api_route)) {
			\debugging('No route is set for API access to the achievements API. Normally this should be set in the plugin\'s settings in Site Administration.', DEBUG_NORMAL);
		}
		if (empty($CFG->report_parentprogressview_achievement_totals_api_route)) {
			\debugging('No route is set for API access to the achievement totals API. Normally this should be set in the plugin\'s settings in Site Administration.', DEBUG_NORMAL);
		}

		$this->configuration->username       = $CFG->report_parentprogressview_achievements_api_user;
		$this->configuration->password       = $CFG->report_parentprogressview_achievements_api_pass;

		$this->configuration->base           = $CFG->report_parentprogressview_achievements_api_base;
		$this->configuration->namespace      = $CFG->report_parentprogressview_achievements_api_namespace;
		$this->configuration->route          = $CFG->report_parentprogressview_achievements_api_route;

		$this->configuration->totals_route   = $CFG->report_parentprogressview_achievement_totals_api_route;

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
	 * Return the data for use in the template.
	 * @return \stdClass
	 */
	public function prepare_data($include_form = true) {
		global $DB, $CFG;
		$data = new stdClass();

		require_once(dirname(__FILE__) . '/mobile_ion_grid.php');
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
				$request->add_query_argument('per_page', 100);
				$request->add_meta_query('username', $username, '=');
				$request->add_meta_query('date', $this->earliest_date, '>');
				$request->add_meta_query('date', $this->latest_date, '<');

				$result = $request->request();

				ob_get_clean();
				ob_start();
				// make a table from these results
				$table = new \report_parentprogressview\local\achievements_table($username, '/report/parentprogressview/achievements.php');
				$table->setup();
				
				if ($request->status == 200) {
					$table->fill_table_with_json_data($result);
				}

				$table->finish_output();
				$output[$o_count]->table = ob_get_clean();

				// list for mobile app
				$ion_grid = new mobile_ion_grid(
					$result,
					array(
						'date',
						'type',
						'points'
					)
				);

				try {
					$output[$o_count]->ion_grid = $ion_grid->render();
				}
				catch (\InvalidArgumentException $iae) {
					error_log('The data passed to the ion-grid renderer was not an array of stdClass objects, despite having >0 count -- data for ' . $username);
				}
				// get achievement points totals
				$conduct_totals_request = new  \report_parentprogressview\local\WP_REST_API_Request(
					$this->configuration->base,
					$this->configuration->namespace,
					$this->configuration->totals_route,
					$this->configuration->username,
					$this->configuration->password);
				$conduct_totals_request->add_query_argument('status', 'private');
				$conduct_totals_request->add_query_argument('orderby', 'date');
				$conduct_totals_request->add_query_argument('order', 'desc');
				$conduct_totals_request->add_meta_query('username', $username, '=');
				//$conduct_totals_request->add_query_argument('after', date('c', strtotime($this->earliest_date)));
				//$conduct_totals_request->add_query_argument('before', date('c', strtotime($this->latest_date)));
				$conduct_totals = $conduct_totals_request->request();

				if ($conduct_totals_request->status == 200 && is_array($conduct_totals) && count($conduct_totals) > 0) {
					$output[$o_count]->total_achievement_points = intval( $conduct_totals[0]->total_achievement_points );
					$output[$o_count]->total_behaviour_points = intval( $conduct_totals[0]->total_behaviour_points );
				}
				else {
					$output[$o_count]->total_achievement_points = '??'; 
					$output[$o_count]->total_behaviour_points = '??'; 
				}

				++$o_count;

			}
		}


		$data->link_documents_page = new \moodle_url('/report/parentprogressview/index.php');
		$data->link_behaviour_page = new \moodle_url('/report/parentprogressview/behaviour.php');
		$data->link_attendance_page = new \moodle_url('/report/parentprogressview/attendance.php');
		$data->link_achievement_page = new \moodle_url('/report/parentprogressview/achievements.php');	
		$data->link_timetables_page = new \moodle_url('/report/parentprogressview/timetables.php');	

		$data->achievements_by_pupil = $output;

		$data->featurehelp = get_string('achievementfeaturehelp', 'report_parentprogressview');
		$data->featurename = get_string('achievementfeaturename', 'report_parentprogressview');
	
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
	
		return $this->prepare_data(true);
	}
};
