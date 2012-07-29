var diff='۰'.charCodeAt(0)-'0'.charCodeAt(0);
function en2faDigit ( str )
{
    str=String(str);
    var fa_str="";
    for (var i=0, c=str.length; i<c; i++)
        fa_str+=String.fromCharCode(str.charCodeAt(i)+diff);
    return fa_str;
}