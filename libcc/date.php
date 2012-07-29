<?php
// REITH: DATE FUNCTION (WITHOUT JALALI CALENEDR)

function div($a, $b)
{
  return (int) ($a / $b);
}

function gregorian_to_jalali($g_y, $g_m, $g_d, $g_w=null)
{
  //g_w = 7 special value, don't show week day but translated month on long date
  $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
  $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);

  $gy = $g_y-1600;
  $gm = $g_m-1;
  $gd = $g_d-1;

  $g_day_no = 365*$gy+div($gy+3,4)-div($gy+99,100)+div($gy+399,400);

  for ($i=0; $i < $gm; ++$i)
  $g_day_no += $g_days_in_month[$i];
  if ($gm>1 && (($gy%4==0 && $gy%100!=0) || ($gy%400==0)))
  /* leap and after Feb */
  ++$g_day_no;
  $g_day_no += $gd;

  $j_day_no = $g_day_no-79;

  $j_np = div($j_day_no, 12053);
  $j_day_no %= 12053;

  $jy = 979+33*$j_np+4*div($j_day_no,1461);

  $j_day_no %= 1461;

  if ($j_day_no >= 366) {
    $jy += div($j_day_no-1, 365);
    $j_day_no = ($j_day_no-1)%365;
  }

  for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i) {
    $j_day_no -= $j_days_in_month[$i];
  }
  $jm = $i+1;
  $jd = $j_day_no+1;
  if($g_w==null) 
    return $jy."-".$jm."-".$jd;
    
  switch($g_w) {
    case 7: $result=""; break;
    case 0: $result="یکشنبه"; break;
    case 1: $result="دوشنبه"; break;
    case 2: $result="سه‌شنبه‌"; break;
    case 3: $result="چهارشنبه"; break;
    case 4: $result="پنج‌شنبه"; break;
    case 5: $result="جمعه"; break;
    case 6: $result="شنبه"; break;
  }
  if ($g_w!=7)
    $result.="، ";

  $result.=$jd. " ";
  switch($jm) {
    case 1: $result.="فروردین"; break;
    case 2: $result.="اردیبهشت"; break;
    case 3: $result.="خـرداد"; break;
    case 4: $result.="تیـر"; break;
    case 5: $result.="مـرداد"; break;
    case 6: $result.="شهریور"; break;
    case 7: $result.="مهـر"; break;
    case 8: $result.="آبان"; break;
    case 9: $result.="آذر"; break;
    case 10:$result.="دی"; break;
    case 11:$result.="بهمن"; break;
    case 12:$result.="اسفند"; break;
  }
  $result.=" ".$jy;
  return $result;
}

function jalaliDate($gregorianDate)
//input format is gregorian date in format : YYYY-mm-dd*
{
  return transNumber (gregorian_to_jalali(substr($gregorianDate, 0, 4),substr($gregorianDate, 5, 2),substr($gregorianDate, 8, 2)).substr($gregorianDate, 10));
}
function jl_date($gregorianDate) {
  return jalaliLongDate($gregorianDate);
}
function jalaliLongDate($gregorianDate)
//input format is gregorian date time in format : YYYY-mm-dd H:M*
{
  return transNumber (gregorian_to_jalali(substr($gregorianDate, 0, 4),substr($gregorianDate, 5, 2),substr($gregorianDate, 8, 2), 7)." ساعت ".substr($gregorianDate, 10));
}
function localeDate( $dateStr, $format='short' ) {
  switch ($format) {
      case 'short':
	if ( $_SESSION['locale']=='fa' )
	  return jalaliDate($dateStr);
	return $dateStr;
      case 'long':
	if ( $_SESSION['locale']=='fa' )
	  return jl_date($dateStr);

	//NOTE: should be implemented
	return $dateStr;
  }
}

?>