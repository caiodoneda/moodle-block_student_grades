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
 *
 * @package    block_student_grades
 * @copyright  Caio Doneda
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

include_once($CFG->dirroot . '/lib/enrollib.php');


class block_student_grades extends block_base {
    function init() {
        $this->title = get_string('pluginname', 'block_student_grades');
    }

    function has_config() {
        return true;
    }

    public function applicable_formats() {
        return array('my' => true);
    }

    function get_content() {
        global $CFG, $USER;

        if($this->content !== NULL) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->text = "";
        $this->content->footer = '';

        $courses = enrol_get_users_courses($USER->id, true);        
        $base_url = $CFG->wwwroot . '/grade/report/user/index.php?id=';

        foreach ($courses as $c) {
            $context = context_course::instance($c->id);
            if (has_capability('moodle/grade:view', $context)) {
                $this->content->text .= html_writer::link($base_url . $c->id, $c->fullname);
                $this->content->text .= '<BR>';
            }
        }

        if (!empty($this->content->text)) {
            $this->content->text .= "<hr>";
            $link = html_writer::link($CFG->wwwroot . "/blocks/student_grades/my_grades_resume.php", 
                    get_string('resume', 'block_student_grades'));
            $this->content->text .= $link;
        }

        return $this->content;
    }
}


