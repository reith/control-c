define(['lib/util/tableForm', 'jquery', 'i18n!nls/dict'], function(TableForm, $, Dict) {
	var buildStudentExercisesForm = function (form, table, seri_url, exercise_url, course_url, vdl_php, se_php, e_php )
	{
		TableForm.prototype.customOperations = function() {
			var tjson=this.json;

			(tjson.type=='seri') ? //j argument is json data received
				showLog = function(j,p) {
		t=j.time==null ? Dict['nr'] : j.time;
		g=j.grade==null? Dict['ng'] : j.grade;
		switch (j.zip) {
			case 'd': g+='<strong class="redtxt"> [ '+ Dict['cht']+' ]</strong>'; break;
			case 'n': g+='<strong> [ ' + Dict['nzar'] +' ]</strong>'; break;
			case 'e': g+='<strong class="redtxt"> [ '+ Dict['zfc'] + ' ]</strong>'; break;
		}

		j.s==null ?
			p.html(Dict['frd']+': ' + t + '<br />' + Dict['tsg'] + ': ' + g)
			:
			p.html('<strong class="redtxt">'+j.s+'</strong>');
		} :

				showLog = function(j,p) {

		if (j.s==null)
		{
			var grade=j.grade;
			
			if (j.cheat==1)
				grade='<strike>'+grade+'</strike><strong class="redtxt"> [ '+ Dict['cd'] +' ]</strong>';

			var h='<span style="text-align: left ; direction: ltr; float:left; width: 140px; "><a target="_blank" href="'+cfg.url+'/source_code/'+
			j.seri+'/'+j.ex_num+'"><img alt="' + Dict['sc'] + '" src="/img/source-code-16x16.png" /></a>|<a target="_blank" href="'+
			cfg.url+'/test_case_input/'+j.seri+'/'+j.ex_num+'"><img alt="'+ Dict['tci'] +'" src="/img/tc-input-16x16.png" />'+
			'</a>|<a target="_blank" href="'+cfg.url+'/test_case_true_output/'+j.seri+'/'+j.ex_num+'"><img alt="'+ Dict['tcci'] +'" '+
			'src="/img/tc-true-output-16x16.png" /></a>';

			if  (j.compileError==='0')
				h+='|<a target="_blank" href="'+cfg.url+'/test_case_output/'+j.seri+'/'+j.ex_num+'"><img alt="'+Dict['tco']+'" src="/img/tc-output-16x16.png" /></a>';
			else
				h+='|<a target="_blank" href="'+cfg.url+'/compile_error/'+j.seri+'/'+j.ex_num+'"><img alt="'+Dict['ce']+'" src="/img/compile-error-16x16.png" /></a>';
		
			h+='</span>';
			p.html(h+Dict['eg']+': '+ grade + '<br />' + Dict['ce'] +': ').append( j.compileError === '0' ? Dict['n'] : Dict['y'])
			.append('<br />' + Dict['rt'] + ': ' + j.time + '<br />' + Dict['re'] + ': ').append( j.re.length? Dict['ftc']+ j.re.join(','):Dict['n']);
		}
		else
			p.html('<strong class="redtxt">'+j.s+'</strong>');
				}

			$.each(tjson.tr, function(i,tr) {
		(tjson.type=='single') ?
		$('<tr/>').html('<td>'+Locale._number(tr.r.id)+'</td><td><a href="'+seri_url+tr.r.sid+'">'+Locale._number(tr.r.seri)+'</a></td><td><a href="'+exercise_url+tr.r.id+'">'
			+Locale._number(tr.r.number)+'</a></td><td><a href="'+course_url+tr.r.cid+'">'+tr.r.name+'</a></td><td>'+tr.r.cdate+'</td><td>'+tr.r.ddate+'</td><td>'+tr.r.expire+'</td>').data('r',tr).appendTo('#'+table)
		:
		$('<tr/>').html('<td>'+Locale._number(tr.r.id)+'</td><td><a href="'+seri_url+tr.r.sid+'">'+Locale._number(tr.r.seri)+'</a></td><td><a href="'+course_url+tr.r.cid+'">'+tr.r.name+'</a></td><td>'
			+tr.r.cdate+'</td><td>'+tr.r.ddate+'</td><td>'+tr.r.expire+'</td>\n').data('r',tr).appendTo('#'+table);
			});

			$('#'+table+' tr td:nth-child(1)').addClass('action_td').bind('click', function() {
			var $r=$(this).parent();
			var tr = $r.data('r');
			if (($n=$r.next('tr')).is('.resultBox'))
					{ tjson.type=="seri" && $('#seriID').val(tr.id); $n.is(':visible') ? $n.fadeOut('fast') : $n.fadeIn('fast'); return; }

		var $box = $result.clone().insertAfter($r);
		$box.children().html( Dict['pw.']);

		if (tr.c==1)
		$.ajax({
		type: "POST",
		url: vdl_php,
		dataType: "json",
		data: {type: tjson.type, id: tr.id},
		async: false,
		success: function (j){showLog(j, $box.children());}
		})

		else if (tr.e==1)
				$box.children().html(Dict['engy']);
		else if (tjson.type=="single")
				$box.children().html(Dict['yshps']);
		else switch (tr.m) {
				case 'j': 
			var sfid="sf"+tr.id;
			sf='<form target="if" action="'+se_php+'" enctype="multipart/form-data" method="post" id="'+sfid+'">'
			+'<input type="file" name="f"></input><input type="hidden" name="s" value="'+tr.id+'"></input>'
			+'<input type="hidden" name="o" value="'+tr.s+'"></input><input type="submit" value="'+Dict['snd']+'" ></form>';
			tr.s==null ?
			$box.children().html(Dict['sza']) :
			$box.children().html('<strong class="grntxt">'+Dict['yasc']+'.</strong>'+ Dict['rshp']+ '.');
			$(sf).appendTo($box.children());
			var $sf=$('#'+sfid);
			$sf.submit(function(){ifrm.document.body.innerHTML='w'; $('#loading').removeClass('hidden'); tx=setInterval(function()
				{if (ifrm.document.body.innerHTML!='w') {$('#loading').addClass('hidden'); j=$.parseJSON(ifrm.document.body.innerHTML);
			if (j.errcode) { showError(j.error); if (['FILEEXT', 19].indexOf(j.errcode)!=-1) { $('<br /><strong class="redtxt">'+j.error+'</strong>').insertBefore($sf); $sf.hide('slow').remove();
			} } else { showError(j.msg); $('<br /><strong class="grntxt">'+j.msg+'</strong>').insertBefore($sf); $sf.hide('slow').remove(); }; clearInterval(tx)}}, 100)});
			break;
				case 'w': $box.children().html(Dict['cscnm'] + '.');
			break;
				case 'b': $box.children().html(Dict['bftc'] + '.' );
			break;
				default: $box.children().html(Dict['ynm'] + '.');
		}

			$box.show();
			});

		}


		$(document).ready(function(){
			retrieveTabe = new TableForm(form, table, $('#res'));
			ifrm=document.getElementById('if').contentWindow;
			$result = $('<tr class="resultBox"><td colspan=10>'+Dict['pw.']+'</td></tr>').hide();
			$('#'+form).submit(function(e){
				retrieveTabe.submitForm(e, e_php, '#'+form);
			});

			//show new exercises
			$('#'+form).trigger('submit');
		});

	}

	return buildStudentExercisesForm;
});
