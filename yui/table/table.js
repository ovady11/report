/**
 * 
 */

YUI.add('moodle-report_teacherreport-table', function(Y) {
	M.report_teacherreport = M.report_teacherreport || {};
	M.report_teacherreport.table = {
			init : function(){
				Y.all('.details').addClass('disabeld');
				Y.all('.main').on('click',function(e){
					 e.preventDefault();
					 if(Y.one('.c'+e.currentTarget.get('id')).hasClass('disabeld')){
						var numofRows = Y.all('.c'+e.currentTarget.get('id'))._nodes.length+1;
						e.currentTarget.setHTML('-');
						Y.one('#fullname'+e.currentTarget.get('id')).setAttribute('rowspan',numofRows);
						Y.one('#username'+e.currentTarget.get('id')).setAttribute('rowspan',numofRows);
						Y.all('.c'+e.currentTarget.get('id')).removeClass('disabeld');
					 } else {
						 e.currentTarget.setHTML('+');						 
						 Y.one('#fullname'+e.currentTarget.get('id')).setAttribute('rowspan',"1");
						 Y.one('#username'+e.currentTarget.get('id')).setAttribute('rowspan',"1");
						 Y.all('.c'+e.currentTarget.get('id')).addClass('disabeld');
					 }
				});
			}
	};

},'@VERSION@', {
	requires:['base','node']
});