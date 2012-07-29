<?php
//REITH: PRINT FORMATING FUNCTION
function transNumber($input) 
{
  $faRes="";
  $input="$input";
  $len=strlen($input);
  for ($i=0; $i<$len; $i++)
    switch($input[$i]) {
    case "0": $faRes.="۰"; break;
    case "1": $faRes.="۱"; break;
    case "2": $faRes.="۲"; break;
    case "3": $faRes.="۳"; break;
    case "4": $faRes.="۴"; break;
    case "5": $faRes.="۵"; break;
    case "6": $faRes.="۶"; break;
    case "7": $faRes.="۷"; break;
    case "8": $faRes.="۸"; break;
    case "9": $faRes.="۹"; break;
    default: $faRes.="$input[$i]"; break;
    }
    return $faRes;
}
function fa_number($input) {
    
    return transNumber($input);
}

?>