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
		
		
		$results = $DB->get_records('course_categories',array("parent" => 0));
		$branches = array();
		foreach ($results as $result){
			$branches[$result->id] = $result->name;
		}
		$mform = $this->_form;
		
		$mform->addElement('date_selector','fromdate',get_string('fromdate','report_teacherreport'));
		$mform->addElement('date_selector','todate',get_string('todate','report_teacherreport'));
		$mform->addElement('select','branch',get_string('brnach','report_teacherreport'),$branches);
		$mform->addElement('select','speilest',get_string('path','report_teacherreport'));
		
		$this->add_action_buttons(false,get_string('getreport','report_teacherreport'));
	}
	
	function display() {
		global $PAGE, $CFG;
		$PAGE->requires->yui_module('moodle-report_teacherreport-form','M.report_teacherreport.form.init',array( $CFG->wwwroot));
		parent::display();
	}
	
}

?>