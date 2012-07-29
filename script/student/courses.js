  newMembership = function(event)
  {
    event.preventDefault();
    $.ajax({
      url: cfg.std_join_crs_action,
      type: 'POST',
      data: {course: $('#'+cfg.course_id).val()},
      dataType: 'json',
      success: function (json) { json.error!=null ? showError (json.error) : showAlert('s', json.message); },
      complete: function() {$('#'+cfg.form).submit()}
    });
  }
  TableForm.prototype.customOperations = function() {
    var tjson=this.json;
    $.each(tjson.tr, function(i,tr) {
      rcode = '<td>'+Locale.digit(tr.r.crsid)+'</td><td><a href="'+cfg.course_url+'/'+tr.r.crsid+'">'+tr.r.crsn+'</a></td>'
      +'<td><a href="'+cfg.profile_url+'/'+tr.r.tchrid+'">'+tr.r.tchrn+'</a></td><td>'+Locale.digit(tr.r.yr)+'</td><td>'
      +tr.r.stat+'</td>';
      
      if (tr.type == 'all')
	rcode += '<td>'+tr.r.rgst+'</td>';
      
      $('<tr />').html(rcode).data('id', tr.r.crsid).appendTo('#'+cfg.table);
    });
    
    $('#'+cfg.table+' tr td:nth-child(1)').addClass('action_td').click(function(){
	$('#'+cfg.course_id).val( $(this).parent().data('id') );
      });
  }
  
  $(document).ready(function(){
    retrieveTabe = new TableForm( cfg.form, cfg.table );
    $('#'+cfg.form).submit(function(e){
      retrieveTabe.submitForm(e, cfg.std_crs_action, '#'+cfg.form);
    });
    $('#'+cfg.send).submit(function(e){
      newMembership(e);
    });
  });
  
  