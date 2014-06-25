<?php
/**
*
*This will normally display a simple HTML form for controlling the report, and, the code for displaying the report. 
*/

require('../../config.php');
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->dirroot.'/report/log/locallib.php');
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

$params = array();

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

equire_login($course);

$context = context_course::instance($course->id);

require_capability('report/log:view', $context);

add_to_log($course->id, "course", "report log", "report/log/index.php?id=$course->id", $course->id);

if ($hostid != $CFG->mnet_localhost_id || $course->id == SITEID) {
	admin_externalpage_setup('reportteacherreport', '', null, '', array('pagelayout'=>'report'));
	echo $OUTPUT->header();
} else {
	$PAGE->set_title($course->shortname .': '. $strlogs);
	$PAGE->set_heading($course->fullname);
	echo $OUTPUT->header();
}

echo $OUTPUT->heading(get_string('teacherreport') .':');

echo $OUTPUT->footer();
