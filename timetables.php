<?php

/*
Parent Progress View, a module for Moodle to allow the viewing of documents and pupil data by authorised parents.
    Copyright (C) 2016-19 Test Valley School.

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
 * The report_parentprogressview report page for viewing timetables for all attached pupils.
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */



require(dirname(__FILE__).'/../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once( dirname( __FILE__ ) . '/classes/output/timetables_page.php' ); // autoloader doesn't seem to want to play ball

admin_externalpage_setup('report_parentprogressview_timetables', '', null, '', array());
// admin_externalpage_setup does access validation checks for us

// Mustache template rendering -- see templates/timetables_page.mustache and classes/output/*
$title = get_string('pluginname', 'report_parentprogressview');
$pagetitle = $title;
$url = new moodle_url('/report/parentprogressview/timetables.php');
$PAGE->set_url($url);
$PAGE->set_title($title);
$PAGE->set_heading($title);

$output = $PAGE->get_renderer('report_parentprogressview');

echo $output->header();
echo $output->heading($pagetitle);

// log 
\report_parentprogressview\event\timetables_viewed::create()->trigger();

// create renderable
$renderable = new \report_parentprogressview\output\timetables_page($USER);

// do the rendering business
echo $output->render($renderable);

echo $output->footer();

