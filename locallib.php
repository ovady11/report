<?php
defined ( 'MOODLE_INTERNAL' ) || die ();
/**
 *
 * @param array $data        	
 */
function print_teaherreport_table($data = array()) {
	global $DB;
	
	$result = $DB->get_recordset_sql ('SELECT distinct c.id, c.fullname, u.username, u.firstname, u.lastname FROM mdl_course as c, mdl_role_assignments AS ra, mdl_user AS u, mdl_context AS ct WHERE c.id = ct.instanceid AND ra.roleid =3 AND ra.userid = u.id AND ct.id = ra.contextid');
	
	echo "<table>\n";
	echo "<thead>\n";
	echo "<tr>\n";
	echo "<th rowspan=\"2\">" . get_string ('teacher', 'report_teacherreport') . "</th><th rowspan=\"2\">" . get_string ('teacherid', 'report_teacherreport') . "</th>";
	echo "</tr>\n";
	echo "</thead>\n";
	echo "<tbody>\n";
	foreach ($result as $key => $value) {
		echo "<tr>\n";
		echo "<td>" . $value->firstname . " " . $value->lastname . "</td><td>" . $value->username . "</td>";
		echo "</tr>\n";
	}
	echo "</tbody>\n";
	echo "</table>\n";
}