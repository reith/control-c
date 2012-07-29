//NOTE: this require should be removed.
<?php require_once '../libcc/session.php'; ?>

var Locale = (function ( digit, locale )
{
  var fa_diff='۰'.charCodeAt(0)-'0'.charCodeAt(0);
  var locale = '<?=@$_SESSION['locale']?>' || 'fa' ;

  en2faDigit = function ( str )
  {
    str=String(str);
    var fa_str="";
    for (var i=0, c=str.length; i<c; i++)
        fa_str+=String.fromCharCode(str.charCodeAt(i)+fa_diff);
    return fa_str;
  }
  
  return {
    setLocale: function( loc ) {
      locale = loc;
    },
    digit: function ( digit, loc )
    {
      tmpLocale = loc || locale;
      switch (tmpLocale)
      {
	case 'fa': return en2faDigit(digit);
	default: return (digit);
      }
    }
  }
})()