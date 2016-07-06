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
 * Represents a published document.
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */

namespace report_parentprogressview\local;

/**
 * Represents a published document.
 * @package report_parentprogressview
 * @author Test Valley School
 */
class document {

	/**
	 * The auto incremented primary key in the database table for this document.
	 */
	public $id;

	/**
	 * The published document's on-disk filename, as captured by the uploader.
	 */
	public $filename;

	/**
	 * The username of the pupil to whom this document relates.
	 */
	public $pupil_username;

	/**
	 * The size of the document in bytes.
	 */
	public $length;


	/**
	 * The date and time that the document was uploaded into the system.
	 */
	public $date_added;


	/**
	 * The date and time that the document becomes available to the authorized parents.
	 */
	public $date_published;

	/**
	 * The formatted userdate() string that corresponds to the publication date of this document.
	 */
	public $date_published_userdate;


	/**
	 * Whether or not the document is hidden and should not appear regardless of publication date.
	 */
	public $hidden;

	/**
	 * The document's MIME type (e.g. application/pdf)
	 */
	public $mimetype;

	/**
	 * The document's SHA512 hash for duplicate detection.
	 */
	public $hash;

	/**
	 * The link (as a string, not a moodle_url) that points to this document.
	 */
	public $link;

	/**
	 * The link (as a string only) that points to the thumbnail.
	 */
	public $thumbnail_link;

	/**
	 * A reference to a database connection object so we can call methods that may need
	 * DB access directly on this object.
	 */
	public $hub_database_connection;


	/**
	 * The file extension of this document. Used to display a file type icon.
	 */
	public $file_extension;

	/**
	 * Intended to be called when report_parentprogressview\local\document_set
	 * loads several documents from the database and passes the raw database
	 * row's properties into this constructor
	 */
	public function __construct( $id, $filename, $pupil_username, $length, $date_added, $date_published, $hidden, $mimetype, $hash, &$hub_database_connection ) {
		$this->id = $id;
		$this->filename = $filename;
		$this->pupil_username = $pupil_username;
		$this->length = $length;

		$this->date_added = strtotime( $date_added . ' UTC' );
		$this->date_published = strtotime( $date_published . ' UTC' );
		
		$this->hidden = ($hidden == 1) ? true : false;

		$this->mimetype = $mimetype;
		$this->hash = $hash;
		
		if ( $hub_database_connection instanceof \report_parentprogressview\local\hub_database_connection ) {
			$this->hub_database_connection = $hub_database_connection;
		}

		$this->link = $this->generate_link();
		$this->thumbnail_link = $this->generate_thumbnail_link();

		$this->file_extension = pathinfo($filename, PATHINFO_EXTENSION);
		$this->date_published_userdate = userdate($this->date_published);

	}

	/**
	 * Return the bytestream of this document for output.
	 */
	public function get_bytes() {
                if (!($this->hub_database_connection->conn instanceof \mysqli) ) {
			throw new \Exception("Didn't have a connection to The Hub database at render time");
		}

		if (!is_numeric($this->id)) {
			throw new \Exception("The id of this document instance is not numeric.");
		}

		$stmt = $this->hub_database_connection->conn->prepare(
			"SELECT data FROM {$this->hub_database_connection->db_table} WHERE id = ?"
		);

		if (!$stmt) {
			throw new \Exception("Unable to prepare the query to get the data blob");
		}
		
		$stmt->bind_param( 'i', $this->id );

		$stmt->execute();
		$stmt->store_result();

		$stmt->bind_result($bytes);
		$stmt->fetch();

		$stmt->close();
		return $bytes;

	}

	/**
	 * Return the bytestream of this document's JPEG thumbnail for output.
	 */
	public function get_thumbnail_bytes() {
                if (!($this->hub_database_connection->conn instanceof \mysqli) ) {
			throw new \Exception("Didn't have a connection to The Hub database at render time");
		}

		if (!is_numeric($this->id)) {
			throw new \Exception("The id of this document instance is not numeric.");
		}

		$stmt = $this->hub_database_connection->conn->prepare(
			"SELECT thumbnail FROM {$this->hub_database_connection->db_table} WHERE id = ?"
		);

		if (!$stmt) {
			throw new \Exception("Unable to prepare the query to get the thumbnail data blob");
		}
		
		$stmt->bind_param( 'i', $this->id );

		$stmt->execute();
		$stmt->store_result();

		$stmt->bind_result($bytes);
		$stmt->fetch();

		$stmt->close();
		return $bytes;

	}

	/**
	 * Return a moodle_url that points to this document (document.php?id=[id]). Also set the link property
	 * on this object to a string representation of that moodle_url.
	 */
	public function generate_link() {
		$link = new \moodle_url('/report/parentprogressview/document.php', array('id' => intval($this->id)));
		$this->link = $link->__toString();
		return $link;
	}

	/**
	 * Return a moodle_url that points to the thumbnail (thumbnail.php?id=[id]). Also sets the thumbnail_link
	 * property on this object to the string representation of this moodle_url.
	 */
	public function generate_thumbnail_link() {
		$link = new \moodle_url('/report/parentprogressview/thumbnail.php', array('id' => intval($this->id)));
		$this->thumbnail_link = $link->__toString();
		return $link;
	}

	/**
	 * Return a string that is sanitised and suitable for outputting in an HTTP header.
	 */
	public function sanitise_string_for_header($string) {
		return preg_replace('/[^\w\d\s\/\-_\.]/', '', $string);
	}

};
