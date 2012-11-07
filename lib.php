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
 * Library functions for the PDF feedback assignment plugin
 *
 * @package   assignfeedback_pdf
 * @copyright 2012 Davo Smith
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

define('ASSIGNFEEDBACK_PDF_FA_IMAGE', 'feedback_pdf_image'); // Images generated from each page of the PDF
define('ASSIGNFEEDBACK_PDF_FA_RESPONSE', 'feedback_pdf_response'); // Response generated once annotation is complete

define('ASSIGNFEEDBACK_PDF_FILENAME', 'response.pdf');

function assignfeedback_file_pluginfile($course, $cm, context $context, $filearea, $args, $forcedownload) {
    return false;
}