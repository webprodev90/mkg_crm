var checkbox;

function get_current_page() {
    const path = window.location.href;
    const parts = path.split('/');
    const page = parts[parts.length - 1];
    return page;
}

event_start('click', '#selrows', function (e) {
	
	checkbox = document.getElementsByName("list");

	var str = "";
	var cntstr = 0;

	for(var i=0; i<checkbox.length; i++){

		if(checkbox[i].checked) {
			str+="'"+checkbox[i].value+"',";
			cntstr = cntstr + 1;
		}
		
		//var usernames = $('#user_names').val();
		$('[name="sel_rows"]').val(str);
	}		

	if (cntstr > 0 || get_current_page().startsWith('unprocessed-base-7.php?p=10')) {
		document.getElementById("count_rows").hidden = true;
		document.getElementById("count_rows").required = false;
		document.getElementsByClassName('textcnt')[0].textContent = cntstr;
	} else {
		document.getElementById("count_rows").hidden = false;
		document.getElementById("count_rows").required = true;
		document.getElementsByClassName('textcnt')[0].textContent = "";
	}
    
});

event_start('click', '#exportcsv', function (e) {
	
	checkbox = document.getElementsByName("list");

	var str = "";
	var cntstr = 0;

	for(var i=0; i<checkbox.length; i++){

		if(checkbox[i].checked) {
			str+="'"+checkbox[i].value+"',";
			cntstr = cntstr + 1;
		}
		
		//var usernames = $('#user_names').val();
		$('[name="sel_rows"]').val(str);
	}		

	if (cntstr > 0) {
		document.getElementById("count_rows2").hidden = true;
		document.getElementById("count_rows2").required = false;
		document.getElementsByClassName('textcnt2')[0].textContent = cntstr;
	} else {
		document.getElementById("count_rows2").hidden = false;
		document.getElementById("count_rows2").required = true;
		document.getElementsByClassName('textcnt2')[0].textContent = "";
	}
    
});


/* 
		window.onload = function() {

		var checkbox;

		to_send.onclick = function() {

				checkbox = document.getElementsByName("list");

				var str = "";

				for(var i=0; i<checkbox.length; i++){

				if(checkbox[i].checked) {
					str+="'"+checkbox[i].value+"',";
				}
				var usernames = $('#user_names').val();
				
				}

				$.ajax({
                    method: "POST",
					//dataType: 'json',
                    url: "update-u.php",
                    data: {unp_id: str, i_value: usernames},
                    success: function(data) {
						//alert(data);
						location.reload();
                    }
                });
				
				//alert($('#user_names').val());

			}

		};
		*/