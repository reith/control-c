/*
  REITH: Interactive form for add new exercise.
  DANGER It must be portable but is not. problem should be in listner for IE!
*/
function addExercise(number)
{
  var exFieldSet = document.createElement("fieldset");
  var exNum = document.createElement("legend");
  var Ltitle = document.createElement("label");
  var title = document.createElement("input");
  var Lexplain = document.createElement("label");
  var explain = document.createElement("textarea");
  var eraseSpaceL = document.createElement("label");
  var FeraseSpaceL = document.createElement("label");
  var LeraseSpaceL = document.createElement("label");
  var multiSpaceL = document.createElement("label");
  var emptyLineL = document.createElement("label");
  var caseSenseL = document.createElement("label");   
  var eraseSpace = document.createElement("input");
  var FeraseSpace = document.createElement("input");
  var LeraseSpace = document.createElement("input");
  var multiSpace = document.createElement("input");
  var emptyLine = document.createElement("input");
  var caseSense = document.createElement("input");   
  var exW = document.createElement("input");
  var exWL = document.createElement("label");
  var timeChangeL = document.createElement("label");   
  var timeChange = document.createElement("input");
  var newTimeL = document.createElement("label");   
  var newTime = document.createElement("input");
  var newTC = document.createElement("input");
  var tcC = document.createElement("input");
  var tcDIV = document.createElement("div");
  
  exFieldSet.setAttribute("id", "ex_"+number);
  
  exNum.innerHTML = "تمرین شماره‌ی "+Number(number);
  
  Ltitle.innerHTML = "عنوان تمرین: ";
  
  title.setAttribute("name", "title_"+number);
  title.setAttribute("type", "text");
  title.setAttribute("size", 30);
  title.setAttribute("maxlength", 60);
  
  Lexplain.innerHTML = "شرح تمرین: ";
  
  explain.setAttribute("name", "explain_"+number);
  explain.setAttribute("rows", 10);
  explain.setAttribute("cols", 50);
  
  eraseSpace.setAttribute("type", "checkbox");
  eraseSpace.setAttribute("name", "delBlank_"+number);
  eraseSpaceL.innerHTML = "نادیده گرفتن  کلیه‌ی فاصله‌ها";
  
  multiSpace.setAttribute("type", "checkbox");
  multiSpace.setAttribute("name", "delMultiBlank_"+number);
  multiSpace.checked=true;
  multiSpaceL.innerHTML = "نادیده گرفتن تفاوت در تعداد فاصله‌ها";
  
  FeraseSpace.setAttribute("type", "checkbox");
  FeraseSpace.setAttribute("name", "delBlankFL_"+number);
  FeraseSpace.checked=true;
  FeraseSpaceL.innerHTML = "نادیده گرفتن  فاصله‌های ابتدای خط";
  
  LeraseSpace.setAttribute("type", "checkbox");
  LeraseSpace.setAttribute("name", "delBlankEL_"+number);
  LeraseSpace.checked=true;
  LeraseSpaceL.innerHTML = "نادیده گرفتن  فاصله‌های انتهای خط";
  
  emptyLine.setAttribute("type", "checkbox");
  emptyLine.setAttribute("name", "delEmptyL_"+number);
  emptyLine.checked=true;
  emptyLineL.innerHTML = "نادیده گرفتن خطوط خالی";

  caseSense.setAttribute("type", "checkbox");
  caseSense.setAttribute("name", "caseSens_"+number);
  caseSenseL.innerHTML = "حساسیت نسبت به حروف بزرگ و کوچک";
  
  exW.setAttribute("type", "text");
  exW.setAttribute("name", "exW_"+number);
  exW.setAttribute("size", 2);
  exW.setAttribute("maxlength", 2);
  exW.setAttribute("value",1);
  exWL.innerHTML = "ضریب تمرین: ";
  
  timeChangeL.innerHTML = "تغییر بیشینه‌ی زمان اجرا به "
  timeChange.setAttribute("type", "checkbox");
  timeChange.setAttribute("name",  "maxRTB_"+number);
  timeChange.checked=false;
  
  newTimeL.innerHTML = "زمان جدید: ";
  newTime.setAttribute("type", "text");
  newTime.setAttribute("name", "maxRT_"+number);
  newTime.setAttribute("size", 3);
  newTime.setAttribute("maxlength", 3);
  newTime.disabled=true;
  
  newTC.setAttribute("type", "button");
  newTC.setAttribute("name", "addTC_"+number);
  newTC.setAttribute("value", "یک ورودی اضافه کن");

  tcC.setAttribute("type", "hidden");
  tcC.setAttribute("name", "tcCount_"+number);
  tcC.setAttribute("value", 0);
  
  tcDIV.setAttribute("id", "tcDIV_"+number);
  
  document.exDetails.appendChild(exFieldSet);
  document.getElementById("ex_"+number).appendChild(exNum);
  document.getElementById("ex_"+number).appendChild(Ltitle);
  document.getElementById("ex_"+number).appendChild(title);
  document.getElementById("ex_"+number).appendChild(document.createElement("br"));
  document.getElementById("ex_"+number).appendChild(Lexplain);
  document.getElementById("ex_"+number).appendChild(document.createElement("br"));
  document.getElementById("ex_"+number).appendChild(explain);
  document.getElementById("ex_"+number).appendChild(document.createElement("br"));
  document.getElementById("ex_"+number).appendChild(document.createElement("br"));
  document.getElementById("ex_"+number).appendChild(eraseSpace);
  document.getElementById("ex_"+number).appendChild(eraseSpaceL);
  document.getElementById("ex_"+number).appendChild(document.createElement("br"));
  document.getElementById("ex_"+number).appendChild(multiSpace);
  document.getElementById("ex_"+number).appendChild(multiSpaceL);
  document.getElementById("ex_"+number).appendChild(document.createElement("br"));
  document.getElementById("ex_"+number).appendChild(FeraseSpace);
  document.getElementById("ex_"+number).appendChild(FeraseSpaceL);
  document.getElementById("ex_"+number).appendChild(document.createElement("br"));
  document.getElementById("ex_"+number).appendChild(LeraseSpace);
  document.getElementById("ex_"+number).appendChild(LeraseSpaceL);
  document.getElementById("ex_"+number).appendChild(document.createElement("br"));
  document.getElementById("ex_"+number).appendChild(emptyLine);
  document.getElementById("ex_"+number).appendChild(emptyLineL);
  document.getElementById("ex_"+number).appendChild(document.createElement("br"));
  document.getElementById("ex_"+number).appendChild(caseSense);
  document.getElementById("ex_"+number).appendChild(caseSenseL);
  document.getElementById("ex_"+number).appendChild(document.createElement("br"));
  document.getElementById("ex_"+number).appendChild(exWL);
  document.getElementById("ex_"+number).appendChild(exW);
  document.getElementById("ex_"+number).appendChild(document.createElement("br"));
  document.getElementById("ex_"+number).appendChild(timeChange);
  document.getElementById("ex_"+number).appendChild(timeChangeL);
  document.getElementById("ex_"+number).appendChild(newTimeL);
  document.getElementById("ex_"+number).appendChild(newTime);
  document.getElementById("ex_"+number).appendChild(tcDIV);
  document.getElementById("tcDIV_"+number).appendChild(newTC);
  document.getElementById("tcDIV_"+number).appendChild(document.createElement("br"));
  document.getElementById("ex_"+number).appendChild(tcC);
  document.getElementById("ex_"+number).appendChild(document.createElement("br"));
  document.getElementById("ex_"+number).appendChild(document.createElement("br"));
 
  if (window.addEventListener) {
       document.getElementsByName("delBlank_"+number)[0].addEventListener("change", function () {delBlankChange(number)}, true);
    document.getElementsByName("maxRTB_"+number)[0].addEventListener("change", function () {maxRTBChange(number)}, true);
    document.getElementsByName("addTC_"+number)[0].addEventListener("click", function () {add1TC(number)}, true);
   } 
//WHY IE DON'T WORKS?
//   else if (window.attachEvent) {
//     document.getElementsByName("delBlank_"+number)[0].attachEvent("onchange", function () {delBlankChange(number)});
//     document.getElementsByName("maxRTB_"+number)[0].attachEvent("onchange", function () {maxRTBChange(number)});
//     document.getElementsByName("addTC_"+number)[0].attachEvent("onclick", function () {add1TC(number)});
//    }
    else {
  document.getElementsByName ("delBlank_"+number)[0].onchange = function() {delBlankChange(number)};
  document.getElementsByName ("maxRTB_"+number)[0].onchange = function() {maxRTBChange(number)};
  document.getElementsByName ("addTC_"+number)[0].onclick = function() {add1TC(number)};
    }

}
function delBlankChange(number)
{
  document.getElementsByName("delBlankFL_"+number)[0].disabled=document.getElementsByName("delBlank_"+number)[0].checked;
  document.getElementsByName("delMultiBlank_"+number)[0].disabled=document.getElementsByName("delBlank_"+number)[0].checked;
  document.getElementsByName("delBlankEL_"+number)[0].disabled=document.getElementsByName("delBlank_"+number)[0].checked;
  document.getElementsByName("delEmptyL_"+number)[0].disabled=document.getElementsByName("delBlank_"+number)[0].checked;
  if (document.getElementsByName("delBlank_"+number)[0].checked)
    document.getElementsByName("delMultiBlank_"+number)[0].checked=document.getElementsByName("delBlankFL_"+number)[0].checked=document.getElementsByName("delBlankEL_"+number)[0].checked=document.getElementsByName("delEmptyL_"+number)[0].checked=true;
}

function maxRTBChange(number)
{
  document.getElementsByName("maxRT_"+number)[0].disabled=!document.getElementsByName("maxRTB_"+number)[0].checked;
}

function add1TC(number)
{
  //1# add one to countainer
  document.getElementsByName("tcCount_"+number)[0].value ++;
  var currentTC = document.getElementsByName("tcCount_"+number)[0].value;
  
  //2# create nested div
  nestedDIV = document.createElement ("div");
  nestedDIV.setAttribute("id", "nDIV_"+number+"_"+ currentTC);
  document.getElementById("tcDIV_"+number).appendChild(nestedDIV);
   
  //3# how much is it's weight?
  tcW = document.createElement ("input");
  tcWL = document.createElement ("label");
  tcWL.innerHTML = "ضریب ورودی شماره‌ی "+currentTC+":     ";
  tcW.setAttribute("type", "text");
  tcW.setAttribute("name", "tcW_"+number+"_"+currentTC);
  tcW.setAttribute("size", 2);
  tcW.setAttribute("value", 1);
  tcW.setAttribute("maxlength", 2);
  document.getElementById( "nDIV_"+number+"_"+ currentTC).appendChild(tcWL);
  document.getElementById( "nDIV_"+number+"_"+ currentTC).appendChild(tcW);
  document.getElementById( "nDIV_"+number+"_"+ currentTC).appendChild(document.createElement("br"));
  
  //4# test case standard input
  tcIL = document.createElement("label");
  tcIL.innerHTML = "ورودی استاندارد شماره‌ی "+currentTC+":";
  tcI = document.createElement("textarea");
  tcI.setAttribute("rows", 3);
  tcI.setAttribute("dir", "ltr");
  tcI.setAttribute("cols", 30);
  tcI.setAttribute("name", "tcI_"+number+"_"+ currentTC);
  document.getElementById( "nDIV_"+number+"_"+ currentTC).appendChild(tcIL);
  document.getElementById( "nDIV_"+number+"_"+ currentTC).appendChild(document.createElement("br"));
  document.getElementById( "nDIV_"+number+"_"+ currentTC).appendChild(tcI);
  document.getElementById( "nDIV_"+number+"_"+ currentTC).appendChild(document.createElement("br"));

  //5# testcase standard output
  tcOL = document.createElement("label");
  tcOL.innerHTML = "خروجی مطلوب شماره‌ی "+currentTC+":";
  tcO = document.createElement("textarea");
  tcO.setAttribute("rows", 3);
  tcO.setAttribute("dir", "ltr");
  tcO.setAttribute("cols", 30);
  tcO.setAttribute("name", "tcO_"+number+"_"+ currentTC);
  document.getElementById( "nDIV_"+number+"_"+ currentTC).appendChild(tcOL);
  document.getElementById( "nDIV_"+number+"_"+ currentTC).appendChild(document.createElement("br"));
  document.getElementById( "nDIV_"+number+"_"+ currentTC).appendChild(tcO);
  document.exDetails.appendChild(document.createElement("br"));
}
