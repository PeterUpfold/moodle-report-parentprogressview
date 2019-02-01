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
 * Define traditional Moodle Web Services exposed by the plugin. The functions that 
 * implement these are in external.php
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */

namespace report_parentprogressview;

defined('MOODLE_INTERNAL') || die();

/**
 * This is the implementation of various external web services for Parent Progress View,
 * used by the mobile app.
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */
class external extends \external_api {

	/**
	 * Return the parameters for the get_document method.
	 * @return \external_function_parameters
	 */
	public static function get_document_parameters() {
		return new \external_function_parameters(
			array(
				'id' => new \external_value(PARAM_INT, 'The document ID to view', VALUE_REQUIRED)
			)
		);
	}

	/**
	 * Describe the return values of get_document.
	 *
	 * @return \external_single_structure
	 */
	public static function get_document_returns() {
		return new \external_single_structure(
			array(
				'document' => new \external_value(PARAM_RAW, 'byte array of the document'),
				'mimetype' => new \external_value(PARAM_TEXT, 'mimetype of the document'),
				'id'       => new \external_value(PARAM_INT, 'document\'s unique internal identifier'),
			)
		);
	}

	/**
	 * Retrieve the bytes of the document with the given ID, if the logged in user is permitted to do so.
	 *
	 * @return array 
	 */
	public static function get_document($id) {
		global $USER;

		if ((int)$USER->id < 1) {
			throw new \Exception(get_string('apinotloggedin', 'report_parentprogressview'));
		}

		$params = self::validate_parameters(self::get_document_parameters(), array('id' => $id));
		
		$document_set = new \report_parentprogressview\local\document_set($USER);
		$document = $document_set->get_document_by_id($id);

		if (NULL == $document) {
			throw new \Exception(get_string('nopermission', 'report_parentprogressview' ));	
		}

		return array('document' => base64_encode($document->get_bytes()), 'mimetype' => $document->mimetype, 'id' => $document->id);
	}

};
