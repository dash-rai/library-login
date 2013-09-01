<!DOCTYPE html>
<html>
<head>
<title>BMSIT Library Reference</title>

<style>

form ul li
{
	display: inline;
	margin: 0 1em;
}

table
{
	border-collapse:collapse;
	width: 100%;
}

table,th, td
{
	border: 1px solid black;
	text-align: center;
}

.current_list form	
{
	display: inline;
}

</style>

</head>
<body>

<form action="" id="login" method="post">
	<ul>
		<li>Library ID: <input type="text" name="id"></li>
		<li>Name: <input type="text" name="name"></li>
		<input type="hidden" name="action" value="login">
		<li><input type="submit" value="Login"></li>
	</ul>
</form>

<?php require_once 'logger.php'; ?>

</body>
</html>