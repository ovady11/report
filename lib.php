<?php 
function report_teacherreport_extend_navigation_user($navigation, $user, $course) {
	$personalcontext = context_user::instance($user->id);
	
	if(has_capability('report/teacherreport:view', $personalcontext)){
		$url = new moodle_url('/report/teacherreport/index.php',array('id'=>1));
		$navigation->add(get_string('teacherreport','report_teacherreport'),$url,navigation_node::TYPE_SETTING, null, null, new pix_icon('i/report', ''));
	}
}