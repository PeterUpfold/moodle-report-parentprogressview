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
 * Create <ion-list> Ionic markup from the source JSON data for use in the template. This
 * is for the mobile app.
 * 
 * @package report_parentprogressview
 * @author Test Valley School
 */

namespace report_parentprogressview\output;

use stdClass;

/**
 * Create <ion-list> Ionic markup from the source JSON data for use in the template. This
 * is for the mobile app.
 * 
 * @package report_parentprogressview
 * @author Test Valley School
 */
class mobile_ion_list {

	/**
	 * Data from which to create an Ionic list.
	 *
	 * @var array
	 */
	public $data = array();


	/**
	 * An array of strings that describe the keys for which you would like to render
	 * values in the <ion-list>.
	 */
	public $keys = array();

	/**
	 * Pass some unserialised JSON data.
	 *
	 * @param array $json_data Unserialised JSON data
	 * @param array An array of strings that describe the keys for which you would like to render values in the <ion-list>
	 *
	 */
	public function __construct($json_data, $keys) {
		$this->data = $json_data;
		$this->keys = $keys;
	}


	/**
	 * Render an <ion-list>
	 *
	 * @return string
	 */
	public function render() {
		$output = '';

		if (count($this->data) < 1) {
			return $output;
		}

		$output = '<ion-list>';


		foreach($this->data as $item) {
			if (!($item instanceof stdClass)) {
				throw new \InvalidArgumentException('The data passed to the ion-list renderer was not an array of stdClass objects.');
			}

			$output .= '<ion-item>';


			// extract keys
			foreach($this->keys as $key) {
				$output .= $key;
				$output .= \s( $item->$key ) . ' · ';
			}
			
			$output .= '</ion-item>';
		}

		
		$output .= '</ion-list>';

		return $output;

	}

};
