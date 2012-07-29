newMembership = function(event)
{
	alert(cfg);
  event.preventDefault();
  $.ajax({
    url: 'student.join_course.php',
    type: 'POST',
    data: {course: $('#courseId').val()},
    dataType: 'json',
    success: function (json) { json.error!=null ? showError (json.error) : showAlert('s', json.message); },
    complete: function() {$('#tableForm').submit()}
  });
}

TableForm.prototype.customOperations = function() {
  var tjson=this.json;
  $.each(tjson.tr, function(i,tr) {
    $('<tr />').html(tr.r).click(function(){
      $('#courseId').val(tr.id);
    }).appendTo('#tableMonitor');
  });
}
$(document).ready(function(){
  retrieveTabe = new TableForm;
  $('#tableForm').submit(function(e){
    retrieveTabe.submitForm(e, "student.courses.php", "#tableForm");
  });
  $('#sendRequest').submit(function(e){
    newMembership(e);
  });
});