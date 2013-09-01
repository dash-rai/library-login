<?php
// remember to turn off error reporting
// error_reporting( E_ERROR );

$current_file = 'current.csv';
$log_file = 'library_log.csv';
$max_length = 200; // max length in characters of the current or log file

function checkComplete ()
{
	if ( empty($_POST['name']) )
	{
		echo "Name not entered!";
		return FALSE;
	}
	if ( empty($_POST['id']) )
	{
		echo "User ID not entered!";
		return FALSE;
	}
	if ( !checkID( $_POST['id']) )
		return FALSE;
	return TRUE;
}

function clean( $string ) {
  return preg_replace('/[^A-Za-z \-]/', '', $string); // Removes all special characters from string, including MULTIPLE whitespaces
}

function checkID( $id )
{
	if ( isset($id) )
	{
		if ( preg_match('/[^0-9\-]/', $id) === 1  || $id > 9999 || $id < 1000 )
		{
			echo "ID is not valid<br>";
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	echo "ID not entered!";
	return FALSE;
}

function isLoggedIn(&$fp, &$id)
{
	global $max_length;
	while ( ($data = fgetcsv($fp, $max_length, ",")) !== FALSE)
    {
    	if ( $data[0] == $id)
    		return TRUE;
	}
	return FALSE;
}

if ( isset ($_POST['action']) )
{
	$action = $_POST['action'];

	if ( $action == "login")
	{
		if ( checkComplete() )
		{
			$new_id =  $_POST['id'];
			$name = clean( $_POST['name'] );
			$fp = fopen ($current_file, 'a+') or die ("Could not open file $current_file");

			if ( !isLoggedIn($fp, $new_id) )
			{
				$student = array ($new_id, $name, date('h:i:s d/m/y'), time());
				fputcsv($fp, $student);
				fclose($fp);
			}
			else
			{
				echo "Already loggen in!";
			}
		}
	}
	else if ( $action == "logout" )
	{
		$fp = fopen ($current_file, 'r+') or die ("Could not open file $current_file");
		if ( checkID( $_POST['id'] ) )
		{
			$id = $_POST['id'];
			
			$found = FALSE;
			for ( $line_num = 0; ($data = fgetcsv($fp, $max_length, ",")) !== FALSE; $line_num++)
			{
				if ($data[0] == $id)
				{
					$found = TRUE;
					break;
				}
			}


			if ( $found == TRUE )
			{
				$name = $data[1];
				$login_time = $data[2];
				$logout_time = date('h:i:s d/m/y');
				$duration = ( time() - $data[3] ) / 60; //duration logged-in in minutes

				$record = array ($id, $name, $login_time, $logout_time, $duration);
				$log_handler = fopen($log_file, 'a') or die ("Could not open file $log_file");
				fputcsv($log_handler, $record);
				fclose ($log_handler);

				$data = file($current_file);
				
				unset($data[$line_num]);

				$data = array_values($data);
				
				$data = implode("", $data); 
				file_put_contents($current_file, $data);

			}
			else
				echo "ID Not Found!";
		}
	}
}


if ( ($fp = fopen($current_file, 'r') or die("File does not exist...yet.") ) !== FALSE) 
{
	echo "<table>";
	echo "<tr>";
	echo "<td>ID</td>
	<td>Name</td>
	<td>Login Time</td>
	<td></td></tr>";
    while (($data = fgetcsv($fp, $max_length, ",")) !== FALSE)
    {
    	//
    	        echo "<tr>
       	<td class='current_list'>" . $data[0] . "</td>
       	<td class='current_list'>" . $data[1] . "</td>
       	<td class='current_list'>" . $data[2] . "</td>
       	<td class='current_list'><form action='' id='logout' method='post'>
			<input type='hidden' name='id' value='" . $data[0] . "'>
			<input type='hidden' name='action' value='logout'>
			<input type='submit' value='Logout'>
		</form></td>
		</tr> ";	  			
    }
    echo "</table>";
    fclose($fp);
}
