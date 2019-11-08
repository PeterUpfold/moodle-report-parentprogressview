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
 * Represents a set of documents that the current user
 * has permission to view.
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */

namespace report_parentprogressview\local;

/**
 * Represents a set of documents that the current user
 * has permission to view.
 * @package report_parentprogressview
 * @author Test Valley School
 */
class document_set {

	/**
	 * An object representing a connection to the Hub database.
	 */
	protected $conn;


	/**
	 * An array of report_parentprogressview\local\document objects, populated by this class
	 * and made available to consumers through this property.
	 */
	public $documents = array();


	/**
	 * The Moodle user object to which this document set is attached.
	 */
	public $user;


	/**
	 * The earliest possible published date of any document in this set. May not actually correspond to a document date and time.
	 */
	public $earliest_published_date;

	/**
	 * The latest possible published date of any document in this set. May not actually correspond to a document date and time.
	 */
	public $latest_published_date;

	/**
	 * An stdClass() holding configuration for accessing The Hub's REST API.
	 */
	protected $api_configuration;

	/**
	 * An array of \stdClass() holding configurations for accessing The Hub's REST API,
	 * keyed by the API endpoint type.
	 */
	protected $api_configurations_by_endpoint;

	/**
	 * All usernames attached to the user who is requesting documents.
	 */
	protected $attached_usernames;


	/**
	 *
	 */
	public function __construct( $user ) {
		global $CFG;
	
		// set up a connection to The Hub
		$this->conn = new hub_database_connection();

		if (!property_exists($user, 'id')) {
			throw new \Exception("Passed user object is invalid");
		}
		
		$this->user = $user;
	
		// support for looking up term dates via the REST API
		$this->api_configuration 	         = new \stdClass();
		$this->api_configuration->username       = null;
		$this->api_configuration->password       = null;

		$this->api_configuration->base           = $CFG->report_parentprogressview_terms_api_base;
		$this->api_configuration->namespace      = $CFG->report_parentprogressview_terms_api_namespace;
		$this->api_configuration->route          = $CFG->report_parentprogressview_terms_api_route;

	}

	/**
	 * In the absence of an available earliest_published_date from the client, we will look up the start of the current term in The Hub by default.
	 * If this fails, we will assume the start of September, either this year or last year.
	 */
	protected function get_default_earliest_published_date() {
		require_once( dirname(__FILE__) . '/hub_api_request.php' );
		$request = new \report_parentprogressview\local\WP_REST_API_Request(
					$this->api_configuration->base,
					$this->api_configuration->namespace,
					$this->api_configuration->route,
					$this->api_configuration->username,
					$this->api_configuration->password);

		$request->add_query_argument('orderby', 'date');
		$request->add_query_argument('order', 'desc');
		$request->add_query_argument('status', 'publish');
		$request->add_meta_query('start', date('Y-m-d H:i:s'), '<=');
		
		$result = $request->request();

		if ($request->status == 200 && is_array($result) && count($result) > 0) {
			$candidate = date( 'Y-m-d H:i:s', strtotime($result[0]->start)); // do not use gmdate here -- assume Hub already returned normalised date/time
		}
		// determine assumed start of academic year
		else if ( (int)date('m') > 8 ) { // after August
			$candidate = gmdate('Y-m-d H:i:s', strtotime('September 1')); // default to all from September this year
		}
		else {
			$candidate = gmdate('Y-m-d H:i:s', strtotime('September 1 last year')); // default to all from September last year
		}

		/* determine document count with this candidate earliest date. If the count is 0, we may "cast back" the earliest date for user experience:
		so that at least some documents are returned instead of none, even if they are old! */

		$this->load_available_documents_for_user($candidate, gmdate('Y-m-d H:i:s'), false);

		$max_attempts = 4;
		$attempts = 0;

		while (count($this->documents) < 1 && $attempts < $max_attempts) {
			// this candidate returns too few results. Expand the earliest date

			// if there is a previous term we can cast back to, use that
			if (count( $result ) >= ($attempts + 2)) {
						/* total term count we check for is attempts + 2. Why?
						   Because $result[0] is the most recent term, which we already decided we did not want.
						   The next term index to try, then, is the loop iteration ($attempts) plus one.

						   If we are going to try and access $result[ $attempts + 1 ], the count must be at least
						   $result[ $attempts + 2 ]
						*/
				$candidate = date( 'Y-m-d H:i:s', strtotime($result[ $attempts + 1 ]->start));
			}
			else {
				// "10 minutes ought to do it"
				if ( (int) date('m') > 8 ) { // after August
					$candidate = gmdate('Y-m-d H:i:s', strtotime('September 1')); // default to all from September this year
				}
				else {
					$candidate = gmdate('Y-m-d H:i:s', strtotime('September 1 last year')); // default to all from September last year
				}
			}

			$this->documents = array(); // clear and reload document list
			$this->load_available_documents_for_user($candidate, gmdate('Y-m-d H:i:s'), false);

			++$attempts;
		}

		return $candidate;

	}


	/**
	 * Convert the input date range for later inclusion in the database query. Loads these values into the instance.
	 */
	public function prepare_date_range($earliest_published_date = null, $latest_published_date = null) {
		// prepare dates
		if ($earliest_published_date == null || $earliest_published_date == 0 || ( !is_numeric($earliest_published_date) && strtotime( $earliest_published_date ) == 0 ) ) {
			$earliest_published_date = $this->get_default_earliest_published_date();
		}
		else if (is_numeric( $earliest_published_date ) ) {
			$earliest_published_date = gmdate('Y-m-d H:i:s', $earliest_published_date);
			$this->earliest_published_date = $earliest_published_date;
		}
		else if ( preg_match( '/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $earliest_published_date ) ) {
			$this->earliest_published_date = $earliest_published_date;
		}
		else {
			$earliest_published_date = $this->get_default_earliest_published_date();
		}
		$this->earliest_published_date = $earliest_published_date;


		// latest published date
		if ($latest_published_date == null || $latest_published_date == 0 || (!is_numeric($latest_published_date) && strtotime( $latest_published_date ) == 0 ) ) {
			$latest_published_date = gmdate('Y-m-d H:i:s'); // default to now
		}
		else if (is_numeric( $latest_published_date ) )  {
			$latest_published_date = gmdate('Y-m-d H:i:s', $latest_published_date);
		}
		else if (preg_match( '/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $latest_published_date)) {
			$this->latest_published_date = $latest_published_date;
		}
		else {
			$latest_published_date = gmdate('Y-m-d H:i:s'); // default to now
		}
		$this->latest_published_date = $latest_published_date;
	}

	/**
	 * Given the current authenticated user, we will load any and all documents
	 * that are available to them to view into this class.
	 */
	public function load_available_documents_for_user($earliest_published_date = null, $latest_published_date = null, $include_hidden = false) {
		global $DB;

		$attached_usernames = common_utilities::get_attached_usernames($this->user);

		
		if ( $include_hidden ) { 
			// so we can have a single condition for hidden in the statement, we will say that by default
			// the parameter is hidden != 1. To include hidden, we say where hidden != 2.
			$hidden = 2;
		}
		else {
			$hidden = 1;
		}

		$this->prepare_date_range($earliest_published_date, $latest_published_date);

		// no efficient way to do this with IN and prepared statements :(
		$sql = "SELECT id, filename, pupil_username, LENGTH(data) AS length, date_added, date_published, hidden, mimetype, hash FROM {$this->conn->db_table}
			WHERE
				pupil_username = ? AND
				date_published > ? AND
				date_published < ? AND
				hidden != ?
			ORDER BY date_published DESC, filename DESC";
		

		$stmt = $this->conn->conn->prepare( $sql );

		$this->documents = []; // clear documents in case some were fetched previously when invoking to find default published dates

		foreach($attached_usernames as $username) {

			if (!$stmt) {
				throw new \Exception('Unable to prepare the database query.');
			}


			$stmt->bind_param( 'sssi', $username, $this->earliest_published_date, $this->latest_published_date, $hidden );
			$stmt->execute();

			$stmt->store_result();
			$stmt->bind_result( $id, $filename, $returned_username, $length, $date_added, $date_published, $hidden_output, $mimetype, $hash );

			while ($stmt->fetch()) {
				$this->documents[] = new document( $id, $filename, $returned_username, $length, $date_added, $date_published, $hidden_output, $mimetype, $hash, $this->conn);
			}

		}
			
		$stmt->close();

	}

	/**
	 * Return an associative array of documents by pupil username
	 */
	public function get_documents_by_pupil_username($earliest_published_date = null, $latest_published_date = null, $include_hidden = false) {
		global $DB;


		if (count($this->documents) < 1) {
			$this->load_available_documents_for_user($earliest_published_date, $latest_published_date, $include_hidden);
		}
		
		$output = array();

		$i = 0;
		foreach(common_utilities::get_attached_usernames($this->user) as $username) {
			// get user record
			$record = $DB->get_record('user', array(
				'username' => $username)
			);

			$output[$i] = new \stdClass();

			$output[$i]->documents = array();

			// add documents connected with this username
			foreach($this->documents as $document) {
				if ($document->pupil_username == $username) {
					$output[$i]->documents[] = $document;
				}
			}

			$output[$i]->user = $record;

			++$i;
		}


		return $output;
	}

	/**
	 * Return an associative array of documents and all other data by pupil username. Convenience function
	 * to allow the mobile app data, which is presented as a single multi-tabbed view, to be pulled at once.
	 */
	public function get_documents_and_other_data_by_pupil_username($earliest_published_date = null, $latest_published_date = null, $include_hidden = false) {
		global $DB, $USER, $CFG;


		require_once(dirname(__FILE__) . '/../output/attendance_page.php');

		$attendance_by_pupil_page = new \report_parentprogressview\output\attendance_page($USER, null, null); // default the earliest and latest for now
		$attendance_by_pupil = $attendance_by_pupil_page->prepare_data(false);

		require_once(dirname(__FILE__) . '/../output/achievements_page.php');
		$achievements_by_pupil_page = new \report_parentprogressview\output\achievements_page($USER, null, null); // default the earliest and latest for now
		$achievements_by_pupil = $achievements_by_pupil_page->prepare_data(false);

		require_once(dirname(__FILE__) . '/../output/behaviour_page.php');
		$behaviour_by_pupil_page = new \report_parentprogressview\output\behaviour_page($USER, null, null); // default the earliest and latest for now
		$behaviour_by_pupil = $behaviour_by_pupil_page->prepare_data(false);

                require_once(dirname(__FILE__) . '/../local/hub_api_request.php');


		$output = $this->get_documents_by_pupil_username($earliest_published_date, $latest_published_date, $include_hidden);

		// add other data
		//
		foreach($output as $key => &$pupil) {
			// match this $pupil to pupil in other arrays
			//
			//

			foreach($attendance_by_pupil->attendance_marks_by_pupil as $inner_key => &$att_pupil) {
				if ($att_pupil->user->id == $pupil->user->id) {
					$pupil_attendance = $att_pupil;
					break;
				}
			}
			foreach($achievements_by_pupil->achievements_by_pupil as $inner_key => &$ach_pupil) {

				if ($ach_pupil->user->id == $pupil->user->id) {
					$pupil_achievements = $ach_pupil;
					break;
				}
			}
			foreach($behaviour_by_pupil->behaviour_by_pupil as $inner_key => $beh_pupil) {
				if ($beh_pupil->user->id == $pupil->user->id) {
					$pupil_behaviour = $beh_pupil;
					break;
				}
			}

                        // does timetable exist? TODO improve performance
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
                        $request->add_meta_query('username', $pupil->user->username, '=');

                        try {
                            $result = $request->request();
                            if ($request->status == 200 && count($result) > 0) {
                                $pupil->timetable = true;
                            }
                            else {
                                $pupil->timetable = [];
                            }

                        } catch (Exception $e) {
                            $pupil->timetable = [];
                        }


			$pupil->attendance = $pupil_attendance;
			$pupil->achievements = $pupil_achievements;
			$pupil->behaviour = $pupil_behaviour;

		}


		return $output;
	}

	/**
	 * Retrieve a document object from its ID, if the current user is duly authorized to see it.
	 */
	public function get_document_by_id($id) {
		global $DB;

		$this->load_available_documents_for_user(strtotime('2015-01-01'));
		/* An arbitrary 'earliest published date' is used as the first argument here -- all we have
		   is the desired document ID. We don't know, or care, what the search term for the earliest
		   document was. The only stipulation is that we leave the 'latest published date' (second argument)
		   as the default, as the user isn't allowed to view a document that hasn't been published yet.
		   However, there's no problem with them viewing any valid document from the past to which they otherwise
		   have permission.
		*/

		if (count($this->documents) > 0) {
			// find document with ID -- TODO perhaps make this more efficient
			foreach($this->documents as $document) {
				if ($document->id == $id) {
					return $document;
				}
			}
		}

		return null;
	}

};
