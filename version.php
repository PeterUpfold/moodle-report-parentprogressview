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
 * Module version.nformation for Parent Progress View. (Viewing extracted documents and statistics from MIS)
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */
 
defined('MOODLE_INTERNAL') || die();

$plugin->version = 2019020103;			// YYYYMMDDXX where XX is an incrementing revision number for that day
$plugin->requires  = 2016052300;			// required Moodle version.tring
$plugin->component = 'report_parentprogressview';	// Full name of the plugin
$plugin->maturity  = MATURITY_STABLE;			// why not?
$plugin->release   = 'v1.2';				// friendly version.umber
$plugin->cron      = 0;
