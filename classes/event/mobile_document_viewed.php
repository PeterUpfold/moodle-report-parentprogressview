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
 * The report_parentprogressview document viewed event.
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */

namespace report_parentprogressview\event;

defined('MOODLE_INTERNAL') || die();

/**
 * The report_parentprogressview document viewed class. This allows the logging of when a user actually downloads/views the document
 * to which they have access using the mobile app.
 *
 * @package report_parentprogressview
 */

class mobile_document_viewed extends \core\event\base {

	/**
	 * Object representing the user who triggered the event.
	 */
	protected $user;

	/**
	 * The filename of the document that was viewed.
	 */
	protected $filename;

	/**
	 * The username of the user to which this document pertains.
	 */
	protected $pupil_username;

	/**
	 * A moodle_url object that points to the document view URL for the document
	 * that was viewed.
	 */
	protected $link_as_moodle_url;

	/**
	 * The ID of the document that was viewed in the database on The Hub.
	 */
	protected $document_id;

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
		return get_string('eventmobiledocumentviewed', 'report_parentprogressview');
	}

	/**
	 * Returns description of the event.
	 * 
	 * @return string
	 */

	 public function get_description() {
		return sprintf(get_string('eventmobiledocumentvieweddescription', 'report_parentprogressview'),
			$this->userid,
			$this->get_data()['other']['document_id'],
			$this->get_data()['other']['filename']
			);
	 }

	 public function get_url() {
		return $this->get_data()['other']['link_as_moodle_url'];
	 }

	 /**
	  * Create this event using the metadata from the attached document object.
	  */
	 public static function create_from_document(\report_parentprogressview\local\document $document) {

		// extract metadata
		

		$data = array(
			'other' => array(

				'filename'           => $document->filename,
				'pupil_username'     => $document->pupil_username,
				'link_as_moodle_url' => $document->generate_link(),
				'document_id'        => $document->id
			)
		);
		// NOT SURE how i'm supposed to grab and store information -- the above doesn't seem helpful

		$event = self::create($data);
		return $event;

	 }



	 public static function get_other_mapping() {
		return false;
	 }



};

