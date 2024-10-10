// Javascripts


// Javascripts END
function QuickFinder(tablename) {
	var input, filter, found, table, tr, td, i, j;
  input = document.getElementById("QInput");
  filter = input.value.toUpperCase();
	table = document.getElementById(tablename);
	
	tr = table.getElementsByTagName("tr");
	for (i = 1; i < tr.length; i++) {
		td = tr[i].getElementsByTagName("td");
		for (j = 1; j < td.length; j++) {
		 
			if ((td[j].innerHTML.toUpperCase().indexOf(filter) > -1)) {
		   
				found = true;
			}
		}
		if (found) {
			tr[i].classList.remove("hidden");
			found = false;
		} else {
		  tr[i].classList.add("hidden");

		}
	}
  

  
  }

  function ClearFilter(tablename) {
  
	document.getElementById("QInput").value = "";
	QuickFinder(tablename);
}

function launch_toast(msg_element) {
	var x = document.getElementById(msg_element)
    x.className = "show";
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 5000);
}

function UpdatePage(srv,id,actid,status)
{
	if(actid>0)
	parent.right.location.href='?srv='+srv+'&id='+id+'&actid='+actid+'';
else
parent.right.location.href='?srv='+srv+'&id='+id+'&status='+status+'';

}