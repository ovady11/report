<?php
define('AJAX_SCRIPT', true);

require('../../config.php');

$category = required_param('category', PARAM_INT);

$PAGE->set_url(new moodle_url('/report/teacherreport/ajax.php'),array('category' => $category));

$categories = $DB->get_records('course_categories', array('parent' => $category));
$context = context_coursecat::instance($category, MUST_EXIST);
require_login();


$outcome = new stdClass();
$outcome->success = true;
$outcome->response = new stdClass();
$outcome->error = '';
$outcome->response->id = 0;
$outcome->response->name = get_string('allcourses','report_teacherreport');
$categories[0] = $outcome->response;
$outcome->response = $categories;
echo json_encode($outcome);
die();