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
 * Create <ion-grid> Ionic markup from the source JSON data for use in the template. This
 * is for the mobile app.
 * 
 * @package report_parentprogressview
 * @author Test Valley School
 */

namespace report_parentprogressview\output;

use stdClass;

/**
 * Create <ion-grid> Ionic markup from the source JSON data for use in the template. This
 * is for the mobile app.
 * 
 * @package report_parentprogressview
 * @author Test Valley School
 */
class mobile_ion_grid {

	/**
	 * Data from which to create an Ionic grid.
	 *
	 * @var array
	 */
	public $data = array();


	/**
	 * An array of strings that describe the keys for which you would like to render
	 * values in the <ion-grid>.
	 */
	public $keys = array();

	/**
	 * Pass some unserialised JSON data.
	 *
	 * @param array $json_data Unserialised JSON data
	 * @param array An array of strings that describe the keys for which you would like to render values in the <ion-grid>
	 *
	 */
	public function __construct($json_data, $keys) {
		$this->data = $json_data;
		$this->keys = $keys;
	}


	/**
	 * Render an <ion-grid>
	 *
	 * @return string
	 */
	public function render() {
		$ion_output = '';

		if (count($this->data) < 1) {
			$ion_output .= '<ion-row><ion-col>' . get_string('nodatainmobilegridview', 'report_parentprogressview') . '</ion-col></ion-row>';
			return $ion_output;
		}

		$ion_output = '<ion-grid>';


		//$ion_output .= '<ion-row>Data count: ' . count($this->data) . '</ion-row>';
		foreach($this->data as &$item) {
			if (!($item instanceof stdClass)) {
				throw new \InvalidArgumentException('The data passed to the ion-grid renderer was not an array of stdClass objects.');
			}


			$ion_output .= '<ion-row>';

			// extract keys
			foreach($this->keys as $key) {

				$content = $item->$key;

				if (strpos($key, 'date') !== false) {
					$content = userdate(strtotime($content), get_string('strftimedateshort'));
				}

				$ion_output .=  '<ion-col>' . \s($content) . '</ion-col>';
			}

			$ion_output .= '</ion-row>';
		}

		
		$ion_output .= '</ion-grid>';

		return $ion_output;

	}

};
