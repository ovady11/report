<?php
/**
*
*This will normally display a simple HTML form for controlling the report, and, the code for displaying the report. 
*/

require('../../config.php');
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->dirroot.'/report/teacherreport/locallib.php');
require_once($CFG->dirroot.'/report/teacherreport/form.php');
require_once($CFG->libdir.'/adminlib.php');


$id          = optional_param('id', 0, PARAM_INT);// Course ID
$host_course = optional_param('host_course', '', PARAM_PATH);// Course ID

if (empty($host_course)) {
	$hostid = $CFG->mnet_localhost_id;
	if (empty($id)) {
		$site = get_site();
		$id = $site->id;
	}
} else {
	list($hostid, $id) = explode('/', $host_course);
}
$chooselog   = optional_param('chooselog', 0, PARAM_INT);

$params = array();
if ($chooselog !== 0) {
	$params['chooselog'] = $chooselog;
}

$PAGE->set_url('/report/teacherreport/index.php', $params);
$PAGE->set_pagelayout('report');

if ($hostid == $CFG->mnet_localhost_id) {
	$course = $DB->get_record('course', array('id'=>$id), '*', MUST_EXIST);

} else {
	$course_stub       = $DB->get_record('mnet_log', array('hostid'=>$hostid, 'course'=>$id), '*', true);
	$course->id        = $id;
	$course->shortname = $course_stub->coursename;
	$course->fullname  = $course_stub->coursename;
}

require_login($course);

$context = context_course::instance($course->id);

require_capability('report/teacherreport:view', $context);

add_to_log($course->id, "course", "report teacherreport", "report/teacherreport/index.php?id=$course->id", $course->id);

if ($hostid != $CFG->mnet_localhost_id || $course->id == SITEID) {
	echo $OUTPUT->header();
} else {
	$PAGE->set_title($course->shortname .': '. $strlogs);
	$PAGE->set_heading($course->fullname);
	echo $OUTPUT->header();
}
$mform = new report_teacherreport_form();

echo $OUTPUT->heading(get_string('teacherreport','report_teacherreport') .':');
if ($fromform = $mform->get_data()) {
	print_teaherreport_table($fromform); 
} else {
	$mform->display();
}
echo $OUTPUT->footer();
