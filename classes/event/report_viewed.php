<?php

/**
 * The report_parentprogressview report viewed event.
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */

namespace report_parentprogressview\event;

defined('MOODLE_INTERNAL') || die();

/**
 * The report_parentprogressview report viewed class. Note that in this context, report refers to accessing the page at all, not an individual document.
 *
 * @package report_parentprogressview
 */

class report_viewed extends \core\event\base {

	/**
	 * Initialize some basic event data
	 *
	 * @return void
	 */
	protected function init() {
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
		return get_string('eventreportviewed', 'report_parentprogressview');
	}

	/**
	 * Returns description of the event.
	 * 
	 * @return string
	 */

	 public function get_description() {
		return sprintf(get_string('eventreportvieweddescription', 'report_parentprogressview'), $this->userid);
	 }

	 /**
	  * Returns the URL to the report module.
	  *
	  * @return \moodle_url
	  */

	 public function get_url() {
		return new \moodle_url('/report/parentprogressview/index.php');
	 }

	 public static function get_other_mapping() {
		return false;
	 }



};

