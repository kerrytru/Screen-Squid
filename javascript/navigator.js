// Javascripts

/* Change these values */
//var srv = 0;  //srvname[0] from config.php

//if srvname[1], than you must change srvnum

// возвращает куки с указанным name,
// или 0, если ничего не найдено
function getCookie(name) {
  let matches = document.cookie.match(new RegExp(
    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : 0;
}


function deleteCookie( name ) {
 document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
parent.right.location.href='reports.php';
UpdateLeftMenu(999);
}



function LeftRightDateSwitch(idReport, dom, lr)
{
  var stringdate=window.document.fastdateswitch_form.date_field_hidden.value;
  var arraydate=stringdate.split('-');
  var leftdate=new Date(arraydate[2],arraydate[1]-1,arraydate[0]);
  var rightdate=new Date(arraydate[2],arraydate[1]-1,arraydate[0]);
  var ldate;
  var rdate;

  
  
  if(dom=='day') {
    leftdate.setDate(leftdate.getDate()-1);
    rightdate.setDate(rightdate.getDate()+1);
  }

  if(dom=='month') {
    leftdate.setMonth(leftdate.getMonth()-1);
    rightdate.setMonth(rightdate.getMonth()+1);
  }

  var mp1l=leftdate.getMonth()+1;
  var mp1r=rightdate.getMonth()+1;
  
  if(mp1l<10) mp1l='0'+mp1l;
  
  if(mp1r<10) mp1r='0'+mp1r;

  ldate=leftdate.getDate()+'-'+mp1l+'-'+leftdate.getFullYear();
  rdate=rightdate.getDate()+'-'+mp1r+'-'+rightdate.getFullYear();

  if(lr=='l')
    window.document.fastdateswitch_form.date_field.value=ldate;
  else if(lr=='r')
    window.document.fastdateswitch_form.date_field.value=rdate;
  else
    window.document.fastdateswitch_form.date_field.value=lr;

  FastDateSwitch(idReport,dom);
}

function FastDateSwitch(idReport, dom)
{
  var srv;
  
  srv = getCookie('srv');

  if(window.document.fastdateswitch_form.date_field.value=='')
    parent.right.location.href='reports.php?srv='+srv+'&id='+idReport
+'&date='+window.document.fastdateswitch_form.date_field_hidden.value
+'&dom='+dom
+'&login='+window.document.fastdateswitch_form.login_field_hidden.value
+'&loginname='+window.document.fastdateswitch_form.loginname_field_hidden.value
+'&ip='+window.document.fastdateswitch_form.ip_field_hidden.value
+'&ipname='+window.document.fastdateswitch_form.ipname_field_hidden.value
+'&site='+window.document.fastdateswitch_form.site_field_hidden.value
+'&group='+window.document.fastdateswitch_form.group_field_hidden.value
+'&groupname='+window.document.fastdateswitch_form.groupname_field_hidden.value
+'&typeid='+window.document.fastdateswitch_form.typeid_field_hidden.value
+'&httpstatus='+window.document.fastdateswitch_form.httpstatus_field_hidden.value
+'&httpname='+window.document.fastdateswitch_form.httpname_field_hidden.value
+'&loiid='+window.document.fastdateswitch_form.loiid_field_hidden.value
+'&loiname='+window.document.fastdateswitch_form.loiname_field_hidden.value;
  else
    parent.right.location.href='reports.php?srv='+srv+'&id='+idReport
+'&date='+window.document.fastdateswitch_form.date_field.value
+'&dom='+dom
+'&login='+window.document.fastdateswitch_form.login_field_hidden.value
+'&loginname='+window.document.fastdateswitch_form.loginname_field_hidden.value
+'&ip='+window.document.fastdateswitch_form.ip_field_hidden.value
+'&ipname='+window.document.fastdateswitch_form.ipname_field_hidden.value
+'&site='+window.document.fastdateswitch_form.site_field_hidden.value
+'&group='+window.document.fastdateswitch_form.group_field_hidden.value
+'&groupname='+window.document.fastdateswitch_form.groupname_field_hidden.value
+'&typeid='+window.document.fastdateswitch_form.typeid_field_hidden.value
+'&httpstatus='+window.document.fastdateswitch_form.httpstatus_field_hidden.value
+'&httpname='+window.document.fastdateswitch_form.httpname_field_hidden.value
+'&loiid='+window.document.fastdateswitch_form.loiid_field_hidden.value
+'&loiname='+window.document.fastdateswitch_form.loiname_field_hidden.value;
}


//JS function to open reports page with some additional parameters.
//idReport - id of report
//dom - day or month report
//id - id login or id ipaddress or id group
//idname - visible name login(Yoda) or name ipaddress(172.16.120.33) or name group(StarWars club)
//idsign - login (0) or ipaddress (1) or group login (3) or group ipaddress (4) or httpstatus id >4
//par1 - sitename if report need it or httpstatus name



function GoPartlyReports(idReport, dom, id, idname, idsign, par1)
{
var srv;
  srv = getCookie('srv');

	if(idsign==0)
	{
		parent.right.location.href='reports.php?srv='+srv+'&id='+idReport+
		'&date='+window.document.fastdateswitch_form.date_field_hidden.value
		+'&dom='+dom
		+'&login='+id
		+'&loginname='+idname
		+'&site='+par1;
	}
	if(idsign==1)
	{
		parent.right.location.href='reports.php?srv='+srv+'&id='+idReport
		+'&date='+window.document.fastdateswitch_form.date_field_hidden.value
		+'&dom='+dom
		+'&ip='+id
		+'&ipname='+idname
		+'&site='+par1;
	}
	if(idsign==3)
	{
		parent.right.location.href='reports.php?srv='+srv+'&id='+idReport
		+'&date='+window.document.fastdateswitch_form.date_field_hidden.value
		+'&dom='+dom
		+'&group='+id
		+'&groupname='+idname
		+'&typeid=0';
	}
	if(idsign==4)
	{
		parent.right.location.href='reports.php?srv='+srv+'&id='+idReport
		+'&date='+window.document.fastdateswitch_form.date_field_hidden.value
		+'&dom='+dom
		+'&group='+id
		+'&groupname='+idname
		+'&typeid=1';
	}
	if(idsign>4)
	{
		parent.right.location.href='reports.php?srv='+srv+'&id='+idReport
		+'&date='+window.document.fastdateswitch_form.date_field_hidden.value
		+'&dom='+dom
		+'&httpstatus='+id
		+'&httpname='+idname
		+'&loiid='+idsign
		+'&loiname='+par1;
	}

}


function UpdateLeftMenu(id)
{
if(id==999)
{
	parent.left.location.href='../mainmenu.php';
}

else
	{
		var srv;
		var namelogin;
		  srv = getCookie('srv');
		  namelogin = getCookie('namelogin');
		  parent.left.location.href='../left.php?srv='+srv+'&id='+id+'&namelogin='+namelogin;
	}
}



// Javascripts END
