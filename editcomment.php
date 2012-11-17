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
 * The entry point for annotating a PDF
 *
 * @package   mod_assign
 * @subpackage assignsubmission_pdf
 * @copyright 2012 Davo Smith
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__).'/../../../../config.php');
global $CFG, $DB, $PAGE;
require_once($CFG->dirroot.'/mod/assign/locallib.php');
require_once($CFG->dirroot.'/mod/assign/submission/pdf/lib.php');

$id   = required_param('id', PARAM_INT);
$userid = optional_param('userid', 0, PARAM_INT);
$pageno = optional_param('pageno', 1, PARAM_INT);
$action = optional_param('action', null, PARAM_TEXT);
$rownum = optional_param('rownum', null, PARAM_INT);

$url = new moodle_url('/mod/assign/feedback/pdf/editcomment.php', array('userid'=>$userid, 'pageno'=>$pageno, 'id' => $id));
if (!is_null($rownum)) {
    $url->param('rownum', $rownum);
}
$cm = get_coursemodule_from_id('assign', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);

$PAGE->set_url($url);
require_login($course, false, $cm);

$context = context_module::instance($cm->id);
require_capability('mod/assign:grade', $context);

$assignment = new assign($context, $cm, $course);
$submissionpdf = new assign_feedback_pdf($assignment, 'feedback_pdf');

if ($action == 'showprevious') {
    $submissionpdf->show_previous_comments($userid);
} elseif ($action == 'showpreviouspage') {
    $submissionpdf->edit_comment_page($userid, $pageno, false);
} else {
    $submissionpdf->edit_comment_page($userid, $pageno);
}
