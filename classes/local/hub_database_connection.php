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
 * Represents a connection to the database on The Hub, from which reports
 * are pulled.
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */

namespace report_parentprogressview\local;

/**
 * Represents a connection to the database on The Hub, from which reports
 * are pulled.
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */
class hub_database_connection {

	/**
	 * The internal MySQLi object we are encapsulating
	 */
	public $conn;

	/**
	 * Database username. Populated by load_config()
	 */
	protected $db_user;

	/**
	 * Database password. Populated by load_config()
	 */
	protected $db_pass;

	/**
	 * Database host. Populated by load_config()
	 */
	protected $db_host;


	/**
	 * Database name. Populated by load_config()
	 */
	protected $db_name;

	/**
	 * Database table. Populated by load_config()
	 */
	public $db_table;


	/**
	 * Create the connection, pulling configuration in by calling get_config
	 */
	public function __construct() {
		$this->get_config();
		
		$this->conn = new \mysqli( $this->db_host, $this->db_user, $this->db_pass, $this->db_name );

	}

	/**
	 * Access the credentials from the config file and load them into
	 * this class.
	 */

	protected function get_config() {
		global $CFG;

		if (!isset($CFG)) {
			throw new \Exception("Unable to get Moodle configuration.");
			return;
		}

		if (	!property_exists($CFG, 'report_parentprogressview_documents_dbuser') ||
			!property_exists($CFG, 'report_parentprogressview_documents_dbpass') ||
			!property_exists($CFG, 'report_parentprogressview_documents_dbhost') ||
			!property_exists($CFG, 'report_parentprogressview_documents_dbname') ||
			!property_exists($CFG, 'report_parentprogressview_documents_dbtable')
		) {
			throw new \Exception('The configuration settings for Parent Progress View could not be found. Verify that the plugin is installed correctly.');
		}

		if (empty($CFG->report_parentprogressview_documents_dbuser)) {
			\debugging('No username is set for database access for retrieving documents. Normally a username for the database should be configured in this plugin\'s settings.', DEBUG_NORMAL);
		}
		if (empty($CFG->report_parentprogressview_documents_dbpass)) {
			\debugging('No password is set for database access for retrieving documents. Normally a password for the database should be configured in this plugin\'s settings.', DEBUG_NORMAL);
		}
		if (empty($CFG->report_parentprogressview_documents_dbhost)) {
			\debugging('No database host is set for retrieving documents. Normally a database host should be configured in this plugin\'s settings, even if just \'localhost\'.', DEBUG_NORMAL);
		}
		if (empty($CFG->report_parentprogressview_documents_dbname)) {
			\debugging('No database name is set for retrieving documents. Normally a database name should be configured in this plugin\'s settings.', DEBUG_NORMAL);
		}
		if (empty($CFG->report_parentprogressview_documents_dbtable)) {
			\debugging('No database table is set for retrieving documents. Normally a database table should be configured in this plugin\'s settings.', DEBUG_NORMAL);
		}

		$this->db_user = $CFG->report_parentprogressview_documents_dbuser;
		$this->db_pass = $CFG->report_parentprogressview_documents_dbpass;
		$this->db_host = $CFG->report_parentprogressview_documents_dbhost;
		$this->db_name = $CFG->report_parentprogressview_documents_dbname;
		$this->db_table = $CFG->report_parentprogressview_documents_dbtable;

	}

};


