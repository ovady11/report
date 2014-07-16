<?php
defined('MOODLE_INTERNAL') || die;
global $DB;
$result = $DB->get_records('course_categories',array('parent' => 0));
$years = array();
foreach ($result as $value) {
	$years[$value->id] = $value->name;
}
reset($years);

$settings->add(new admin_setting_configselect('report_teacherreport/year',get_string('year','report_teacherreport'),get_string('yeardescription','report_teacherreport'),key($years),$years));