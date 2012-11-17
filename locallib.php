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

global $CFG;
require_once($CFG->dirroot.'/mod/assign/feedback/pdf/lib.php');

/**
 * File areas for PDF feedback assignment
 */
define('ASSIGNFEEDBACK_PDF_MAXSUMMARYFILES', 5);

/**
 * library class for pdf feedback plugin extending feedback plugin base class
 *
 * @package   assignfeedback_pdf
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
        $mform->addElement('static', '', '', $this->annotate_link($grade));
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
        return $this->annotate_link($grade);

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
        return $this->annotate_link($grade);
    }

    protected function annotate_link(stdClass $grade) {
        // TODO davo - add link to download PDF (once generated)
        $cm = $this->assignment->get_course_module();
        $url = new moodle_url('/mod/assign/feedback/pdf/editcomment.php', array('id' => $cm->id, 'userid' => $grade->userid));

        return html_writer::link($url, get_string('annotatesubmission', 'assignfeedback_pdf'));
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

    public function edit_comment_page($userid, $pageno, $enableedit = true) {
        global $CFG, $DB, $OUTPUT, $PAGE, $USER;

        // Check user and submission both exist.
        $assignment = $this->assignment->get_instance();
        $user = $DB->get_record('user', array('id' => $userid), '*', MUST_EXIST);
        $params = array('assignment' => $assignment->id, 'userid' => $userid);
        $submission = $DB->get_record('assign_submission', $params, '*', MUST_EXIST);
        $params = array('assignment' => $assignment->id, 'submission' => $submission->id);
        $submissionpdf = $DB->get_record('assignsubmission_pdf', $params, '*', MUST_EXIST);
        $submission->numpages = $submissionpdf->numpages;
        $cm = $this->assignment->get_course_module();

        // Check capabilities.
        $context = $this->assignment->get_context();
        if ($USER->id == $userid) {
            if (!has_capability('mod/assign:grade', $context)) {
                require_capability('mod/assign:submit', $context);
                $enableedit = false;
            }
        } else {
            require_capability('mod/assign:grade', $context);
        }

        // Create a frameset if to handle the 'showprevious comments' sidebar.
        $showprevious = optional_param('showprevious', -1, PARAM_INT);
        if ($enableedit && optional_param('topframe', false, PARAM_INT)) {
            if ($showprevious != -1) {
                echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">';
                echo '<html><head><title>'.get_string('feedback', 'assign').':'.fullname($user, true).':'.format_string($assignment->name).'</title></head>';
                echo html_writer::start_tag('frameset', array('cols' => "60%, 40%"));
                $mainframeurl = new moodle_url('/mod/assign/feedback/editcomment.php', array('id' => $cm->id,
                                                                                  'userid' => $userid,
                                                                                  'pageno' => $pageno,
                                                                                  'showprevious' => $showprevious));
                $sideframeurl = new moodle_url('/mod/assign/feedback/editcomment.php', array('id' => $cm->id,
                                                                                       'userid' => $userid,
                                                                                       'action' => 'showprevious'));
                echo html_writer::empty_tag('frame', array('src' => $mainframeurl));
                echo html_writer::empty_tag('frame', array('src' => $sideframeurl));
                echo html_writer::end_tag('frameset');
                echo '</html>';
                die();
            }
        }

        $savedraft = optional_param('savedraft', null, PARAM_TEXT);
        $generateresponse = optional_param('generateresponse', null, PARAM_TEXT);

        // Close the window (if the user clicks on 'savedraft')
        if ($enableedit && $savedraft) {
            echo $OUTPUT->header(get_string('feedback', 'assignment').':'.format_string($assignment->name));
            echo $OUTPUT->heading(get_string('draftsaved', 'assignmentfeedback_pdf'));
            echo html_writer::script('self.close()');
            die();
        }

        // Generate the response PDF and cose the window, if requested
        if ($enableedit && $generateresponse) {
            if ($this->create_response_pdf($submission->id)) {
                // TODO davo - decide if this is actually needed any more.
                /*
                $submission->data2 = ASSIGNMENT_UPLOADPDF_STATUS_RESPONDED;

                $updated = new stdClass();
                $updated->id = $submission->id;
                $updated->data2 = $submission->data2;
                $DB->update_record('assign_submission', $updated);
                */

                $PAGE->set_title(get_string('feedback', 'assignment').':'.format_string($assignment->name));
                echo $OUTPUT->header();
                echo $OUTPUT->heading(get_string('responseok', 'assignmentfeedback_pdf'));
                require_once($CFG->libdir.'/gradelib.php');
                // TODO davo - is this still needed?
                //echo $this->update_main_listing($submission);
                echo html_writer::script('self.close()');
                die();
            } else {
                echo $OUTPUT->header(get_string('feedback', 'assignment').':'.format_string($this->assignment->name));
                print_error('responseproblem', 'assignfeedback_pdf');
                die();
            }
        }

        list($imageurl, $imgwidth, $imgheight, $pagecount) = $this->get_page_image($pageno, $submission);

        $PAGE->requires->js('/mod/assignment/type/uploadpdf/scripts/mootools-core-1.4.1.js');
        $PAGE->requires->js('/mod/assignment/type/uploadpdf/scripts/mootools-more-1.4.0.1.js');
        $PAGE->requires->js('/mod/assignment/type/uploadpdf/scripts/raphael-min.js');
        $PAGE->requires->js('/mod/assignment/type/uploadpdf/scripts/contextmenu.js');

        //$PAGE->set_pagelayout('popup');
        $PAGE->set_title(get_string('feedback', 'assignment').':'.fullname($user, true).':'.format_string($assignment->name));
        $PAGE->set_heading('');

        echo $OUTPUT->header();

        echo $this->output_controls($submission, $user, $enableedit, $showprevious);

        $pageselector = ''; // TODO davo - fix this properly

        // TODO davo - the rest of this function needs reviewing

        // Output the page image
        echo '<div id="pdfsize" style="clear: both; width:'.$imgwidth.'px; height:'.$imgheight.'px; ">';
        echo '<div id="pdfouter" style="position: relative; "> <div id="pdfholder" > ';
        echo '<img id="pdfimg" src="'.$imageurl.'" width="'.$imgwidth.'" height="'.$imgheight.'" />';
        echo '</div></div></div>';
        $pageselector = str_replace(array('selectpage','"nextpage"','"prevpage"'),array('selectpage2','"nextpage2"','"prevpage2"'),$pageselector);
        echo '<br/>';
        echo $pageselector;
        if ($enableedit) {
            echo '<p><a id="opennewwindow" target="_blank" href="editcomment.php?id='.$cm->id.'&amp;userid='.$userid.'&amp;pageno='. $pageno .'&amp;showprevious='.$showprevious.'">'.get_string('opennewwindow','assignment_uploadpdf').'</a></p>';
        }
        echo '<br style="clear:both;" />';

        if ($enableedit) {
            // Definitions for the right-click menus
            echo '<ul class="contextmenu" style="display: none;" id="context-quicklist"><li class="separator">'.get_string('quicklist','assignment_uploadpdf').'</li></ul>';
            echo '<ul class="contextmenu" style="display: none;" id="context-comment"><li><a href="#addtoquicklist">'.get_string('addquicklist','assignment_uploadpdf').'</a></li>';
            echo '<li class="separator"><a href="#red">'.get_string('colourred','assignment_uploadpdf').'</a></li>';
            echo '<li><a href="#yellow">'.get_string('colouryellow','assignment_uploadpdf').'</a></li>';
            echo '<li><a href="#green">'.get_string('colourgreen','assignment_uploadpdf').'</a></li>';
            echo '<li><a href="#blue">'.get_string('colourblue','assignment_uploadpdf').'</a></li>';
            echo '<li><a href="#white">'.get_string('colourwhite','assignment_uploadpdf').'</a></li>';
            echo '<li><a href="#clear">'.get_string('colourclear','assignment_uploadpdf').'</a></li>';
            echo '<li class="separator"><a href="#deletecomment">'.get_string('deletecomment','assignment_uploadpdf').'</a></li>';
            echo '</ul>';
        }

        // Definition for 'resend' box
        echo '<div id="sendfailed" style="display: none;"><p>'.get_string('servercommfailed','assignment_uploadpdf').'</p><button id="sendagain">'.get_string('resend','assignment_uploadpdf').'</button><button onClick="hidesendfailed();">'.get_string('cancel','assignment_uploadpdf').'</button></div>';

        $server = array(
            'id' => $cm->id,
            'userid' => $userid,
            'pageno' => $pageno,
            'sesskey' => sesskey(),
            'updatepage' => $CFG->wwwroot.'/mod/assignment/type/uploadpdf/updatecomment.php',
            'lang_servercommfailed' => get_string('servercommfailed', 'assignment_uploadpdf'),
            'lang_errormessage' => get_string('errormessage', 'assignment_uploadpdf'),
            'lang_okagain' => get_string('okagain', 'assignment_uploadpdf'),
            'lang_emptyquicklist' => get_string('emptyquicklist', 'assignment_uploadpdf'),
            'lang_emptyquicklist_instructions' => get_string('emptyquicklist_instructions', 'assignment_uploadpdf'),
            'deleteicon' => $OUTPUT->pix_url('/t/delete'),
            'pagecount' => $pagecount,
            'blank_image' => $CFG->wwwroot.'/mod/assignment/type/uploadpdf/style/blank.gif',
            'image_path' => $CFG->wwwroot.'/mod/assignment/type/uploadpdf/pix/',
            'css_path' => $CFG->wwwroot.'/lib/yui/'.$CFG->yui2version.'/build/assets/skins/sam/',
            'editing' => ($enableedit ? 1 : 0),
            'lang_nocomments' => get_string('findcommentsempty', 'assignment_uploadpdf')
        );

        echo '<script type="text/javascript">server_config = {';
        foreach ($server as $key => $value) {
            echo $key.": '$value', \n";
        }
        echo "ignore: ''\n"; // Just there so IE does not complain
        echo '};</script>';

        $jsmodule = array('name' => 'assignment_uploadpdf',
                          'fullpath' => new moodle_url('/mod/assignment/type/uploadpdf/scripts/annotate.js'),
                          'requires' => array('yui2-yahoo-dom-event', 'yui2-container', 'yui2-element',
                                              'yui2-button', 'yui2-menu', 'yui2-utilities'));
        $PAGE->requires->js_init_call('uploadpdf_init', null, true, $jsmodule);

        echo $OUTPUT->footer();
    }

    protected function output_controls($submission, $user, $enableedit, $showprevious) {
        global $PAGE, $DB, $OUTPUT;

        $context = $this->assignment->get_context();

        $out = '';
        $saveopts = '';
        if ($enableedit) {
            // Save draft / generate response buttons
            $saveopts .= html_writer::start_tag('form', array('action' => $PAGE->url->out_omit_querystring(),
                                                            'method' => 'post', 'target' => '_top'));
            $saveopts .= html_writer::input_hidden_params($PAGE->url);
            $img = $OUTPUT->pix_icon('savequit', '', 'assignfeedback_pdf');
            $saveopts .= html_writer::tag('button', $img, array('type' => 'submit', 'name' => 'savedraft',
                                                               'value' => 'savedraft',
                                                               'title' => get_string('savedraft', 'assignfeedback_pdf')));
            $img = $OUTPUT->pix_icon('tostudent', '', 'assignfeedback_pdf');
            $saveopts .= html_writer::tag('button', $img, array('type' => 'submit', 'name' => 'generateresponse',
                                                               'value' => 'generateresponse',
                                                               'title' => get_string('generateresponse', 'assignfeedback_pdf')));
        }

        // 'Download original' button
        $pdfurl = moodle_url::make_pluginfile_url($context->id, 'assignsubmission_pdf', ASSIGNSUBMISSION_PDF_FA_FINAL,
                                                  $submission->id, '/', ASSIGNSUBMISSION_PDF_FILENAME, true);
        $downloadorig = get_string('downloadoriginal', 'assignment_uploadpdf');
        if (!$enableedit) {
            $pdfurl = moodle_url::make_pluginfile_url($context->id, 'assignfeedback_pdf', ASSIGNFEEDBACK_PDF_FA_RESPONSE,
                                                      $submission->id, '/', ASSIGNFEEDBACK_PDF_FILENAME, true);
        }
        $img = $OUTPUT->pix_icon('download', $downloadorig, 'assignfeedback_pdf');
        $saveopts .= html_writer::link($pdfurl, $img, array('id' => 'downloadpdf', 'title' => $downloadorig,
                                                           'alt' => $downloadorig));
        if ($enableedit) {
            $saveopts .= html_writer::end_tag('form');
        }

        // Show previous assignment
        if ($enableedit) {
            // TODO davo - test this works
            $ps_sql = "SELECT asn.id, asn.name
                       FROM {assignment} asn
                       JOIN {assignsubmission_pdf} subp ON subp.assignment = asn.id
                       JOIN {assign_submission} sub ON sub.id = subp.submission
                       WHERE asn.course = ?
                       AND sub.userid = ?
                       AND asn.id != ?
                       ORDER BY sub.timemodified DESC;";
            $assignment = $this->assignment->get_instance();
            $course = $this->assignment->get_course();
            $previoussubs = $DB->get_records_sql_menu($ps_sql, array($course->id, $user->id, $assignment->id) );
            if ($previoussubs) {
                $showpreviousstr = get_string('showpreviousassignment','assignment_uploadpdf');;
                $saveopts .= html_writer::empty_tag('input', array('type' => 'submit', 'id' => 'showpreviousbutton',
                                                             'name' => 'showpreviousbutton', 'value' => $showpreviousstr));
                $saveopts .= html_writer::select($previoussubs, 'showprevious', $showprevious,
                                                 array('-1' => get_string('previousnone', 'assignfeedback_pdf')),
                                                 array('id' => 'showpreviousselect', 'onChange' => 'this.form.submit();'));
            }
        }

        $comments = $DB->get_records('assignfeedback_pdf_cmnt', array('submissionid' => $submission->id), 'pageno, posy, posx');
        $saveopts .= html_writer::tag('button', get_string('findcomments','assignment_uploadpdf'),
                                      array('id' => 'findcommentsbutton'));
        if (empty($comments)) {
            $outcomments = array('0:0' => get_string('findcommentsempty', 'assignment_uploadpdf'));
        } else {
            $outcomments = array();
            foreach ($comments as $comment) {
                $text = $comment->rawtext;
                if (strlen($text) > 40) {
                    $text = substr($text, 0, 39).'&hellip;';
                }
                $outcomments["{$comment->pageno}:{$comment->id}"] = $comment->pageno.': '.s($text);
            }
        }
        $saveopts .= html_writer::select($outcomments, 'findcomments', '', false, array('id' => 'findcommentsselect'));

        if (!$enableedit) {
            // TODO davo - check if this is still needed
            /*
            // If opening in same window - show 'back to comment list' link
            if (array_key_exists('uploadpdf_commentnewwindow', $_COOKIE) && !$_COOKIE['uploadpdf_commentnewwindow']) {
                $url = "editcomment.php?a={$this->assignment->id}&amp;userid={$userid}&amp;action=showprevious";
                echo '<a href="'.$url.'">'.get_string('backtocommentlist','assignment_uploadpdf').'</a>';
            }
            */
        }

        $out .= html_writer::tag('div', $saveopts, array('id' => 'saveoptions'));

        // TODO davo - review the 2nd line of the toolbar
        /*
        echo '<div id="toolbar-line2">';
        $pageselector = '';
        $disabled = ($pageno == 1) ? ' disabled = "disabled" ' : '';
        $pageselector .= '<button id="prevpage" '.$disabled.'onClick="gotoprevpage();" title="'.get_string('keyboardprev','assignment_uploadpdf').'" >&lt;--'.get_string('previous','assignment_uploadpdf').'</button>';

        $pageselector .= '<span style="position:relative; width:50px; display:inline-block; height:34px"><select name="selectpage" id="selectpage" onChange="selectpage();">';
        for ($i=1; $i<=$pagecount; $i++) {
            if ($i == $pageno) {
                $pageselector .= "<option value='$i' selected='selected'>$i</option>";
            } else {
                $pageselector .= "<option value='$i'>$i</option>";
            }
        }
        $pageselector .= '</select></span>';

        $disabled = ($pageno == $pagecount) ? ' disabled = "disabled" ' : '';
        $pageselector .= '<button id="nextpage" '.$disabled.'onClick="gotonextpage();" title="'.get_string('keyboardnext','assignment_uploadpdf').'">'.get_string('next','assignment_uploadpdf').'--&gt;</button>';

        echo $pageselector;

        if ($enableedit) {
            // Choose comment colour
            echo '<input type="submit" id="choosecolour" style="line-height:normal;" name="choosecolour" value="" title="'.get_string('commentcolour','assignment_uploadpdf').'">';
            echo '<div id="choosecolourmenu" class="yuimenu" title="'.get_string('commentcolour', 'assignment_uploadpdf').'"><div class="bd"><ul class="first-of-type">';
            $colours = array('red','yellow','green','blue','white','clear');
            foreach ($colours as $colour) {
                echo '<li class="yuimenuitem choosecolour-'.$colour.'-"><img src="'.$OUTPUT->pix_url($colour,'assignment_uploadpdf').'"/></li>';
            }
            echo '</ul></div></div>';

            // Choose line colour
            echo '<input type="submit" id="chooselinecolour" style="line-height:normal;" name="chooselinecolour" value="" title="'.get_string('linecolour','assignment_uploadpdf').'">';
            echo '<div id="chooselinecolourmenu" class="yuimenu"><div class="bd"><ul class="first-of-type">';
            $colours = array('red','yellow','green','blue','white','black');
            foreach ($colours as $colour) {
                echo '<li class="yuimenuitem choosecolour-'.$colour.'-"><img src="'.$OUTPUT->pix_url('line'.$colour, 'assignment_uploadpdf').'"/></li>';
            }
            echo '</ul></div></div>';

            // Stamps
            echo '<input type="submit" id="choosestamp" style="line-height:normal;" name="choosestamp" value="" title="'.get_string('stamp','assignment_uploadpdf').'">';
            echo '<div id="choosestampmenu" class="yuimenu"><div class="bd"><ul class="first-of-type">';
            $stamps = MyPDFLib::get_stamps();
            foreach ($stamps as $stamp => $filename) {
                echo '<li class="yuimenuitem choosestamp-'.$stamp.'-"><img width="32" height="32" src="'.$OUTPUT->pix_url('stamps/'.$stamp, 'assignment_uploadpdf').'"/></li>';
            }
            echo '</ul></div></div>';


            // Choose annotation type
            $drawingtools = array('commenticon','lineicon','rectangleicon','ovalicon','freehandicon','highlighticon','stampicon','eraseicon');
            $checked = ' yui-button-checked';
            echo '<div id="choosetoolgroup" class="yui-buttongroup">';

            foreach ($drawingtools as $drawingtool) {
                echo '<span id="'.$drawingtool.'" class="yui-button yui-radio-button'.$checked.'">';
                echo ' <span class="first-child">';
                echo '  <button type="button" name="choosetoolradio" value="'.$drawingtool.'" title="'.get_string($drawingtool,'assignment_uploadpdf').'">';
                echo '   <img src="'.$OUTPUT->pix_url($drawingtool, 'assignment_uploadpdf').'" />';
                echo '  </button>';
                echo ' </span>';
                echo '</span>';
                $checked = '';
            }
            echo '</div>';

        }
        echo '</div>'; // toolbar-line-2
        */

        return $out;
    }

    protected function get_temp_folder($submissionid) {
        global $CFG, $USER;

        $tempfolder = $CFG->dataroot.'/temp/assignfeedback_pdf/';
        $tempfolder .= sha1("{$submissionid}_{$USER->id}_".time()).'/';
        return $tempfolder;
    }

    /**
     * Get the image details from a file and return them.
     * @param stored_file $file
     * @return mixed array|false
     */
    protected static function get_image_details($file, $pagecount) {
        if ($imageinfo = $file->get_imageinfo()) {
            $imgurl = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(),
                                                      $file->get_filearea(), $file->get_itemid(),
                                                      $file->get_filepath(), $file->get_filename());
            // Prevent browser from caching image if it has changed
            if (strpos($imgurl, '?') === false) {
                $imgurl .= '?ts='.$file->get_timemodified();
            } else {
                $imgurl .= '&amp;ts='.$file->get_timemodified();
            }
            return array($imgurl, $imageinfo['width'], $imageinfo['height'], $pagecount);
        }
        // Something went wrong
        return false;
    }

    protected function get_page_image($pageno, $submission) {
        global $CFG;

        require_once($CFG->dirroot.'/mod/assign/submission/pdf/mypdflib.php');
        require_once($CFG->dirroot.'/mod/assign/submission/pdf/lib.php');

        $pagefilename = 'page'.$pageno.'.png';
        $pdf = new AssignPDFLib();

        $pagecount = $submission->numpages;

        $context = $this->assignment->get_context();
        $fs = get_file_storage();
        // If pagecount is 0, then we need to skip down to the next stage to find the real page count
        if ($pagecount && ($file = $fs->get_file($context->id, 'assignfeedback_pdf', ASSIGNFEEDBACK_PDF_FA_IMAGE,
                                                 $submission->id, '/', $pagefilename)) ) {
            if ($ret = self::get_image_details($file, $pagecount)) {
                return $ret;
            }
            // If the image is bad in some way, try to create a new image instead
        }

        // Generate the image
        $tempfolder = $this->get_temp_folder($submission->id);
        $imagefolder = $tempfolder.'img';
        if (!file_exists($imagefolder)) {
            if (!mkdir($imagefolder, 0777, true)) {
                $errdata = (object)array('temparea' => $imagefolder);
                throw new moodle_exception('errortempfolder', 'assignfeedback_pdf', '', null, $errdata);
            }
        }
        $pdffolder = $tempfolder.'sub';
        $pdffile = $pdffolder.'/submission.pdf';
        if (!file_exists($pdffolder)) {
            if (!mkdir($pdffolder, 0777, true)) {
                $errdata = (object)array('temparea' => $pdffolder);
                throw new moodle_exception('errortempfolder', 'assignfeedback_pdf', '', null, $errdata);
            }
        }

        $file = $fs->get_file($context->id, 'assignsubmission_pdf', ASSIGNSUBMISSION_PDF_FA_FINAL, $submission->id,
                              '/', ASSIGNSUBMISSION_PDF_FILENAME);
        if (!$file) {
            throw new moodle_exception('errornosubmission', 'assignfeedback_pdf');
        }
        $file->copy_content_to($pdffile);  // Copy the PDF out of the file storage, into the temp area

        $pagecount = $pdf->set_pdf($pdffile, $pagecount); // Only loads the PDF if the pagecount is unknown (0)
        if ($pageno > $pagecount) {
            @unlink($pdffile);
            @rmdir($imagefolder);
            @rmdir($pdffolder);
            @rmdir($tempfolder);
            return array(null, 0, 0, $pagecount);
        }

        $pdf->set_image_folder($imagefolder);
        if (!$imgname = $pdf->get_image($pageno)) { // Generate the image in the temp area
            throw new moodle_exception('errorgenerateimage', 'assignment_uploadpdf');
        }

        $imginfo = array(
            'contextid' => $context->id,
            'component' => 'assignfeedback_pdf',
            'filearea' => ASSIGNFEEDBACK_PDF_FA_IMAGE,
            'itemid' => $submission->id,
            'filepath' => '/',
            'filename' => $pagefilename
        );
        $file = $fs->create_file_from_pathname($imginfo, $imagefolder.'/'.$imgname); // Copy the image into the file storage

        //Delete the temporary files
        @unlink($pdffile);
        @unlink($imagefolder.'/'.$imgname);
        @rmdir($imagefolder);
        @rmdir($pdffolder);
        @rmdir($tempfolder);

        if ($ret = self::get_image_details($file, $pagecount)) {
            return $ret;
        }
        return array(null, 0, 0, $pagecount);
    }

    public function show_previous_comments($userid) {
        // TODO davo - finish this
    }

    public function create_response_pdf($submissionid) {
        // TODO davo - finish this
        return false;
    }
}
