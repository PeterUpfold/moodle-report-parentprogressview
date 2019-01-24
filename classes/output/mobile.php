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

namespace report_parentprogressview\output;

defined('MOODLE_INTERNAL') || die();

use context_module;
use report_parentprogressview_external;

/**
 * Mobile output class for Parent Progress View.
 *
 * @package report_parentprogressview
 * @author Test Valley School
 * 
 */
class mobile {

	/**
	 * Returns the documents view for the mobile app.
	 * @param array $args Arguments from tool_mobile_get_content web service
	 *
	 * @return array  HTML, JS and other data
	 */
	public static function mobile_documents_view($args) {
		global $OUTPUT, $USER, $DB, $CFG;

		$args = (object) $args;

		require_login(NULL, false, NULL);
		require_capability('report/parentprogressview:view', \context_system::instance());


		// get some $data
		\report_parentprogressview\event\report_viewed::create()->trigger(); //TODO mobile specific?
		$document_set = new \report_parentprogressview\local\document_set($USER);

		// create renderable
		$data = new \stdClass();

		// split documents by pupil
		$data->documents_by_pupil = $document_set->get_documents_by_pupil_username($document_set->earliest_published_date, $document_set->latest_published_date);	
		
		$data->link_documents_page = new \moodle_url('/report/parentprogressview/index.php');
		$data->link_behaviour_page = new \moodle_url('/report/parentprogressview/behaviour.php');
		$data->link_attendance_page = new \moodle_url('/report/parentprogressview/attendance.php');
		$data->link_achievement_page = new \moodle_url('/report/parentprogressview/achievements.php');

		$data->link_no_documents_help_page = $CFG->report_parentprogressview_link_no_documents_help_page;



		return array(
			'templates' => array(
				array(
					'id'   => 'main',
					'html' => $OUTPUT->render_from_template('report_parentprogressview/mobile_view_page', $data),
				),
			),
			'javascript' => '',
			'otherdata'  => '',
			'files'      => ''
		);

	}

};
