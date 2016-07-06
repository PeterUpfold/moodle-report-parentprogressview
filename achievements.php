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
 * The report_parentprogressview page for showing achievements.
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */

require(dirname(__FILE__).'/../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once( dirname( __FILE__ ) . '/classes/output/achievements_page.php'); // autoloader much?

admin_externalpage_setup('report_parentprogressview_achievements', '', null, '', array()); // this does access checks

// Mustache template rendering -- see templates/index_page.mustache and classes/output/*
$title = get_string('pluginname', 'report_parentprogressview');
$pagetitle = $title;
$url = new moodle_url('/report/parentprogressview/achievements.php');
$PAGE->set_url($url);
$PAGE->set_title($title);
$PAGE->set_heading($title);

$output = $PAGE->get_renderer('report_parentprogressview');

echo $output->header();
echo $output->heading($pagetitle);

// log this event
\report_parentprogressview\event\achievement_viewed::create()->trigger();

// get earliest and latest dates if form has been submitted
$form = new \report_parentprogressview\local\daterange_form(null, null, 'post');
if ($data = $form->get_data()) {
	$earliest_date = intval($data->datefrom);
	$latest_date = intval($data->dateto);
}

if (!isset($earliest_date) || $earliest_date == 0) {
	$earliest_date = null; // null it out so that  default is used
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

// create renderable
$renderable = new report_parentprogressview\output\achievements_page($USER, $earliest_date, $latest_date);


// do the rendering business
echo $output->render($renderable);

echo $output->footer();

