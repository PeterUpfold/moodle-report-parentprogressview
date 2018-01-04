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
 * Common utilities for this module.
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */

namespace report_parentprogressview\local;

/**
 * Common utilities for this module.
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */
class common_utilities {

	/**
	 * Return the role ID associated with the 'parent' role.
	 * 
	 * This role connects pupil accounts and parent accounts and ensures that only
	 * authorised parent accounts get access to data about that pupil. If the option
	 * is not set, or appears invalid, then this method will throw an exception.
	 *
	 * @return int
	 */
	public static function get_parent_roleid() {
		global $CFG;
		static $prevalidated_parent_roleid = 0; // cache the result

		if ($prevalidated_parent_roleid) {
			return $prevalidated_parent_roleid;
		}

		if (!property_exists($CFG, 'report_parentprogressview_parent_roleid')) {
			throw new \Exception('The report_parentprogressview_parent_roleid option has not been defined. This issue is most likely an incomplete install of the plugin.');
		}

		if (empty($CFG->report_parentprogressview_parent_roleid)) {
			throw new \Exception('The report_parentprogressview_parent_roleid option is blank or set to zero. An administrator must set the appropriate role using this plugin\'s settings page under Site Administration.');
		}

		if (!is_numeric($CFG->report_parentprogressview_parent_roleid)) {
			throw new \Exception('The report_parentprogressview_parent_roleid is not numeric. This is an unexpected condition. An administrator can try to reset the appropriate role using the plugin\'s settings page under Site Administration');
		}

		// verify that the configured ID is actually a valid role ID in the system
		$roles = \get_all_roles();
		foreach($roles as $role) {
			if ((int)$CFG->report_parentprogressview_parent_roleid == $role->id) {
				$prevalidated_parent_roleid = $role->id;
				return $role->id;
			}
		}
		
		throw new \Exception(sprintf('The specified parent role ID %d was not found in the roles system.', (int)$CFG->report_parentprogressview_parent_roleid));
	}

	/**
	 * Return a list of the usernames for which the currently logged in user is allowed to access
	 * documents pertaining to those users.
	 */
	public static function get_attached_usernames($user) {
		global $DB;

		// get this user's role assignments, from which we can extract pupil user IDs that are attached to this parent
		$sql = "SELECT ra.id, ra.userid, ra.contextid, ra.roleid, ra.component, ra.itemid, c.path
                  FROM {role_assignments} ra
                  JOIN {context} c ON ra.contextid = c.id
                  JOIN {role} r ON ra.roleid = r.id
                 WHERE ra.userid = ?
              	ORDER BY contextlevel DESC, contextid ASC, r.sortorder ASC";

		$role_assignments = $DB->get_records_sql( $sql, array($user->id) );
		$pupil_user_ids = array();
		$pupils = array();

		if (is_array($role_assignments) && count($role_assignments) > 0 ) {
			// from their role assignments, determine which of these is for the 'parent' role

			foreach($role_assignments as $role) {
				// if this has the magic number, it is a 'parent' role

				if ($role->roleid == common_utilities::get_parent_roleid()) {
					// get the pupil user ID from this role context
					$context = \context::instance_by_id($role->contextid);
					$pupil_user_ids[] = (int)$context->get_url()->param('id');

				}
			}

			// now, get the user objects, so we can match usernames
			$pupils = $DB->get_records_list('user', 'id', $pupil_user_ids);
			
		}
		else {
			return array();
		}

		// now, we have a $pupils array, and can search The Hub's available documents with those usernames
		$pupil_usernames = array();
		foreach($pupils as $pupil) {
			$pupil_usernames[] = strtolower($pupil->username);
		}


		return $pupil_usernames;
	}

	/**
	 * Determine appropriate date ranges to use for the "Show Items From" box
	 * and the query itself. Will attempt to parse valid dates/times from
	 * its parameters, or will fall back to defaults
	 * (start of current term) otherwise.
	 *
	 * @return stdClass
	 */
	public static function prepare_date_ranges($earliest_date, $latest_date, $use_term_dates = true) {
		global $CFG;

		static $term_dates = array();

		$output = new \stdClass();

		// get term dates if we have not already
		if (count($term_dates) < 1 && $use_term_dates) {

			if (isset($CFG->report_parentprogressview_terms_api_base) &&
				isset($CFG->report_parentprogressview_terms_api_namespace) &&
				isset($CFG->report_parentprogressview_terms_api_route)
			) {

				require(dirname(__FILE__) . '/hub_api_request.php');

				$request = new \report_parentprogressview\local\WP_REST_API_Request(
					$CFG->report_parentprogressview_terms_api_base,
					$CFG->report_parentprogressview_terms_api_namespace,
					$CFG->report_parentprogressview_terms_api_route
				);

				$request->add_query_argument('status', 'publish');
				$request->add_query_argument('orderby', 'date');
				$request->add_query_argument('order', 'asc');
				// current term -- start is less than or equal to today's date...
				$request->add_meta_query('start', date('Y-m-d'), '<=');
				// ...and the end is greater than or equal to today's date
				$request->add_meta_query('end', date('Y-m-d'), '>=');

				$term_dates = $request->request();

				if ($request->status != 200) {
					// clear term dates again -- not useful data
					$term_dates = array();
				}

				if (count($term_dates) < 1) {
					// perhaps we are between terms -- let's go back to last term and try that
					$request = new \report_parentprogressview\local\WP_REST_API_Request(
						$CFG->report_parentprogressview_terms_api_base,
						$CFG->report_parentprogressview_terms_api_namespace,
						$CFG->report_parentprogressview_terms_api_route
					);

					$request->add_query_argument('status', 'publish');
					$request->add_query_argument('orderby', 'date');
					$request->add_query_argument('order', 'desc');
					// previous term -- start is less than or equal to today's date only
					$request->add_meta_query('start', date('Y-m-d'), '<=');

					$term_dates = $request->request();

					if ($request->status != 200) {
						// clear term dates again -- not useful data
						$term_dates = array();
					}
				}
			}
		}

		if ($earliest_date == null || $earliest_date == 0 || ( !is_numeric($earliest_date) && strtotime( $earliest_date ) == 0 ) ) {
			if (count($term_dates) > 0 && $use_term_dates && property_exists($term_dates[0], 'start') && property_exists($term_dates[0], 'end')) {
				// use term start and end dates
				$output->earliest_date = gmdate('Y-m-d', strtotime($term_dates[0]->start));
				$output->latest_date = gmdate('Y-m-d H:i:s', strtotime($term_dates[0]->end . ' + 1 day'));
				$latest_date = $output->latest_date; // to satisfy the separate latest date checking later
			}
			else {
				// determine assumed start of academic year
				if ( (int)gmdate('m') > 8 ) { // after August
					$earliest_date = gmdate('Y-m-d', strtotime('September 1')); // default to all from September this year
				}
				else {
					$earliest_date = gmdate('Y-m-d', strtotime('September 1 last year')); // default to all from September last year
				}
			}
			if (!isset($output->earliest_date)) {
				$output->earliest_date = $earliest_date;
			}
		}
		else if (is_numeric( $earliest_date ) ) {
			$earliest_date = gmdate('Y-m-d', $earliest_date);
			$output->earliest_date = $earliest_date;
		}
		else if (preg_match( '/\d{4}-\d{2}-\d{2}/', $earliest_date)) {
			$output->earliest_date = $earliest_date;
		}
		else {
			// determine assumed start of academic year
			if ( (int)gmdate('m') > 8 ) { // after August
				$earliest_date = gmdate('Y-m-d', strtotime('September 1')); // default to all from September this year
			}
			else {
				$earliest_date = gmdate('Y-m-d', strtotime('September 1 last year')); // default to all from September last year
			}
			$output->earliest_date = $earliest_date;
		}

		// latest published date
		if ($latest_date == null || $latest_date == 0 || (!is_numeric($latest_date) && strtotime( $latest_date ) == 0 ) ) {
			$latest_date = gmdate('Y-m-d H:i:s'); // default to now
			$output->latest_date = $latest_date;
		}
		else if (is_numeric( $latest_date ) )  {
			$latest_date = gmdate('Y-m-d H:i:s', $latest_date);
			$output->latest_date = $latest_date;
		}
		else if (preg_match( '/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $latest_date)) {
			$output->latest_date = $latest_date;
		}	
		else {
			$latest_date = gmdate('Y-m-d H:i:s'); // default to now
			$output->latest_date = $latest_date;
		}

		return $output;

	}

};
