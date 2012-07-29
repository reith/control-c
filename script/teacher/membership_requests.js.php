selGrp=function(id) {
//   return '<select name="verify"><option value="a'+id+'">بله</option><option value="n'+id+'">خیر</option><option value="n'+id+'">لغو</option></select>';
    return '<input type="radio" name="'+id+'" value="d">حذف</input><input checked="1" type="radio" name="'+id+'" value="a">اضافه</input><input type="radio" name="'+id+'" value="b">مسدود</input><input type="radio" name="'+id+'" value="c">لغو</input>';
}
oprDesc=function(l) {
  switch (l) {
    case 'a': return ' تائید ';
    case 'b': return ' مسدود ';
    case 'd': return ' حذف ';
    case 'c': return ' نادیده گرفته ';
  }
}

TableForm.prototype.customOperations = function () {
  tjson=this.json;
  table=this;
  $('#manageBox').removeClass('hidden');
  
  $.each(tjson.tr.r, function(i, r)
  {
    $('<tr/>').html(r).append('<td>'+selGrp(tjson.tr.id[i])+'</td>').appendTo(table.$table);
  });
  
}
$(document).ready(function ()
{
  retrieveTabe = new TableForm('tableForm', 'tableMonitor');
  $("#tableForm").submit(function(e) {
    retrieveTabe.submitForm(e, $('#tableForm').attr('action'), "#tableForm");
  });
  $('#manageBox').submit(function(e) {
    e.preventDefault();
    $.ajax ({type: 'POST', cache: false, dataType:'json', url: $('#vm_action').val(),
	    data: $('#manageBox').serialize(),
	    success: function(j) {return recieve(j);}
	    });
  });
});

recieve=function (j) {
  if (j.error) {
    showError(j.error);
    return;
  }

  var msg='<ul>';
  $.each(j.msg['id'], function(i, v) {
	msg+='<li>'+'درخواست '+v+oprDesc(j.msg.done[i])+' شد</li>'
      })
  msg+='</ul>'

  if (msg==='<ul></ul>')
    showMessage ('تغییری صورت نگرفت');
  else
    showCongratulation (msg);
}