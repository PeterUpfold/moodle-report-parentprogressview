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

defined('MOODLE_INTERNAL') || die();

use plugin_renderer_base;

/**
 * Output renderer to dispatch renderable data to template.
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */
class renderer extends plugin_renderer_base {

	/**
	 * Render the index page using the mustache template
	 * See @link{https://docs.moodle.org/dev/Output_API}
	 */
	public function render_index_page($page) {
		$data = $page->export_for_template($this);
		return parent::render_from_template('report_parentprogressview/index_page', $data);
	}

	/**
	 * Render the achievements page using the mustache template
	 * See @link{https://docs.moodle.org/dev/Output_API}
	 */
	public function render_achievements_page($page) {
		$data = $page->export_for_template($this);
		return parent::render_from_template('report_parentprogressview/achievements_page', $data);
	}

	/**
	 * Render the attendance page using the mustache template
	 * See @link{https://docs.moodle.org/dev/Output_API}
	 */
	 public function render_attendance_page($page) {
		$data = $page->export_for_template($this);
		return parent::render_from_template('report_parentprogressview/attendance_page', $data);
	 }

	/**
	 * Render the behaviour page using the mustache template
	 * See @link{https://docs.moodle.org/dev/Output_API}
	 */
	 public function render_behaviour_page($page) {
		$data = $page->export_for_template($this);
		return parent::render_from_template('report_parentprogressview/behaviour_page', $data);
	 }


};
