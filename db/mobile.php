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

				),
				'init'       => 'init', // will call init() in classes/output/mobile.php's mobile class to initialise
				'styles'     => array(
					'url'     => 'report/parentprogressview/mobile/styles.css',
					'version' => @filemtime(dirname(__FILE__) . '/../mobile/styles.css')
				)
			)
                    ),
                    /* These language strings are passed to the mobile app and cached upon sign in */
		'lang' => array(
			array('pluginname', 'report_parentprogressview'),
			array('documentsfeaturename', 'report_parentprogressview'),
			array('achievementfeaturename', 'report_parentprogressview'),
			array('behaviourfeaturename', 'report_parentprogressview'),
			array('attendancefeaturename', 'report_parentprogressview'),
			array('pcntpresent', 'report_parentprogressview'),
			array('pcntauthorisedabsences', 'report_parentprogressview'),
			array('pcntunauthorisedabsences', 'report_parentprogressview'),
			array('pcntunexplainedabsences', 'report_parentprogressview'),
			array('pcntlatebeforereg', 'report_parentprogressview'),
			array('pcntlateafterreg', 'report_parentprogressview'),
			array('present', 'report_parentprogressview'),
			array('authorisedabsences', 'report_parentprogressview'),
			array('unauthorisedabsences', 'report_parentprogressview'),
			array('unexplainedabsences', 'report_parentprogressview'),
			array('latebeforereg', 'report_parentprogressview'),
			array('lateafterreg', 'report_parentprogressview'),
			array('attendancemarkstableheading', 'report_parentprogressview'),
			array('achievementpointslabel', 'report_parentprogressview'),
			array('achievementitemsheader', 'report_parentprogressview'),
			array('behaviourpointslabel', 'report_parentprogressview'),
			array('achievementotalsheader', 'report_parentprogressview'),
			array('noattendancesummaryplaceholder', 'report_parentprogressview'),
			array('noattendancesummary', 'report_parentprogressview'),
			array('attendancemarkstableheading', 'report_parentprogressview'),
			array('nodocumentstoview', 'report_parentprogressview'),
			array('noattachedpupils', 'report_parentprogressview'),
                        array('timetable_heading', 'report_parentprogressview'),
                        array('timetable_nocontent', 'report_parentprogressview'),
                        array('timetable_nopermission', 'report_parentprogressview'),
                        array('timetable_viewtimetablebutton', 'report_parentprogressview')
		)
	)

);
