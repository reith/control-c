define(['jquery', 'lib/util/tableForm'], function($, TableForm) {

	var selGrp=function(id) {
		return '<input type="radio" name="'+id+'" value="d">حذف</input><input checked="1" type="radio" name="'+id+'" value="a">اضافه</input><input type="radio" name="'+id+'" value="b">مسدود</input><input type="radio" name="'+id+'" value="c">لغو</input>';
		};

	var oprDesc=function(l) {
		switch (l) {
			case 'a': return ' تائید ';
			case 'b': return ' مسدود ';
			case 'd': return ' حذف ';
			case 'c': return ' نادیده گرفته ';
		}
	};

	TableForm.prototype.customOperations = function () {
		tjson=this.json;
		table=this;
		$('#manageBox').removeClass('hidden');
		
		$.each(tjson.tr.r, function(i, r)
		{
			$('<tr/>').html(r).append('<td>'+selGrp(tjson.tr.id[i])+'</td>').appendTo(table.$table);
		});
	};

	return {
		receive: function (j) {
  		if (j.error) {
    		showError(j.error);
    		return;
  		}

			var msg='<ul>';
			$.each(j.msg['id'], function(i, v) {
				msg+='<li>'+'درخواست '+v+oprDesc(j.msg.done[i])+' شد</li>'
			});
			msg+='</ul>'

			if (msg==='<ul></ul>')
				showMessage ('تغییری صورت نگرفت');
			else
				showCongratulation (msg);
		}
	};
});


