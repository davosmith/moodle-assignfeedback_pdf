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
 * Functionality for creating a debug log
 *
 * @package   assignfeedback_pdf
 * @copyright 2013 Davo Smith, Synergy Learning
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
defined('MOODLE_INTERNAL') || die();

function debuglog($msg) {
    // Remove the '//' from the start of the next line to turn off all debugging

    // return;

    global $CFG;

    $fp = fopen($CFG->dataroot.'/assignfeedback_pdf.log', 'a');

    if (!$fp) {
        return;
    }

    fwrite($fp, date('j M Y H:i:s').' - '.$msg."\n");
    fclose($fp);
}

function debuglog_print_r($var) {
    ob_start();
    print_r($var);
    $out = ob_get_contents();
    ob_end_clean();

    debuglog($out);
}

function debuglog_backtrace() {

    $output = "Backtrace: \n";
    $trace = debug_backtrace();
    foreach ($trace as $depth => $details) {
        $output .= $depth.': ';
        $output .= $details['file'].' - ';
        $output .= 'line '.$details['line'].': ';
        if ($details['function']) {
            $output .= $details['function'].'()';
        }
        $output .= "\n";
    }
    debuglog($output);
}


class debug_timing {
    static $lasttime = 0;
    static $times = array();

    static function start() {
        self::$lasttime = microtime(true);
        self::$times = array();
    }

    static function add($description, $logimmediately = false) {
        $data = new stdClass;
        $data->timeelapsed = microtime(true) - self::$lasttime;
        $data->description = $description;
        self::$times[] = $data;
        self::$lasttime = microtime(true);

        if ($logimmediately) {
            debuglog("$description: $data->timeelapsed");
        }
    }

    static function output() {
        foreach (self::$times as $time) {
            echo "$time->description: $time->timeelapsed<br/>\n";
        }
    }

    static function output_log($pagename) {
        $timing = '';
        $total = 0;
        foreach (self::$times as $time) {
            $total += $time->timeelapsed;
        }
        foreach (self::$times as $time) {
            $percent = 100.0 * $time->timeelapsed / $total;
            $percent = sprintf('%d%%', $percent);
            $timing .= "$time->description: $time->timeelapsed ($percent)\n";
        }
        $timing .= "Total: $total\n";
        debuglog($pagename."\n".$timing);
    }
}