function hideMsg()
{
	document.getElementById('alertMsgBox').className='hidemsg'; 
	if (document.getElementById('nextURL').value)
		window.location = document.getElementById('nextURL').value; 
}

function showMsg(url)
{
	document.getElementById('alertMsgBox').className='showmsg';
	if (url)
		document.getElementById('nextURL').value = url;
	else
		document.getElementById('nextURL').value = null;
	
}

// DANGER: DON'T REMEBER WHEN USED IT
// FIXIT
// function showMessage(msg, url)
// {
//     document.getElementById('alertMsg').innerHTML=msg;
//     document.getElementById('alertMsgBox').className='showmsg';
// }

function closeAlert( callback )
{
    $('#alert').animate({'height':'0px'}, 'fast', function() { 
        $(this).html('');
        if ( callback )
            callback();
        if (document.getElementById('nextURL').value)
                window.location = document.getElementById('nextURL').value; 
    });
}

var Timer = "";
var alertAnimatePreClose = function(timeout, callback) {
    
    if  ( timeout )
        timeout*=1000;
    else
        timeout=4000;
    
    if (Timer !== "")
        clearTimeout(Timer)
        
    Timer = setTimeout(function() { closeAlert(callback); } , timeout);
}

function showError( message , timeout, callback, url )
{
    showAlert ( 'error', message, timeout, callback, url)
}

function showCongratulation( message , timeout, callback, url )
{
    showAlert ( 'congratulation', message, timeout, callback, url)
}

function showMessage( message , timeout, callback, url )
{
    showAlert ( 'message', message, timeout, callback, url)
}

function showAlert ( type, message, timeout, callback, url )
{
    $('#alert').html( message );
    switch (type)
    {
        case 'congratulation':
            $('#alert').css({'background-color': '#80f180', 'color': 'black'}).animate({'height':'42px'}, 'fast', function () { alertAnimatePreClose(timeout, callback); });
            break;
        case 'error':
            $('#alert').css({'background-color': '#c41e1e', 'color': 'white'}).animate({'height':'42px'}, 'slow', function () { alertAnimatePreClose(timeout, callback); } );
            break;
        default:
            $('#alert').css({'background-color': '#111111', 'color': 'yellow'}).animate({'height':'42px'}, 'slow', function () { alertAnimatePreClose(timeout, callback); });
    }
    
    if (url)
       document.getElementById('nextURL').value = url;
    else
       document.getElementById('nextURL').value = '';
}
