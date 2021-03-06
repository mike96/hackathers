<html>
<head>

<link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/css/bootstrap-datetimepicker.min.css'); ?>" rel="stylesheet">


<script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/moment-with-locales.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap-datetimepicker.js'); ?>"></script>
<style>

	table.lc-list-table > tbody > tr:nth-child(1) {
		background: #5b9bd5;
    	color: black;
	}

	table.lc-list-table > tbody > tr:nth-child(2n+2) {
		background: #DDEBF7;
    	color: black;
	}

	table.lc-list-table > tbody > tr:nth-child(2n+2):hover {
		background: #c1daf0;
	}

	table.lc-list-table > tbody > tr:nth-child(2n+3) {
		background: #f5faff;
    	color: black;
	}

	table.lc-list-table > tbody > tr:nth-child(2n+3).collapsing {
	    -webkit-transition: none;
	    transition: none;
	}

	.hidden {
		display: none;
	}
</style>

<script>
var reference_id = -1;
var current_date = "";
var temp_date = "";

function add_bc(){
	var date_str = document.getElementById("ab_board_change_date").value;

	var post_data = "";

	post_data += "board_change_date=" + date_str + "&";
	post_data += "lc_id=" + reference_id;

	var xhr = new XMLHttpRequest();

	xhr.open("POST", "add_bc");

	//xhr.addEventListener("loadend", function(){console.log(this.responseText)});
	xhr.addEventListener("load", function(){update_table();});
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(post_data);

	reference_id = -1;
}

function add_pos(){
	var inputs = document.getElementsByClassName("add-pos");

	var post_data = "";

	for(var i = 0; i < inputs.length; i++)
	{
		post_data += inputs[i].name + "=" + inputs[i].value + "&";
	}
	post_data += "board_change_id=" + reference_id;

	var xhr = new XMLHttpRequest();

	xhr.open("POST", "add_pos");

	//xhr.addEventListener("loadend", function(){console.log(this.responseText)});
	xhr.addEventListener("load", function(){update_table();});
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(post_data);

	reference_id = -1;
}

function add_lc(){
	var inputs = document.getElementsByClassName("add-lc");

	var post_data = "";

	for(var i = 0; i < inputs.length; i++)
	{
		post_data += inputs[i].name + "=" + inputs[i].value + (i != inputs.length - 1? "&" : "");
	}

	var xhr = new XMLHttpRequest();

	xhr.open("POST", "add_lc");

	//xhr.addEventListener("loadend", function(){console.log(this.responseText)});
	xhr.addEventListener("load", function(){update_table();});
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(post_data);
}

function toggle_collapsed(id)
{
	$('#' + id).toggleClass('hidden');
}

function show_collapsed(id)
{
	$('#' + id).removeClass('hidden');
}

function edit_lc_begin(id)
{
	keys = ["lc_internal_name",
			"lc_reg_name",
			"lc_connection",
			"lc_address",
			"lc_post_code",
			"lc_city",
			"lc_email",
			"lc_site"];

	for(var i = 0; i < keys.length; i++)
	{
		var element = document.getElementById("display_" + id + "_" + keys[i]);
		
		var current_value = element.innerHTML;
		//document.getElementById("el_" + keys[i]).value = current_value;
		element.innerHTML = "<input type='text' onclick='event.stopPropagation()' class='form-control edit-lc' id='el_" + keys[i] + "' name='" + keys[i] + "' value='" + current_value + "'>"

		if(i == 0)
		{
			element.firstChild.focus();
		}
	}

	toolbar = document.getElementById("toolbar_" + id);
	var html = "";
	html += "<button type='button' class='btn btn-primary' onclick='edit_lc()'>Save</button>";
	html += "<button type='button' class='btn btn-default' onclick='update_table();'>Cancel</button>";

	toolbar.innerHTML = html;

	show_collapsed("collapseExample" + id);

	reference_id = id;
}

function edit_lc(){
	var inputs = document.getElementsByClassName("edit-lc");

	var post_data = "";

	for(var i = 0; i < inputs.length; i++)
	{
		post_data += inputs[i].name + "=" + inputs[i].value + "&";
	}

	post_data += "lc_id=" + reference_id;

	var xhr = new XMLHttpRequest();

	xhr.open("POST", "edit_lc");

	//xhr.addEventListener("loadend", function(){console.log(this.responseText)});
	xhr.addEventListener("load", function(){update_table();});
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(post_data);

	reference_id = -1;
}

function shred_lc(){
	var xhr = new XMLHttpRequest();
	xhr.open("get", "shred_lc/" + reference_id);
	xhr.addEventListener("load", function(){update_table();});
	xhr.send();
	reference_id = -1;
}

function get_lc_list(callback)
{
	var xhr = new XMLHttpRequest();
	xhr.open("get", "lcs_json");

	xhr.addEventListener("load", function(){callback(JSON.parse(xhr.responseText));});

	xhr.send();
}

function get_current_board_change(lc_id, callback)
{
	var xhr = new XMLHttpRequest();
	xhr.open("post", "board_change_json/" + lc_id);

	xhr.addEventListener("load", function(){callback(JSON.parse(xhr.responseText));});
	var data = "";
	if(current_date != "")
		data += "date=" + current_date;

	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(data);
}



function update_table()
{
	var table = document.getElementById("lc_list_table");

	get_lc_list(function(lc_list)
	{

		var html = "<tr>" +
					"<th>Internal name</th>" +
					"<th>Registration name</th>" +
					"<th>City</th>" +
					"<th>Address</th>" +
					"<th>Post code</th>" +
					"<th>Email</th>" +
					"<th>Website</th>" +
					"<th></th>" +
					"</tr>";

		for(var i = 0; i < lc_list.length; i++)
		{
			var id = lc_list[i].lc_id;

			html += "<tr class='lc-id-" + id + "' id='bla" + id + "' onclick='toggle_collapsed(\"collapseExample" + id + "\")'>";


			html += "<td role='button' id='display_" + id + "_lc_internal_name'>" 
					+ lc_list[i].lc_internal_name + "</td>";
			html += "<td role='button' id='display_" + id + "_lc_reg_name'>" 
					+ lc_list[i].lc_reg_name + "</td>";
			html += "<td role='button' id='display_" + id + "_lc_city'>" 
					+ lc_list[i].lc_city + "</td>";
			html += "<td role='button' id='display_" + id + "_lc_address'>" 
					+ lc_list[i].lc_address + "</td>";
			html += "<td role='button' id='display_" + id + "_lc_post_code'>" 
					+ lc_list[i].lc_post_code + "</td>";
			html += "<td role='button' id='display_" + id + "_lc_email'>" 
					+ lc_list[i].lc_email + "</td>";
			html += "<td role='button' id='display_" + id + "_lc_site'>" 
					+ lc_list[i].lc_site + "</td>";


			// Buttons

			html += "<td id='toolbar_" + id + "'>";
			html += "<div class='btn-toolbar' role='toolbar'>";
			html += "<div class='btn-group' role='group'>";
			html += "<button type='button' class='btn btn-default btn-sm' onclick='edit_lc_begin(" +  id + ");event.stopPropagation();' data-toggle='modall' data-target='.edit-lc-modall'>";
			html += "<span class='glyphicon glyphicon-pencil' aria-hidden='true'></span>";
			html += "</button>";

			html += "<a href='history/" + id + "'><button type='button' class='btn btn-default btn-lg btn-sm'>";
			html += "<span class='glyphicon glyphicon-film'></span>";
			html += "</button></a>";
			html += "</div>";

			html += "<button type='button' class='btn btn-default btn-sm' onclick='reference_id=" +  id + ";' data-toggle='modal' data-target='.shred-lc-modal'>";
			html += "<span class='glyphicon glyphicon-fire' aria-hidden='true'></span>";
			html += "</button>";
			html += "</div>";
			html += "</td>";

			// buttons end


			html += "</tr>";

			html += "<tr class='hidden' id='collapseExample" + id + "'><td colspan='9'>";
			html += "<div id='display_" + id + "_lc_connection'>" +lc_list[i].lc_connection + "</div><br>";

			html += "<div class='row'>";
			html += "<button type='button' class='btn btn-default btn-sm col-sm-offset-11' onclick='reference_id=" +  id + ";' data-toggle='modal' data-target='.add-bc-modal'>";
			html += "<span class='glyphicon glyphicon-plus' aria-hidden='true'></span> Board change";
			html += "</button>";
			html += "</div><br>";

			html += "<div class='panel panel-default'>";
			html += "<div class='panel-heading' id='current_board_date" + id + "'>" + "</div>";

			html += "<table class='table table-hover current-board-table' id='current_board" + id + "'>";
			html += "</table>";
			html += "</div>";
			html += "</td>";

			html += "</tr>";
		}
		table.innerHTML = html;

		for(var i = 0; i < lc_list.length; i++)
		{
			var id = lc_list[i].lc_id;
			update_current_board(id);
		}
	});

}


function update_current_board(lc_id)
{
	get_current_board_change(lc_id, function(data)
	{
		header = document.getElementById("current_board_date" + lc_id);
		table = document.getElementById("current_board" + lc_id);
		if(data.board_change)
		{
			var html = "";
			html += "<div class='container-fluid'><div class='row'><div class='col-sm-3'>Current board (since " + data.board_change.board_change_date + ")</div>";
			html += "<button type='button' class='btn btn-default btn-xs col-sm-1 col-sm-offset-8' onclick='reference_id=" +  data.board_change.board_change_id + ";' data-toggle='modal' data-target='.add-pos-modal'>";
			html += "<span class='glyphicon glyphicon-plus' aria-hidden='true'></span> Position";
			html += "</button></div></div>";
			header.innerHTML = html;//"Current board (since " + data.board_change.board_change_date + ")";

			if(data.positions.length)
			{
				var html =  "<tr>" +
							"<th>Position</th>" +
							"<th>Name</th>" +
							"<th>E-mail</th>" +
							"<th>Phone</th>" +
							"</tr>";

				for(var i = 0; i < data.positions.length; i++)
				{
					html += "<tr>";
					
					html += "<td>" + data.positions[i].position_title + "</td>";
					html += "<td>" + data.positions[i].position_name + "</td>";
					html += "<td>" + data.positions[i].position_mail + "</td>";
					html += "<td>" + data.positions[i].position_phone + "</td>";

					html += "</tr>";
				}

				table.innerHTML = html;
			}
			else
			{
				table.innerHTML = "";
			}
		}
		else
		{
			header.innerHTML = "";
			table.innerHTML = "";
		}
	});
}
</script>

</head>
<body onload="update_table();">

<nav class="navbar navbar-static-top navbar-inverse">
	<div class="container-fluid">

		<ul class="nav navbar-nav navbar-right">
		<li>
			<a href="logout">
				Log out
			</a>
			</li>
		</ul>
	</div>
</nav>
<div class="container-fluid">

<div class="row"> 
	<div class="col-sm-12">
		  <h1 class="page-header">List of commitments</h1>
	</div>
</div>	
  

  <br>
  

<div class="row"> 
    <div class="col-sm-2" style="height:130px;">
        <div class="form-group">
            <div class='input-group date' id='datetimepicker10'>
                <input type='text' class="form-control" />
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar">
                    </span>
                </span>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(function () {
            $('#datetimepicker10').datetimepicker({
                viewMode: 'months',
                format: 'YYYY-MM-DD',
                showTodayButton: true,
            });

            $('#datetimepicker10').on('dp.change', function (ev) {
			    temp_date = ev.date.format("YYYY-MM-DD");
			});
        });


    </script>
    <button type='button' class='btn btn-default col-sm-1' onclick='current_date = temp_date; update_table();'>
		<span class='glyphicon glyphicon-transfer' aria-hidden='true'></span> Time machine
	</button>

	<button type='button' class='btn btn-default col-sm-offset-8' data-toggle='modal' data-target='.add-lc-modal'>
		<span class='glyphicon glyphicon-plus' aria-hidden='true'></span> Commitment
	</button>
</div>

<div class="row"> 
<table class='table table-striped lc-list-table' id='lc_list_table'>
</table>
</div>
</div>


<div class="modal fade add-lc-modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
                <button type="button" class="close" 
                   data-dismiss="modal">
                       <span>&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    Add LC
                </h4>
            </div>
			
			<form role="form" action="add_lc" method="post">

            <div class="modal-body">
					<div class="form-group">
						<label for="al_lc_internal_name">Internal name:</label>
						<input type="text" class="form-control add-lc" id="al_lc_internal_name" name="lc_internal_name">
					</div>
					<div class="form-group">
						<label for="al_lc_reg_name">Registration name:</label>
						<input type="text" class="form-control add-lc" id="al_lc_reg_name" name="lc_reg_name">
					</div>
					<div class="form-group">
						<label for="al_lc_connection">Connection to EESTEC:</label>
						<textarea class="form-control add-lc" id="al_lc_connection" name="lc_connection"></textarea>
					</div>
					<div class="form-group">
						<label for="al_lc_address">Address:</label>
						<input type="text" class="form-control add-lc" id="al_lc_address" name="lc_address">
					</div>
					<div class="form-group">
						<label for="al_lc_post_code">Post code:</label>
						<input type="text" class="form-control add-lc" id="al_lc_post_code" name="lc_post_code">
					</div>
					<div class="form-group">
						<label for="al_lc_city">City:</label>
						<input type="text" class="form-control add-lc" id="al_lc_city" name="lc_city">
					</div>
					<div class="form-group">
						<label for="al_lc_email">E-mail:</label>
						<input type="text" class="form-control add-lc" id="al_lc_email" name="lc_email">
					</div>
					<div class="form-group">
						<label for="al_lc_site">Website:</label>
						<input type="text" class="form-control add-lc" id="al_lc_site" name="lc_site">
					</div>
					

			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="add_lc()" data-dismiss="modal">Add</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			</div>
			</form>

		</div>
	</div>
</div>


<div class="modal fade add-pos-modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
                <button type="button" class="close" 
                   data-dismiss="modal">
                       <span>&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    Add position
                </h4>
            </div>
			
			<form role="form" action="add_pos" method="post">

            <div class="modal-body">
					<div class="form-group">
						<label for="ap_position_title">Title:</label>
						<input type="text" class="form-control add-pos" id="ap_position_title" name="position_title">
					</div>
					<div class="form-group">
						<label for="ap_position_name">Name:</label>
						<input type="text" class="form-control add-pos" id="ap_position_name" name="position_name">
					</div>
					<div class="form-group">
						<label for="ap_position_mail">E-Mail address:</label>
						<input type="text" class="form-control add-pos" id="ap_position_mail" name="position_mail">
					</div>
					<div class="form-group">
						<label for="ap_position_phone">Phone:</label>
						<input type="text" class="form-control add-pos" id="ap_position_phone" name="position_phone">
					</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="add_pos()" data-dismiss="modal">Add</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			</div>
			</form>

		</div>
	</div>
</div>


<div class="modal fade edit-lc-modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
                <button type="button" class="close" 
                   data-dismiss="modal">
                       <span>&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    Add Commitment
                </h4>
            </div>
<!--
			<form role="form" action="edit_lc" method="post">

            <div class="modal-body">
					<div class="form-group">
						<label for="el_lc_internal_name">Internal name:</label>
						<input type="text" class="form-control edit-lc" id="el_lc_internal_name" name="lc_internal_name">
					</div>
					<div class="form-group">
						<label for="el_lc_reg_name">Registration name:</label>
						<input type="text" class="form-control edit-lc" id="el_lc_reg_name" name="lc_reg_name">
					</div>
					<div class="form-group">
						<label for="el_lc_connection">Connection to EESTEC:</label>
						<input type="text" class="form-control edit-lc" id="el_lc_connection" name="lc_connection">
					</div>
					<div class="form-group">
						<label for="el_lc_address">Address:</label>
						<input type="text" class="form-control edit-lc" id="el_lc_address" name="lc_address">
					</div>
					<div class="form-group">
						<label for="el_lc_post_code">Post code:</label>
						<input type="text" class="form-control edit-lc" id="el_lc_post_code" name="lc_post_code">
					</div>
					<div class="form-group">
						<label for="el_lc_city">City:</label>
						<input type="text" class="form-control edit-lc" id="el_lc_city" name="lc_city">
					</div>
					<div class="form-group">
						<label for="el_lc_email">E-mail:</label>
						<input type="text" class="form-control edit-lc" id="el_lc_email" name="lc_email">
					</div>
					<div class="form-group">
						<label for="el_lc_site">Website:</label>
						<input type="text" class="form-control edit-lc" id="el_lc_site" name="lc_site">
					</div>
					

			</div>
-->
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="edit_lc()" data-dismiss="modal">Save</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			</div>
			</form>

		</div>
	</div>
</div>

<div class="modal fade shred-lc-modal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
			    <h4 class="modal-title">
					Shred LC?
				</h4>
			</div>

            <div class="modal-body">
            	Shredding means permanently deleting all data about this LC from the database.
            	There is no way to recover this information later.
            	<br>
            	Are you really sure this is what you want to do?
			</div>


			<div class="modal-footer">
				<button type="button" class="btn btn-danger" onclick="shred_lc()" data-dismiss="modal">Shred</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			</div>

		</div>
	</div>
</div>

<div class="modal fade add-bc-modal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
			    <h4 class="modal-title">
			    	Add board change
				</h4>
			</div>

            <div class="modal-body">
					<div class="form-group">
						<label for="ab_board_change_date">Board change date (YYYY-MM-DD):</label>
						<input type="text" class="form-control add-bc" id="ab_board_change_date" name="board_change_date">
					</div>
			</div>


			<div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="add_bc()" data-dismiss="modal">Add</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			</div>

		</div>
	</div>
</div>

</body>
</html>