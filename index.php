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
 * The report_parentprogressview report main  page.
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */



require(dirname(__FILE__).'/../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once( dirname( __FILE__ ) . '/classes/output/index_page.php' ); // autoloader doesn't seem to want to play ball

admin_externalpage_setup('report_parentprogressview_main', '', null, '', array());
// admin_externalpage_setup does access validation checks for us

// Mustache template rendering -- see templates/index_page.mustache and classes/output/*
$title = get_string('pluginname', 'report_parentprogressview');
$pagetitle = $title;
$url = new moodle_url('/report/parentprogressview/index.php');
$PAGE->set_url($url);
$PAGE->set_title($title);
$PAGE->set_heading($title);

$output = $PAGE->get_renderer('report_parentprogressview');

echo $output->header();
echo $output->heading($pagetitle);

// log -- note that 'report_viewed' event actually just means this page, not the viewing of a particular document
\report_parentprogressview\event\report_viewed::create()->trigger();

// get data to pass to the renderable
$document_set = new report_parentprogressview\local\document_set($USER);

// get earliest and latest dates if form has been submitted
$form = new \report_parentprogressview\local\daterange_form(null, null, 'post');
if ($data = $form->get_data()) {
	$earliest_date = intval($data->datefrom);
	$latest_date = intval($data->dateto);
}

if (!isset($earliest_date) || $earliest_date == 0) {
	$earliest_date = null; // null it out so that $document_set uses its default
}

if (isset($latest_date) && is_numeric($latest_date) && $latest_date > 0) {
	// disallow a latest date in the future
	if ($latest_date > time()) {
		$latest_date = time();
	}
}
else {
	$latest_date = null;
}

$document_set->prepare_date_range($earliest_date, $latest_date);

// create renderable
$renderable = new \report_parentprogressview\output\index_page($document_set);

// do the rendering business
echo $output->render($renderable);

echo $output->footer();

