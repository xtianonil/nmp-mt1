<?php
   echo $this->session->flashdata('email_sent');
   echo form_open('Support_page/send_mail');

   $this->css = <<<CSS
#container111 {
	position: relative;
	top: 10em;
	width: 35%;
	margin: 0 auto;
	box-shadow: 0px 2px 5px 0px rgba(0, 0, 0, 0.26);
}

.container{
	margin-top: 50px;
	padding-right:15px;
	padding-left:15px;
	margin-right:auto;
	margin-left:auto}
	@media (min-width:768px){
		.container{width:750px
	}
}
@media (min-width:992px)
{
	.container
	{
			width:970px
	}
	}
@media (min-width:1200px){
			.container
			{
				width:1170px
			}
		}
			.container-fluid
			{
				padding-right:15px;padding-left:15px;margin-right:auto;margin-left:auto
			}

form {
	/*background-color: lightsteelblue;*/
	padding: 3em;
}

form span {
	display: inline-block;
	min-width: 6em;
}

form div {
	margin-bottom: 1em;
}

.error {
	text-align: center;
	padding: 1em 3em;
	margin: 0;
}

form input[type=text], form input[type=password] {
	border: none;
	height: 2em;
	width: 98%;
	padding: 5px 0px;
	padding-left: 2%;
	color: black;
}

input:-webkit-autofill {
    -webkit-box-shadow: 0 0 0px 1000px white inset;
}

button {
	padding: 10px !important;
	width: 100%;
}

body {
  padding-top: 0px;
  padding-bottom: 40px;
  background-color: #eee;
}

.form-signin {
  max-width: 330px;
  padding: 15px;
  /*
  margin-top: 100px;
  margin-left: 40%;
  */
  margin: 0 auto;
}
.form-signin .form-signin-heading,
.form-signin .checkbox {
  margin-bottom: 10px;
}
.form-signin .checkbox {
  font-weight: normal;
}
.form-signin .form-control {
  position: relative;
  height: auto;
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
  
  font-size: 16px;
}
.form-signin .form-control:focus {
  z-index: 2;
}
.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}

CSS;
?>
<div class="container">
<div class="form-group">
<h3>Email Support</h3><br>
<table>
	<tr>	
		<td><label for="name">Name:</label><label for="name" style="color: red">*</label></td></td>
		<td><input class="form-control" name = "name" required /></td>
		<td></td>
	</tr>

	<tr>	
		<td><label for="email">Email:</label><label for="email" style="color: red">*</label></td>
		<td><input class="form-control" type = "email" name = "email" required /></td>
		<td></td>
	</tr>
	
	<tr>
		<td><label for="email_body">Message:</label><label for="email_body" style="color: red"></label></td>
		<td colspan="2"><!--<label for="comment">Comment:</label>-->
			<textarea class="form-control" cols="80" rows="5" name="email_body" style="resize: none"></textarea>
			<!--<textarea name="email_body" cols="80" rows="5" style="resize: none"></textarea>-->
		</td>
	</tr>
	<tr>
		<td></td>
		<td align="center"><input class="btn-custom1_default2" type = "submit" value = "Submit"><input class="btn btn-default" type="reset" value="Reset"/></td>
		<td></td>
	</tr>
</table>
</div>
</div>
<br>

<?php
   echo form_close();
?>