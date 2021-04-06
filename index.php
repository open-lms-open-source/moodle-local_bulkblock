<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Bulk block creator
 * @package   local_bulkblock
 * @copyright Copyright (c) 2021 Open LMS (https://www.openlms.net)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__).'/../../config.php');

$categoryid = required_param('id', PARAM_INT);
$context = context_coursecat::instance($categoryid);
$category = core_course_category::get($categoryid);
$site = get_site();

require_login();
require_capability('local/bulkblock:use', $context);

$PAGE->set_category_by_id($categoryid);
$PAGE->set_pagetype('course-index-category');
$PAGE->set_pagelayout('coursecategory');
$formname = get_string('pluginname', 'local_bulkblock');
$title = format_string($site->fullname).': '.format_string($category->name) . ": $formname";

$pageurl = new moodle_url('/local/bulkblock/index.php', array('id' => $categoryid));
$PAGE->set_url($pageurl);
$PAGE->set_title($title);
$PAGE->set_heading($title);

$renderer = $PAGE->get_renderer('local_bulkblock');
$controller = new \local_bulkblock\controller($category, $renderer);
$controller->execute();
