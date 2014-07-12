YUI.add('moodle-report_teacherreport-form', function(Y) {
	M.report_teacherreport = M.report_teacherreport || {};
	M.report_teacherreport.form = {
		rootDoc: 'http://localhost',
		init : function(root){
			this.rootDoc = root || this.rootDoc;
			Y.one('#id_branch').on('change',this.loadCat);
			if(Y.one('#id_branch').get('value') != 0){
				this.loadCat();
			}
		},
		loadCat: function() {
			Y.io( M.report_teacherreport.form.rootDoc +'/report/teacherreport/ajax.php',{data:{'category':parseInt(Y.one('#id_branch').get('value'))},
				on:{
					success: function (transId,response) {
						Y.one('#id_course').get('childNodes').remove();
						var res = Y.JSON.parse(response.responseText);
						res = res.response;
						for(var cat in res){
							Y.one('#id_course').appendChild('<option value="' + cat + '">' + res[cat].name + '</option>');
						}
					}
				}
			});
		}
	};
}, '@VERSION@', {
	requires:['base','node','io','json-parse']
});