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


require_once(dirname(__FILE__).'/../../config.php');
include_once($CFG->dirroot . '/lib/enrollib.php');
include_once($CFG->dirroot . '/grade/querylib.php');


$PAGE->set_url("/student_grades/my_grades_resume.php");
$PAGE->set_title(get_string('resume', 'block_student_grades'));
$PAGE->set_heading(get_string('resume', 'block_student_grades'));
$PAGE->set_pagelayout('standard');
$PAGE->navbar->add(get_string('resume', 'block_student_grades'));

$PAGE->set_context(context_system::instance());

require_login();

echo $OUTPUT->header();

$courses = enrol_get_users_courses($USER->id, true);

$table = new html_table();
$table->head  = array(get_string('course'), get_string('finalgrade', 'grades'));
$table->data  = array();
$table->attributes['class'] = 'generaltable table-bordered';

foreach ($courses as $c) {
	$context = context_course::instance($c->id);
    if (has_capability('moodle/grade:view', $context)) {
		$grades = grade_get_course_grade($USER->id, $c->id);
		$table->data[] = array($c->fullname, $grades->str_grade);
	}
}

echo html_writer::start_tag('div', array('class' => 'grades-resume'));
echo html_writer::table($table);
echo html_writer::end_tag('div');

echo $OUTPUT->footer();
