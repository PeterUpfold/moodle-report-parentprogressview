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
 * The report_parentprogressview achievement viewed event.
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */

namespace report_parentprogressview\event;

defined('MOODLE_INTERNAL') || die();

/**
 * The report_parentprogressview achievement viewed class. This allows the logging of when a user visits the achievements page
 * and browses through the date range.
 *
 * @package report_parentprogressview
 */

class achievement_viewed extends \core\event\base {

	/**
	 * Object representing the user who triggered the event.
	 */
	protected $user;

	/**
	 * The username of the user to which this achievement pertains.
	 */
	protected $pupil_username;

	/**
	 * The selected date range start when this achievements page was viewed.
	 */
	protected $date_range_start;

	/**
	 * The selected date range end when this achievements page was viewed.
	 */
	protected $date_range_end;
	//TODO get and store this information -- it is not yet implemented that we know the date ranges viewed


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
		return get_string('eventachievementviewed', 'report_parentprogressview');
	}

	/**
	 * Returns description of the event.
	 * 
	 * @return string
	 */

	 public function get_description() {
		return sprintf(get_string('eventachievementvieweddescription', 'report_parentprogressview'),
			$this->userid,
			$this->get_data()['other']['pupil_username']
			);
	 }

	 public function get_url() {
		return $this->get_data()['other']['link_as_moodle_url'];
	 }


	 public static function get_other_mapping() {
		return false;
	 }



};

