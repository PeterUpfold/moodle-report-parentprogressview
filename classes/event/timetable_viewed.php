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
 * The report_parentprogressview timetable viewed event. This is logged when an individual timetable is viewed. 
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */

namespace report_parentprogressview\event;

defined('MOODLE_INTERNAL') || die();

/**
 * The report_parentprogressview timetable viewed event. This is logged when an individual timetable is viewed. 
 *
 * @package report_parentprogressview
 */

class timetable_viewed extends \core\event\base {

	/**
	 * Object representing the user who triggered the event.
	 */
	protected $user;


	/**
	 * Initialize some basic event data
	 *
	 * @return void
	 */
	protected function init() {
		global $DB;

		$this->data['crud'] = 'r';
		$this->data['edulevel'] = self::LEVEL_OTHER;
		$this->context = \context_system::instance();

	}


	/**
	 * Return localised event name
	 *
	 * @return string
	 */

	public static function get_name() {
		return get_string('eventtimetableviewed', 'report_parentprogressview');
	}

	/**
	 * Returns description of the event.
	 * 
	 * @return string
	 */

	 public function get_description() {
		return sprintf(get_string('eventtimetablevieweddescription', 'report_parentprogressview'),
			$this->userid,
                        $this->get_data()['other']['pupil_username']
			);
	 }

	 public function get_url() {
		return new \moodle_url('/report/parentprogressview/timetable.php');
	 }


	 public static function get_other_mapping() {
		return false;
	 }



};

