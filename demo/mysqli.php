<table><tr><th>Atomic Number</th><th>Latin</th><th>English</th><th>Abbreviation</th></tr>
<?php

/*** mysql hostname ***/
$hostname = 'localhost';

/*** mysql username ***/
$username = 'username';

/*** mysql password ***/
$password = 'password';

/*** mysql database name ***/
$dbname = 'periodic_table';

/*** create a new mysqli object with default database***/
$mysqli = @new mysqli($hostname, $username, $password, $dbname);

/* check connection */ 
if(!mysqli_connect_errno())
    {
    /*** if we are successful ***/
    echo 'Connected Successfully<br />';

    /*** our SELECT query ***/
    $sql = "SELECT * FROM elements";

    /*** prepare statement ***/
    if($stmt = $mysqli->prepare($sql))
        {
        /*** execute our SQL query ***/
        $stmt->execute();
        /*** bind the results ***/
        $stmt->bind_result($atomicnumber, $latin, $english, $abbr);	
        /*** loop over the result set ***/
        while ($stmt->fetch())
            {
            /*** echo our table rows ***/
            echo '<tr><td>'.$atomicnumber.'</td><td>'.$latin.'</td><td>'.$english.'</td><td>'.$abbr.'</td></tr>';
            }
        }
    /*** close connection ***/
    $mysqli->close();
    }
else
    {
    /*** if we are unable to connect ***/
    echo 'Unable to connect';
    exit();
    }
?>
</table>