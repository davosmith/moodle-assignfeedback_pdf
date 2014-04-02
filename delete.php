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
 * Delete the response from the teacher + all comments
 *
 * @package   assignfeedback_pdf
 * @copyright 2014 Davo Smith
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__).'/../../../../config.php');
global $DB, $PAGE, $CFG;
require_once($CFG->dirroot.'/mod/assign/locallib.php');
require_once($CFG->dirroot.'/mod/assign/submission/pdf/lib.php');

$id = required_param('id', PARAM_INT);
$submissionid = required_param('submissionid', PARAM_INT);
$returnparams = required_param('returnparams', PARAM_TEXT);

$cm = get_coursemodule_from_id('assign', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);

$url = new moodle_url('/mod/assign/feedback/pdf/delete.php', array('id' => $id, 'submissionid' => $submissionid,
                                                                   'returnparams' => $returnparams));
$PAGE->set_url($url);
require_login($course, false, $cm);

$context = context_module::instance($cm->id);

$assignment = new assign($context, $cm, $course);
$feedbackpdf = new assign_feedback_pdf($assignment, 'feedback_pdf');
$submission = $DB->get_record('assign_submission', array('id' => $submissionid, 'assignment' => $assignment->get_instance()->id),
                              '*', MUST_EXIST);

$feedbackpdf->delete_feedback($submission);