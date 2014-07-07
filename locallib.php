<?php
defined ( 'MOODLE_INTERNAL' ) || die ();
require_once($CFG->dirroot.'/course/lib.php');
/**
 *
 * @param array $data        	
 */
function print_teaherreport_table($data = array()) {
	global $DB,$CFG,$COURSE;
	
	$result = $DB->get_recordset_sql ('SELECT distinct c.id, c.fullname, u.username, u.firstname, u.lastname, u.id as userid FROM '. $CFG->prefix . 'course as c, '. $CFG->prefix . 'role_assignments AS ra, '. $CFG->prefix . 'user AS u, '. $CFG->prefix . 'context AS ct, '. $CFG->prefix . 'course_categories as cc  WHERE c.category = cc.id and c.id = ct.instanceid AND ra.roleid =3 AND ra.userid = u.id AND ct.id = ra.contextid and c.timecreated >= ? and c.timecreated <= ? and cc.id = ?', array($data->fromdate,$data->todate,$data->course));
	$modules = get_module_metadata($COURSE, get_module_types_names(), 0);
	$activities = array_filter($modules, create_function('$mod', 'return ($mod->archetype !== MOD_ARCHETYPE_RESOURCE && $mod->archetype !== MOD_ARCHETYPE_SYSTEM);'));
	$resources = array_filter($modules, create_function('$mod', 'return ($mod->archetype === MOD_ARCHETYPE_RESOURCE);'));
	echo "<table>\n";
	echo "<thead>\n";
	echo "<tr>\n";
	echo "<th rowspan=\"2\">" . get_string ('teacher', 'report_teacherreport') . "</th><th rowspan=\"2\">" . get_string ('teacherid', 'report_teacherreport') . "</th>";
	echo "<th rowspan=\"2\">" . get_string('students', 'report_teacherreport') . "</th><th rowspan=\"2\">" . get_string('courses', 'report_teacherreport') . "</th>";
	echo "<th colspan=\"" . count($resources) . "\">" . get_string('resources') . "</th><th colspan=\"" . count($activities)  . "\">" . get_string('activities') . "</th>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	foreach ($resources as $key){
		echo "<th>" . $key->title . "</th>";
	}
	foreach ($activities as $key){
		echo "<th>" . $key->title . "</th>";
	}
	$teacher = array();
	foreach ($result as $value) {
		$courseModules = get_course_mods($value->id);
		foreach ($courseModules as $mod) {
			$logteacherid = $DB->get_records('log',array(
					'time'		=> $mod->added,
					'course'	=> $mod->course
				));
			if(reset($logteacherid) && reset($logteacherid)->userid == $value->userid){
				if(!isset($teacher[$value->username][$mod->modname])){
					$teacher[$value->username][$mod->modname]['count'] = 0;
					$teacher[$value->username][$mod->modname]['id'] = $mod->module;
				}
				$teacher[$value->username][$mod->modname]['count']++;
			}
		}
		if(!isset($teacher[$value->username]['coursecount'])){
			$teacher[$value->username]['coursecount'] = 0;
		}
		$teacher[$value->username]['coursecount']++;
		if(!isset($teacher[$value->username]['students'])){
			$teacher[$value->username]['students'] = 0;
		}
		$context = context_course::instance($value->id);
		$teacher[$value->username]['students'] += count_enrolled_users($context,'mod/assign:submit');
		$teacher[$value->username]['fullname'] = $value->firstname . ' ' . $value->lastname;
	}
	echo "</tr>\n";
	echo "</thead>\n";
	echo "<tbody>\n";
	foreach ($teacher as $key => $value) {
		echo "<tr>\n";
		echo "<td>" . $value['fullname'] . "</td><td>" . $key . "</td><td>" . $value['students'] . "</td><td>" . $value['coursecount'];
		foreach ($resources as $mod){
			$count = isset($value[$mod->name]) ? $value[$mod->name]['count']: '0';
			echo "<th>" . $count . "</th>";
		}
		foreach ($activities as $mod){
			$count = isset($value[$mod->name]) ? $value[$mod->name]['count']: '0';
			echo "<th>" . $count . "</th>";
		}
		echo "</tr>\n";
	}
	echo "</tbody>\n";
	echo "</table>\n";
}