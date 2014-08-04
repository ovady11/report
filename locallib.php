<?php
defined ( 'MOODLE_INTERNAL' ) || die ();
require_once ($CFG->dirroot . '/course/lib.php');
/**
 *
 * @param array $data        	
 */
function print_teaherreport_table($data = array()) {
	global $DB, $CFG, $COURSE, $PAGE;
	
	$result = array ();
	if ($data->branch == 0) {
		$brunches = $DB->get_recordset ( 'course_categories', array (
				"parent" => get_config ( 'report_teacherreport', 'year' ) 
		) );
		foreach ( $brunches as $branch ) {
			$courses = $DB->get_recordset ( 'course_categories', array (
					"parent" => $branch->id 
			) );
			foreach ( $courses as $course ) {
				$result [] = $DB->get_recordset_sql ( 'SELECT distinct c.id, c.shortname, u.idnumber, u.username, u.firstname, u.lastname, u.id as userid FROM ' . $CFG->prefix . 'course as c, ' . $CFG->prefix . 'role_assignments AS ra, ' . $CFG->prefix . 'user AS u, ' . $CFG->prefix . 'context AS ct, ' . $CFG->prefix . 'course_categories as cc  WHERE c.category = cc.id and c.id = ct.instanceid AND ra.roleid =3 AND ra.userid = u.id AND ct.id = ra.contextid and c.timecreated >= ? and c.timecreated <= ? and cc.id = ?', array (
						$data->fromdate,
						$data->todate,
						$course->id 
				) );
			}
		}
	} elseif ($data->course == 0) {
		$courses = $DB->get_recordset ( 'course_categories', array (
				"parent" => $data->branch 
		) );
		foreach ( $courses as $course ) {
			$result [] = $DB->get_recordset_sql ( 'SELECT distinct c.id, c.shortname, u.idnumber, u.username, u.firstname, u.lastname, u.id as userid FROM ' . $CFG->prefix . 'course as c, ' . $CFG->prefix . 'role_assignments AS ra, ' . $CFG->prefix . 'user AS u, ' . $CFG->prefix . 'context AS ct, ' . $CFG->prefix . 'course_categories as cc  WHERE c.category = cc.id and c.id = ct.instanceid AND ra.roleid =3 AND ra.userid = u.id AND ct.id = ra.contextid and c.timecreated >= ? and c.timecreated <= ? and cc.id = ?', array (
					$data->fromdate,
					$data->todate,
					$course->id 
			) );
		}
	} else {
		$result = $DB->get_recordset_sql ( 'SELECT distinct c.id, c.shortname, u.idnumber, u.username, u.firstname, u.lastname, u.id as userid FROM ' . $CFG->prefix . 'course as c, ' . $CFG->prefix . 'role_assignments AS ra, ' . $CFG->prefix . 'user AS u, ' . $CFG->prefix . 'context AS ct, ' . $CFG->prefix . 'course_categories as cc  WHERE c.category = cc.id and c.id = ct.instanceid AND ra.roleid =3 AND ra.userid = u.id AND ct.id = ra.contextid and c.timecreated >= ? and c.timecreated <= ? and cc.id = ?', array (
				$data->fromdate,
				$data->todate,
				$data->course 
		) );
	}
	$modules = get_module_metadata ( $COURSE, get_module_types_names (), 0 );
	$activities = array_filter ( $modules, create_function ( '$mod', 'return ($mod->archetype !== MOD_ARCHETYPE_RESOURCE && $mod->archetype !== MOD_ARCHETYPE_SYSTEM);' ) );
	$resources = array_filter ( $modules, create_function ( '$mod', 'return ($mod->archetype === MOD_ARCHETYPE_RESOURCE);' ) );
	echo "<table id=\"teacherreport\">\n";
	echo "<thead>\n";
	echo "<tr>\n";
	echo "<th rowspan=\"2\">" . get_string ( 'teacher', 'report_teacherreport' ) . "</th><th rowspan=\"2\">" . get_string ( 'teacherid', 'report_teacherreport' ) . "</th>";
	echo "<th rowspan=\"2\">" . get_string ( 'students', 'report_teacherreport' ) . "</th><th rowspan=\"2\">" . get_string ( 'courses', 'report_teacherreport' ) . "</th>";
	echo "<th colspan=\"" . count ( $resources ) . "\">" . get_string ( 'resources' ) . "</th><th colspan=\"" . count ( $activities ) . "\">" . get_string ( 'activities' ) . "</th>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	foreach ( $resources as $key ) {
		echo "<th>" . $key->title . "</th>";
	}
	foreach ( $activities as $key ) {
		echo "<th>" . $key->title . "</th>";
	}
	echo "</tr>\n";
	echo "</thead>\n";
	$teacher = array ();
	if (is_array ( $result )) {
		foreach ( $result as $course ) {
			$teacher = array_merge ( $teacher, get_teacher_in_category ( $course ) );
		}
	} else {
		$teacher = get_teacher_in_category ( $result );
	}
	echo "<tbody>\n";
	foreach ( $teacher as $username => $value ) {
		$rowspan = $value ['coursecount'] + 1;
		echo "<tr>\n";
		echo "<td  rowspan=\"1\" id=\"fullname" . $username . "\"><button  class=\"main\" id=\"" . $username . "\">+</button><a href=\"/user/profile.php?id=" . $username . "\">" . $value ['fullname'] . "</a></td>\n";
		echo "<td rowspan=\"1\" id=\"username" . $username . "\"><a href=\"/user/profile.php?id=" . $username . "\">" . $value['idnumber'] . "</a></td>";
		echo "<td>" . $value ['students'] . "</td><td>" . $value ['coursecount'] . "</td>";
		foreach ( $resources as $mod ) {
			$count = isset ( $value [$mod->name] ) ? $value [$mod->name] ['count'] : '0';
			echo "<td>" . $count . "</td>";
		}
		foreach ( $activities as $mod ) {
			$count = isset ( $value [$mod->name] ) ? $value [$mod->name] ['count'] : '0';
			echo "<td>" . $count . "</td>";
		}
		echo "</tr>\n";
		foreach ( $value ['courses'] as $prop => $propval ) {
			echo "<tr class=\"details c" . $username . "\">\n";
			echo "<td>" . $propval ['students'] . "</td><td><a href=\"/course/view.php?id=" . $propval ['id'] . "\">" . $prop . "</a></td>";
			foreach ( $resources as $mod ) {
				$count = isset ( $propval [$mod->name] ) ? $propval [$mod->name] ['count'] : '0';
				echo "<td>" . $count . "</td>";
			}
			foreach ( $activities as $mod ) {
				$count = isset ( $propval [$mod->name] ) ? $propval [$mod->name] ['count'] : '0';
				echo "<td>" . $count . "</td>";
			}
			echo "</tr>\n";
		}
	}
	echo "</tbody>\n";
	echo "</table>\n";
	$PAGE->requires->yui_module ( 'moodle-report_teacherreport-table', 'M.report_teacherreport.table.init', array () );
}

/**
 */
function get_teacher_in_category($result) {
	global $DB;
	$teacher = array ();
	foreach ( $result as $value ) {
		$courseModules = get_course_mods ( $value->id );
		foreach ( $courseModules as $mod ) {
			if (! isset ( $teacher [$value->username] [$mod->modname] )) {
				$teacher [$value->username] [$mod->modname] ['count'] = 0;
				$teacher [$value->username] ['courses'] [$value->shortname] [$mod->modname] ['count'] = 0;
				$teacher [$value->username] [$mod->modname] ['id'] = $mod->module;
			}
			$teacher [$value->username] ['courses'] [$value->shortname] [$mod->modname] ['count'] ++;
			$teacher [$value->username] [$mod->modname] ['count'] ++;
		}
		if (! isset ( $teacher [$value->username] ['coursecount'] )) {
			$teacher [$value->username] ['coursecount'] = 0;
		}
		$teacher [$value->username] ['coursecount'] ++;
		if (! isset ( $teacher [$value->username] ['students'] )) {
			$teacher [$value->username] ['students'] = 0;
		}
		$context = context_course::instance ( $value->id );
		$teacher [$value->username] ['students'] += count_enrolled_users ( $context, 'mod/assign:submit' );
		$teacher [$value->username] ['courses'] [$value->shortname] ['students'] = count_enrolled_users ( $context, 'mod/assign:submit' );
		$teacher [$value->username] ['courses'] [$value->shortname] ['id'] = $value->id;
		$teacher [$value->username] ['fullname'] = $value->firstname . ' ' . $value->lastname;
		$teacher[$value->username]['idnumber'] = $value->idnumber;
	}
	return $teacher;
}