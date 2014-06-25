<?php
function report_teacherreport_extend_navigation_user($navigation, $user, $course) {
	list($all, $today) = report_teacherreport_can_access_user_report($user, $course);
}

/**
 * Is current user allowed to access this report
 *
 * @access private defined in lib.php for performance reasons
 * @global stdClass $USER
 * @param stdClass $user
 * @param stdClass $course
 * @return array with two elements $all, $today
 */
function report_teacherreport_can_access_user_report($user, $course) {
	global $USER;

	$coursecontext = context_course::instance($course->id);
	$personalcontext = context_user::instance($user->id);

	$today = false;
	$all = false;

	if (has_capability('report/log:view', $coursecontext)) {
		$today = true;
	}
	if (has_capability('report/log:viewtoday', $coursecontext)) {
		$all = true;
	}

	if ($today and $all) {
		return array(true, true);
	}

	if (has_capability('moodle/user:viewuseractivitiesreport', $personalcontext)) {
		if ($course->showreports and (is_viewing($coursecontext, $user) or is_enrolled($coursecontext, $user))) {
			return array(true, true);
		}

	} else if ($user->id == $USER->id) {
		if ($course->showreports and (is_viewing($coursecontext, $USER) or is_enrolled($coursecontext, $USER))) {
			return array(true, true);
		}
	}

	return array($all, $today);
}