<?php
defined('MOODLE_INTERNAL') || die;

$ADMIN->add('reports', new admin_externalpage('reportteacherreport', get_string('teacherreport','report_teacherreport'), "$CFG->wwwroot/report/teacherreport/index.php?id=".SITEID));

$settings = null;