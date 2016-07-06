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
 * A class for a configurable date range form element.
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */

namespace report_parentprogressview\local;

require_once("$CFG->libdir/formslib.php");

use moodleform;

/**
 * A class for a configurable date range form element.
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */
class daterange_form extends moodleform {

	/**
	 *
	 */
	public function definition() {
		global $CFG;

		$mform = $this->_form;

		$mform->addElement('header', 'show_documents_published', get_string('daterangeintro', 'report_parentprogressview'));

		$date_sel_options = array(
			'startyear'      => 2012,
			'stopyear'       => (int) date('Y'),
		);

		$date_selectors[] =& $mform->createElement('date_selector', 'datefrom', get_string('from'), $date_sel_options);
		$date_selectors[] =& $mform->createElement('date_selector', 'dateto', get_string('to'), $date_sel_options);

		foreach($date_selectors as $element) {
			$mform->addElement($element);
		}

		$this->add_action_buttons(/* $cancel */ false, get_string('daterangeshowbutton', 'report_parentprogressview'));

	}

	/**
	 * Wrapper to call setDefault on the form object.
	 */
	public function setDefault($element, $default) {
		$this->_form->setDefault($element, $default);
	}

	/**
	 * 
	 */
	public function validation($data, $files) {
		return array();
	}

};

