<?php
//CSS into heredoc
$this->css = <<<CSS
#container111 {
	position: relative;
	top: 10em;
	width: 35%;
	margin: 0 auto;
	box-shadow: 0px 2px 5px 0px rgba(0, 0, 0, 0.26);
}

.container{
	margin-top: 100px;
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
	<!--If not empty error echo error/s-->
	<?php if (!empty($errors)): ?>
		<ul class="error">
		<?php foreach ($errors as $error): ?>
			<li><?php echo $error ?></li>
		<?php endforeach ?>
		</ul>
	<?php endif ?>
	
	<!--form login-->
	<form class="form-signin" action="<?php echo site_url('login')?>" method="post">
		<!--<h2>Login</h2>-->
		
		<br/>
		
		<label for="username" class="sr-only">Username: </label>
		<input <?php if (!empty($error)) echo 'class="input_error"';?> type="text" name="username" class="form-control" placeholder="Username" required autofocus>
		<label for="password" class="sr-only">Password: </label>
		<input <?php if (!empty($error)) echo 'class="input_error"';?> type="password" name="password" class="form-control" placeholder="Password" required/>
		
		<button type="submit" class="btn-custom1_default2">Log In</button>
	</form>

</div>