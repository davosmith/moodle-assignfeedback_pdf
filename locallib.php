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
 * This file contains the definition for the library class for PDF feedback plugin
 *
 *
 * @package   assignfeedback_pdf
 * @copyright 2012 Davo Smith
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
/**
 * File areas for file feedback assignment
 */
define('ASSIGNFEEDBACK_PDF_FILEAREA', 'feedback_files');
define('ASSIGNFEEDBACK_PDF_MAXSUMMARYFILES', 5);

/**
 * library class for pdf feedback plugin extending feedback plugin base class
 *
 * @package   asignfeedback_pdf
 * @copyright 2012 Davo Smith
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class assign_feedback_pdf extends assign_feedback_plugin {

    /**
     * Get the name of the file feedback plugin
     * @return string
     */
    public function get_name() {
        return get_string('pdf', 'assignfeedback_pdf');
    }

    /**
     * Get form elements for grading form
     *
     * @param stdClass $grade
     * @param MoodleQuickForm $mform
     * @param stdClass $data
     * @return bool true if elements were added to the form
     */
    public function get_form_elements($grade, MoodleQuickForm $mform, stdClass $data) {

        // TODO davo - add a real annotate link here + link to download PDF (once generated)
        $mform->addElement('static', '', 'Annotate your PDF online here', 'Really!!');

        return true;
    }

    /**
     * Display the list of files  in the feedback status table
     *
     * @param stdClass $grade
     * @param bool $showviewlink - Set to true to show a link to see the full list of files
     * @return string
     */
    public function view_summary(stdClass $grade, & $showviewlink) {

        // TODO davo - add a real annotate link here + link to download PDF (once generated)

        return 'Click here to annotate the assignment';
        /*
        $count = $this->count_files($grade->id, ASSIGNFEEDBACK_FILE_FILEAREA);
        // show a view all link if the number of files is over this limit
        $showviewlink = $count > ASSIGNFEEDBACK_FILE_MAXSUMMARYFILES;

        if ($count <= ASSIGNFEEDBACK_FILE_MAXSUMMARYFILES) {
            return $this->assignment->render_area_files('assignfeedback_file', ASSIGNFEEDBACK_FILE_FILEAREA, $grade->id);
        } else {
            return get_string('countfiles', 'assignfeedback_file', $count);
        }
        */
    }

    /**
     * Display the list of files  in the feedback status table
     * @param stdClass $grade
     * @return string
     */
    public function view(stdClass $grade) {
        // TODO davo - add a real annotate link here + link to download PDF (once generated)
        return 'Click here to annotate the assignment';
    }

    /**
     * The assignment has been deleted - cleanup
     *
     * @return bool
     */
    public function delete_instance() {

        // TODO davo - clean up all annotations and files

/*
        global $DB;
        // will throw exception on failure
        $DB->delete_records('assignfeedback_file', array('assignment'=>$this->assignment->get_instance()->id));
*/
        return true;
    }

    /**
     * Return true if there are no feedback files
     * @param stdClass $grade
     */
    public function is_empty(stdClass $grade) {
        // TODO davo - return true if response has not yet been generated
    }

    /**
     * Get file areas returns a list of areas this plugin stores files
     * @return array - An array of fileareas (keys) and descriptions (values)
     */
    public function get_file_areas() {
        $name = $this->get_name();
        return array(
            ASSIGNFEEDBACK_PDF_FA_IMAGE => get_string('imagefor', 'assignfeedback_pdf', $name),
            ASSIGNFEEDBACK_PDF_FA_RESPONSE => get_string('responsefor', 'assignfeedback_pdf', $name)
        );
    }

    /**
     * Return true if this plugin can upgrade an old Moodle 2.2 assignment of this type
     * and version.
     *
     * @param string $type old assignment subtype
     * @param int $version old assignment version
     * @return bool True if upgrade is possible
     */
    public function can_upgrade($type, $version) {

        // TODO davo - allow this to upgrade an old uploadpdf plugin

        /*
        if (($type == 'upload' || $type == 'uploadsingle') && $version >= 2011112900) {
            return true;
        }
        return false;
        */
    }

    /**
     * Upgrade the settings from the old assignment to the new plugin based one
     *
     * @param context $oldcontext - the context for the old assignment
     * @param stdClass $oldassignment - the data for the old assignment
     * @param string $log - can be appended to by the upgrade
     * @return bool was it a success? (false will trigger a rollback)
     */
    public function upgrade_settings(context $oldcontext, stdClass $oldassignment, & $log) {
        // first upgrade settings (nothing to do)
        return true;
    }

    /**
     * Upgrade the feedback from the old assignment to the new one
     *
     * @param context $oldcontext - the database for the old assignment context
     * @param stdClass $oldassignment The data record for the old assignment
     * @param stdClass $oldsubmission The data record for the old submission
     * @param stdClass $grade The data record for the new grade
     * @param string $log Record upgrade messages in the log
     * @return bool true or false - false will trigger a rollback
     */
    public function upgrade(context $oldcontext, stdClass $oldassignment, stdClass $oldsubmission, stdClass $grade, & $log) {

        // TODO davo - upgrade annotated uploadpdf assignments

        /*
        global $DB;

        // now copy the area files
        $this->assignment->copy_area_files_for_upgrade($oldcontext->id,
                                                        'mod_assignment',
                                                        'response',
                                                        $oldsubmission->id,
                                                        // New file area
                                                        $this->assignment->get_context()->id,
                                                        'assignfeedback_file',
                                                        ASSIGNFEEDBACK_FILE_FILEAREA,
                                                        $grade->id);

        // now count them!
        $filefeedback = new stdClass();
        $filefeedback->numfiles = $this->count_files($grade->id, ASSIGNFEEDBACK_FILE_FILEAREA);
        $filefeedback->grade = $grade->id;
        $filefeedback->assignment = $this->assignment->get_instance()->id;
        if (!$DB->insert_record('assignfeedback_file', $filefeedback) > 0) {
            $log .= get_string('couldnotconvertgrade', 'mod_assign', $grade->userid);
            return false;
        }
        return true;
        */
    }
}
