<?php

/**
 * The report_parentprogressview report main render page.
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */



require(dirname(__FILE__).'/../../config.php');
require_once($CFG->libdir.'/adminlib.php');


admin_externalpage_setup('reportparentprogressview', '', null, '', array());
// admin_externalpage_setup does access validation checks for us

echo $OUTPUT->header();

// log -- note that 'report_viewed' event actually just means this page, not the viewing of a particular document
\report_parentprogressview\event\report_viewed::create()->trigger();

echo '<h2>Parent Progress View</h2>';


echo $OUTPUT->footer();

