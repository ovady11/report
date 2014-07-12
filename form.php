<?php
require_once ($CFG->libdir . '/formslib.php');

/** 
 * @author Avi
 * 
 */
class report_teacherreport_form extends \moodleform {
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see moodleform::definition()
	 *
	 */
	protected function definition() {
		global $CFG, $DB;
		
		$pluginconfig = get_config('report_teacherreport');
		$results = $DB->get_records_sql('select * from '. $CFG->prefix . 'course_categories where parent = ? and id > 1',array($pluginconfig->year));
		$branches = array();
		$branches[0] = get_string('allbranches','report_teacherreport');
		foreach ($results as $result){
			$branches[intval($result->id)] = $result->name;
		}
		$courses = $DB->get_records_sql('SELECT id,name FROM '. $CFG->prefix . 'course_categories where parent in (select id from '. $CFG->prefix . 'course_categories where parent = ?)',array($pluginconfig->year));
		$cr = array();
		$cr[0] = get_string('allcourses','report_teacherreport');
		foreach ($courses as $course) {
			$cr[$course->id] = $course->name;
		}
		$mform = $this->_form;
		
		$mform->addElement('date_selector','fromdate',get_string('fromdate','report_teacherreport'));
		$mform->addElement('date_selector','todate',get_string('todate','report_teacherreport'));
		$mform->addElement('select','branch',get_string('branch','report_teacherreport'),$branches);
		$mform->addElement('select','course',get_string('path','report_teacherreport'),$cr);
		$mform->disabledIf('course', 'branch','eq',0);
		
		$this->add_action_buttons(false,get_string('getreport','report_teacherreport'));
	}
	
	function display() {
		global $PAGE, $CFG;
		parent::display();
		$PAGE->requires->yui_module('moodle-report_teacherreport-form','M.report_teacherreport.form.init',array( $CFG->wwwroot));
	}
	
}

?>