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
 * Output renderable (handler to set up data) for index page mustache template.
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
class index_page implements renderable, templatable {

	/**
	 * The class constructor receives the document_set object, and pulls out the array of document
	 * objects (which should be easy enough to access properties of in the template).
	 */
	//not currently using public $documents;

	/**
	 * The document_set object we are passed in the constructor.
	 */
	public $document_set;

	/**
	 * The class constructor should receive any information that needs to be passed to the template at rendertime.
	 */
	public function __construct(\report_parentprogressview\local\document_set $document_set) {
		$this->document_set = $document_set;
	}

	/**
	 * Export the data for use in the Mustache template.
	 */
	public function export_for_template(renderer_base $output) {
		global $CFG;
		$data = new stdClass();

		// split documents by pupil
		$data->documents_by_pupil = $this->document_set->get_documents_by_pupil_username($this->document_set->earliest_published_date, $this->document_set->latest_published_date);	
		
		$data->link_documents_page = new \moodle_url('/report/parentprogressview/index.php');
		$data->link_behaviour_page = new \moodle_url('/report/parentprogressview/behaviour.php');
		$data->link_attendance_page = new \moodle_url('/report/parentprogressview/attendance.php');
		$data->link_achievement_page = new \moodle_url('/report/parentprogressview/achievements.php');

		$data->link_no_documents_help_page = $CFG->report_parentprogressview_link_no_documents_help_page;

		require_once( dirname(__FILE__) . '/../local/daterange_form.php');
		$form = new \report_parentprogressview\local\daterange_form(null, null, 'post');
		$form->setDefault('datefrom', strtotime($this->document_set->earliest_published_date));
		$form->setDefault('dateto',  strtotime($this->document_set->latest_published_date));
		$data->daterange_form = $form->render();

		return $data;
	}

};
