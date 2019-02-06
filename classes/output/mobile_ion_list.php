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
		$ion_output = '';

		if (count($this->data) < 1) {
			return $ion_output;
		}

		$ion_output = '<ion-list>';


		foreach($this->data as &$item) {
			if (!($item instanceof stdClass)) {
				throw new \InvalidArgumentException('The data passed to the ion-list renderer was not an array of stdClass objects.');
			}


			$ion_output .= '<ion-item>';


			// extract keys
			foreach($this->keys as $key) {
				//$ion_output .= $key;
				//var_dump($item->$key);
				$ion_output .=  $item->$key . '&nbsp;&#8226;&nbsp;';
			}

			// strip last bullet character
			$ion_output = substr($ion_output, 0, strrpos($ion_output, '&#8226'));
			
			$ion_output .= '</ion-item>';
		}

		
		$ion_output .= '</ion-list>';

		return $ion_output;

	}

};
