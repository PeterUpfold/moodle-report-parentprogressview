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
 * of attendance marks.
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */

namespace report_parentprogressview\local;
require_once(dirname(__FILE__) . '/../../../../lib/tablelib.php');


/**
 * A class for a renderable web page table for showing details
 * of attendance marks.
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */
class attendance_marks_table extends \flexible_table {

	/**
	 * Constructor
	 * @param int $uniqueid Tables must have a unique ID used as a key when storing table properties in the session.
	 * @param string $baseurl The URL of the page that produces this instance of this table. As a string, not a moodle_url.
	 */
	public function __construct($uniqueid, $baseurl) {
		parent::__construct($uniqueid);

		$this->define_baseurl($baseurl);

		$this->define_columns(array(
			'mark_date',
			'am_pm',
			'mark',
			'mark_description',
		) );

		$this->define_headers(array(
			get_string('mark_date', 'report_parentprogressview'),
			get_string('am_pm', 'report_parentprogressview'),
			get_string('mark', 'report_parentprogressview'),
			get_string('mark_description', 'report_parentprogressview'),
		) );

		$this->set_attribute('class', 'attendance_marks'); 

	}

	/**
	 * Given an array of objects parsed from a JSON/REST request to The Hub's API, fill
	 * this flexible_table with the rows of data.
	 */
	public function fill_table_with_json_data($jsondata) {
		if (is_array($jsondata) && count($jsondata) > 0) {

			foreach($jsondata as $key => $item) {

				$row = array();

				if ( 	!property_exists($item, 'mark_date') ||
					!property_exists($item, 'am_pm') ||
					!property_exists($item, 'mark') ||
					!property_exists($item, 'mark_description')
				) {
					// skip, as this is not well-formed
					continue;
				}

				$row[0] = userdate(strtotime($item->mark_date), get_string('strftimedateshort', 'langconfig'));
				$row[1] = $item->am_pm;
				$row[2] = $item->mark;
				$row[3] = $item->mark_description;

				// fix display of Present PM marks, where the mark code is not recorded as a single backslash (stripslashes in WP on The Hub?)
				if (strlen($row[2]) == 0 && 'Present (PM)' == $item->mark_description) {
					$row[2] = '\\';
				}
				
				$this->add_data($row);

			}
		}
	}

	/**
	 * Important to override this, even thought it is not part of the public API.
	 * We want a much nicer message than a massive "Nothing to display".
	 */
	public function print_nothing_to_display() {
		echo '<div id="alert alert-notice">';
		echo get_string('noattendancemarksinrange', 'report_parentprogressview');
		echo '</div>';
	}

};
