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
 * Define mobile app support for this plugin.
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */


$addons = array(
	'report_parentprogressview' => array(
		'handlers' => array(
			'documents' => array(
				'displaydata' => array(
					'icon'  => 'paper',
					'class' => '',
					'title' => get_string('pluginname', 'report_parentprogressview'),
				),
				'delegate'    => 'CoreMainMenuDelegate', // add to main app menu
				'method'      => 'mobile_documents_view',
				'offlinefunctions' => array(
					'mobile_documents_view' => array(),

				)
			)
		),
		'lang' => array(
			array('pluginname', 'report_parentprogressview'),
		)
	)

);
