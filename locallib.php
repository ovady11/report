<?php
defined ( 'MOODLE_INTERNAL' ) || die ();
require_once($CFG->dirroot.'/course/lib.php');
/**
 *
 * @param array $data        	
 */
function print_teaherreport_table($data = array()) {
	global $DB,$COURSE;
	
	$result = $DB->get_recordset_sql ('SELECT distinct c.id, c.fullname, u.username, u.firstname, u.lastname FROM mdl_course as c, mdl_role_assignments AS ra, mdl_user AS u, mdl_context AS ct WHERE c.id = ct.instanceid AND ra.roleid =3 AND ra.userid = u.id AND ct.id = ra.contextid and c.timecreated >= ? and c.timecreated <= ?', array($data->fromdate,$data->todate));
	$modules = get_module_metadata($COURSE, get_module_types_names(), 0);
	$activities = array_filter($modules, create_function('$mod', 'return ($mod->archetype !== MOD_ARCHETYPE_RESOURCE && $mod->archetype !== MOD_ARCHETYPE_SYSTEM);'));
	$resources = array_filter($modules, create_function('$mod', 'return ($mod->archetype === MOD_ARCHETYPE_RESOURCE);'));
	/*<tr><th rowspan="2">שם מרצה</th><th rowspan="2">ת.ז.</th><th rowspan="2">מספר סטדנטים</th><th rowspan="2">קורסים</th><th colspan="4">משאבים</th><th colspan="4">פעילויות</th></tr>
	<tr><th>קבצים</th><th>קבצי וידאו</th><th>קישורים</th><th>וידאו משובץ</th><th>מטלות</th><th>בחנים</th><th>פורום</th><th>נוכחות</th></tr>*/
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
	echo "</tr>\n";
	echo "</thead>\n";
	echo "<tbody>\n";
	foreach ($result as $key => $value) {
		echo "<tr>\n";
		echo "<td>" . $value->firstname . " " . $value->lastname . "</td><td>" . $value->username . "</td><td>100</td><td>" . $value->fullname;
		echo "</tr>\n";
	}
	echo "</tbody>\n";
	echo "</table>\n";
}