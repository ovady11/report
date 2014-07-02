YUI.add('moodle-report_teacherreport-form', function(Y) {
	M.report_teacherreport = M.report_teacherreport || {};
	M.report_teacherreport.form = {
		rootDoc: 'http://localhost',
		init : function(root){
			this.rootDoc = root || this.rootDoc;
			Y.one('#id_branch').on('change',this.loadCat);
		},
		loadCat: function() {
			Y.io( M.report_teacherreport.form.rootDoc +'/report/teacherreport/ajax.php',{data:{'category':this.get('selectedIndex')}});
		}
	};
}, '@VERSION@', {
	requires:['base','node','io']
});