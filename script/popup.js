// NOTE: this file must be deleted probably

(function($) {
	$.popup = {
		verticalOffset: -75,
		horizontalOffset: 0,
		repositionOnResize: true,
		overlayOpacity: .2,
		overlayColor: '#000',
		okButton: '&nbsp;تائید&nbsp;',
		cancelButton: '&nbsp;لغو&nbsp;',
		dialogClass: null,
		show: function(msg, title, type, value, callback) {
			$.popup._hide();
			$.popup._overlay('show');
			$("BODY").append(
			  '<div id="popup_container">' +
			    '<h1 id="popup_title"></h1>' +
			    '<div id="popup_content">' +
			      '<div id="popup_message"></div>' +
				'</div>' +
			  '</div>');
			if( $.popup.dialogClass ) $("#popup_container").addClass($.popup.dialogClass);
			var pos = ($.browser.msie && parseInt($.browser.version) <= 6 ) ? 'absolute' : 'fixed';
			$("#popup_container").css({
				position: pos,
				zIndex: 99999,
				padding: 0,
				margin: 0
			});
			$("#popup_content").addClass(type);
			$("#popup_message").text(msg);
			$("#popup_message").html( $("#popup_message").text().replace(/\n/g, '<br />') );
			$("#popup_container").css({
				minWidth: $("#popup_container").outerWidth(),
				maxWidth: $("#popup_container").outerWidth()
			});
			$.popup._reposition();
			$.popup._maintainPosition(true);
			switch( type ) {
				case 'alert':
					if( title == null ) title = 'پیِغام';
					$("#popup_message").after('<div id="popup_panel"><input type="button" value="' + $.popup.okButton + '" id="popup_ok" /></div>');
					$("#popup_ok").click( function() {
						$.popup._hide();
						if (callback) callback(true);
					});
					$("#popup_ok").focus().keypress( function(e) {
						if( e.keyCode == 13 || e.keyCode == 27 ) $("#popup_ok").trigger('click');
					});
				break;
				case 'confirm':
					if( title == null ) title = 'تائید';
					$("#popup_message").after('<div id="popup_panel"><input type="button" value="' + $.popup.okButton + '" id="popup_ok" /> <input type="button" value="' + $.popup.cancelButton + '" id="popup_cancel" /></div>');
					$("#popup_ok").click( function() {
						$.popup._hide();
						if( callback ) callback(true);
					});
					$("#popup_cancel").click( function() {
						$.popup._hide();
						if( callback ) callback(false);
					});
					$("#popup_ok").focus();
					$("#popup_ok, #popup_cancel").keypress( function(e) {
						if( e.keyCode == 13 ) $("#popup_ok").trigger('click');
						if( e.keyCode == 27 ) $("#popup_cancel").trigger('click');
					});
				break;
				case 'prompt':
					if( title == null ) title = 'انتخاب';
					$("#popup_message").append('<br /><input type="text" size="30" id="popup_prompt" />').after('<div id="popup_panel"><input type="button" value="' + $.popup.okButton + '" id="popup_ok" /> <input type="button" value="' + $.popup.cancelButton + '" id="popup_cancel" /></div>');
					$("#popup_prompt").width( $("#popup_message").width() );
					$("#popup_ok").click( function() {
						var val = $("#popup_prompt").val();
						$.popup._hide();
						if( callback ) callback( val );
					});
					$("#popup_cancel").click( function() {
						$.popup._hide();
						if( callback ) callback( null );
					});
					$("#popup_prompt, #popup_ok, #popup_cancel").keypress( function(e) {
						if( e.keyCode == 13 ) $("#popup_ok").trigger('click');
						if( e.keyCode == 27 ) $("#popup_cancel").trigger('click');
					});
					if( value ) $("#popup_prompt").val(value);
					$("#popup_prompt").focus().select();
				break;
			}
			$("#popup_title").text(title);
		},
		_hide: function() {
			$("#popup_container").remove();
			$.popup._overlay('hide');
			$.popup._maintainPosition(false);
		},
		_overlay: function(status) {
			switch( status ) {
				case 'show':
					$.popup._overlay('hide');
					$("BODY").append('<div id="popup_overlay"></div>');
					$("#popup_overlay").css({
						position: 'absolute',
						zIndex: 99998,
						top: '0px',
						left: '0px',
						width: '100%',
						height: $(document).height(),
						background: $.popup.overlayColor,
						opacity: $.popup.overlayOpacity
					});
				break;
				case 'hide':
					$("#popup_overlay").remove();
				break;
			}
		},
		_reposition: function() {
			var top = (($(window).height() / 2) - ($("#popup_container").outerHeight() / 2)) + $.popup.verticalOffset;
			var left = (($(window).width() / 2) - ($("#popup_container").outerWidth() / 2)) + $.popup.horizontalOffset;
			if( top < 0 ) top = 0;
			if( left < 0 ) left = 0;
			if( $.browser.msie && parseInt($.browser.version) <= 6 ) top = top + $(window).scrollTop();
			$("#popup_container").css({
				top: top + 'px',
				left: left + 'px'
			});
			$("#popup_overlay").height( $(document).height() );
		},
		_maintainPosition: function(status) {
			if( $.popup.repositionOnResize ) {
				switch(status) {
					case true:
						$(window).bind('resize', $.popup._reposition);
					break;
					case false:
						$(window).unbind('resize', $.popup._reposition);
					break;
				}
			}
		}
	}
	jAlert = function(message, title, callback) {
		$.popup.show(message, title, 'alert', null, callback);
	}
	jConfirm = function(message, title, callback) {
		$.popup.show(message, title, 'confirm', null, callback);
	};
	jPrompt = function(message, title, value, callback) {
		$.popup.show(message, title, 'confirm', null, callback);
	};
})(jQuery);