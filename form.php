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
		
		
		$results = $DB->get_records_sql('select * from '. $CFG->prefix . 'course_categories where parent = 198 and id > 1');
		$branches = array();
		$inparams ='';
		foreach ($results as $result){
			$branches[intval($result->id)] = $result->name;
			$inparams .= '?,';
		}
		$inparams[strlen($inparams)-1]=null;
		$courses = $DB->get_records_sql('SELECT id,name FROM '. $CFG->prefix . 'course_categories where parent in (select id from '. $CFG->prefix . 'course_categories where parent = 198)');
		$cr = array();
		foreach ($courses as $course) {
			$cr[$course->id] = $course->name;
		}
		$mform = $this->_form;
		
		$mform->addElement('date_selector','fromdate',get_string('fromdate','report_teacherreport'));
		$mform->addElement('date_selector','todate',get_string('todate','report_teacherreport'));
		$mform->addElement('select','branch',get_string('branch','report_teacherreport'),$branches);
		$mform->addElement('select','course',get_string('path','report_teacherreport'),$cr);
		
		$this->add_action_buttons(false,get_string('getreport','report_teacherreport'));
	}
	
	function display() {
		global $PAGE, $CFG;
		parent::display();
		$PAGE->requires->yui_module('moodle-report_teacherreport-form','M.report_teacherreport.form.init',array( $CFG->wwwroot));
	}
	
	function validation($data, $files)
	{
		$data['course'] = clean_param($data['course'], PARAM_INT);
	}
	
}

?>