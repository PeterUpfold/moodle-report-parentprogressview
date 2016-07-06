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
 * A class for a renderable web page table for showing details
 * of behaviour incidents.
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */

namespace report_parentprogressview\local;
require_once(dirname(__FILE__) . '/../../../../lib/tablelib.php');


/**
 * A class for a renderable web page table for showing details
 * of behaviour incidents.
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */
class behaviour_table extends \flexible_table {

	/**
	 * Constructor
	 * @param int $uniqueid Tables must have a unique ID used as a key when storing table properties in the session.
	 * @param string $baseurl The URL of the page that produces this instance of this table. As a string, not a moodle_url.
	 */
	public function __construct($uniqueid, $baseurl) {
		parent::__construct($uniqueid);

		$this->define_baseurl($baseurl);

		$this->define_columns(array(
			'date',
			'type',
			'lesson_period',
			'lesson_class',
			'lesson_subject',
			'points'
		) );

		$this->define_headers(array(
			get_string('date', 'report_parentprogressview'),
			get_string('type', 'report_parentprogressview'),
			get_string('lesson_period', 'report_parentprogressview'),
			get_string('lesson_class', 'report_parentprogressview'),
			get_string('lesson_subject', 'report_parentprogressview'),
			get_string('points', 'report_parentprogressview')
		) );

		$this->set_attribute('class', 'behaviour'); 

	}

	/**
	 * Given an array of objects parsed from a JSON/REST request to The Hub's API, fill
	 * this flexible_table with the rows of data.
	 */
	public function fill_table_with_json_data($jsondata) {
		if (is_array($jsondata) && count($jsondata) > 0) {

			foreach($jsondata as $key => $item) {

				$row = array();

				if ( 	!property_exists($item, 'incident_date') ||
					!property_exists($item, 'type') ||
					!property_exists($item, 'lesson_period') ||
					!property_exists($item, 'lesson_class') ||
					!property_exists($item, 'lesson_subject') ||
					!property_exists($item, 'points')
				) {
					// skip, as this is not well-formed
					continue;
				}

				$row[0] = userdate(strtotime($item->incident_date), get_string('strftimedateshort', 'langconfig'));
				$row[1] = $item->type;
				$row[2] = $item->lesson_period;
				$row[3] = $item->lesson_class;
				$row[4] = $item->lesson_subject;
				$row[5] = $item->points;
				
				$this->add_data($row);

			}
		}
	}

	/**
	 * Important to override this, even thought it is not part of the public API.
	 * We want a much nicer message than a massive "Nothing to display".
	 */
	public function print_nothing_to_display() {
		echo '<div class="alert alert-info">';
		echo get_string('nobehaviourinrange', 'report_parentprogressview');
		echo '</div>';
	}

};
