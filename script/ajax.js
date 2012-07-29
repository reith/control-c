function createAjax()
{
	var result;
	if (XMLHttpRequest)
		try
		{
			result = new XMLHttpRequest();
		}
		catch (e)
		{
			result = false;
		}
	else if(window.ActiveXObject)
	//SHIT
	try
	{
		result = new ActiveXObject("Microsoft.XMLHTTP");
	}
	catch (e)
	{
		result = false;
		
	}	
	if (!result)
		window.alert ("Your borowser does not support AJAX, FireFox is your friend! (;");
	else
		return result;
}

function sendForm(url, formElementsName, succRedirect)
{
	//a) second all php posted fields received with p0..pn names
	//b) succ is redirect link for successful form process. php file must have NO output in this case at all.
	//v) all form fields encoded. use proper stripslash on php files.
	var httpRequest = createAjax();
	var formElements = document.getElementsByName(formElementsName);
	var postQuery="p0="+encodeURIComponent(formElements[0].value);
	for (var i=1; i< formElements.length ; i++)
		postQuery+="&p"+i+"="+encodeURIComponent(formElements[i].value);
	httpRequest.onreadystatechange = function() {
		if (httpRequest.readyState == 4)
		{
			document.getElementById("loading").className = "hidden";
			if (httpRequest.status == 200)
			{
				try
				{
					if (document.getElementById("alertMsg").innerHTML = httpRequest.responseText)
						showMsg();
					else
						window.location = succRedirect;
						
					var jsBlocks = document.getElementById("alertMsg").getElementsByTagName ("script");
					for (var i=0; i<jsBlocks.length; i++)
						eval(jsBlocks[i].innerHTML);
				}
				catch (e)
				{
					alert(e.toString());
				}
			}
			else
			{
				alert("Can't establish successful connection.");
			}
		}
		else if (httpRequest.readyState != 0)
			document.getElementById("loading").className = "";
	}

	httpRequest.open ("POST", url, true);
	httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	httpRequest.send(postQuery);
}

function refreshTable (url, placeholder, query)
{
	var httpRequest = createAjax();
	var getQuery;
	//very similiar. but seperated for easier changing.
	switch (query)
	{
		case "membership_request":
			getQuery="?course="+document.getElementById("course").value+"&sort="+document.getElementById("sort").value
			+"&order="+document.getElementById("order").value+"&from="+document.getElementById("from").value+"&limit="
			+document.getElementById("limit").value;
			break;
		case "student_course":
			getQuery="?view="+document.getElementById("view").value+"&sort="+document.getElementById("sort").value
			+"&order="+document.getElementById("order").value+"&from="+document.getElementById("from").value+"&limit="
			+document.getElementById("limit").value;
			break;
		case "student_exercise":
			getQuery=getQuery="?view="+document.getElementById("view").value+"&solved="+document.getElementById("solved").value
			+"&expired="+document.getElementById("expired").value+"&course="+document.getElementById("course").value+"&sort="
			+document.getElementById("sort").value
			+"&order="+document.getElementById("order").value+"&from="+document.getElementById("from").value+"&limit="
			+document.getElementById("limit").value;
			break;
		case "teacher_course":
			getQuery="?view="+document.getElementById("view").value+"&sort="+document.getElementById("sort").value
			+"&order="+document.getElementById("order").value+"&from="+document.getElementById("from").value+"&limit="
			+document.getElementById("limit").value;
			break;
		case "teacher_exercise":
			getQuery="?view="+document.getElementById("view").value+"&course="+document.getElementById("course").value+"&sort="+document.getElementById("sort").value
			+"&order="+document.getElementById("order").value+"&from="+document.getElementById("from").value+"&limit="
			+document.getElementById("limit").value;
			break;
		case "teacher_logs":
			getQuery="?view="+document.getElementById("view").value+"&course="+document.getElementById("course").value+"&sort="+document.getElementById("sort").value
			+"&order="+document.getElementById("order").value+"&from="+document.getElementById("from").value+"&limit="
			+document.getElementById("limit").value;
			break;
		case "teacher_student":
			getQuery="?course="+document.getElementById("course").value+"&sort="+document.getElementById("sort").value
			+"&order="+document.getElementById("order").value+"&from="+document.getElementById("from").value+"&limit="
			+document.getElementById("limit").value;
			break;
		default: alert("Reith: Hey! Bad Request!"); return;
	}
	httpRequest.onreadystatechange = function() {
		if (httpRequest.readyState == 4)
		{
			if (httpRequest.status == 200)
			{
				document.getElementById("loading").className = "hidden";
				try
				{
					document.getElementById(placeholder).innerHTML = httpRequest.responseText;
					var jsBlocks = document.getElementById(placeholder).getElementsByTagName ("script");
					for (var i=0; i<jsBlocks.length; i++)
						eval(jsBlocks[i].innerHTML);
				}
				catch (e)
				{
					alert(e.toString());
				}
			}
			else
			{
				alert("Can't establish successful connection.");
			}
		}
		else if (httpRequest.readyState != 0)
			document.getElementById("loading").className = "";
	}
	httpRequest.open ("GET", url+getQuery, true);
	httpRequest.send(null);
}