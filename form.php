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
		
		
		$mform = $this->_form;
		
		$mform->addElement('date_selector','fromdate',get_string('fromdate','report_teacherreport'));
		$mform->addElement('date_selector','todate',get_string('todate','report_teacherreport'));
		
		$this->add_action_buttons(false,get_string('getreport','report_teacherreport'));
	}
}

?>