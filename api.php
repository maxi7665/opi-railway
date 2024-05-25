<?php

 $dbconn = pg_connect("host=localhost dbname=railwaydb user=postgres password=qwsxza")
    or die('Не удалось соединиться: ' . pg_last_error());

 switch ($_SERVER['REQUEST_METHOD'])
 {
 	case 'GET': 

		$query = 'SELECT * FROM trip';

		if ($_GET['id'])
		{
			$query .= ' where id = '.$_GET['id'];
		}
		
		$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());

		$ret = "[";

		$cnt = 0;

		while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

				if ($cnt > 0)
				{
					$ret .= ',';				
				}

				$cnt = $cnt + 1;

				$ret .= json_encode($line);			
			}

		$ret .=']';

 		//$age = array("Peter"=>35, "Ben"=>37, "Joe"=>43);

		echo $ret;

		break;

	case 'DELETE':		

		if ($_GET['id'])
		{
			$query = 'delete  FROM trip where id = '.$_GET['id'].';';
		}
		
		$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());

		break;

	case 'POST':
	case 'PATCH':

		$trip = json_decode(file_get_contents('php://input'), true);

		if ($trip == null)
		{
			die('Ошибка запроса');
		}

		//echo $trip;

		if ($_SERVER['REQUEST_METHOD'] == 'PATCH')
		{
			$query = 'UPDATE trip '
				.'SET "source"=\''.$trip['source'].'\', destination=\''.$trip['destination'].
				'\', departure_date_time=\''.$trip['departure_date_time'].'\', way='.$trip['way'].
				' WHERE id='.$trip['id'].';';

				echo $query;

			$res = pg_query($dbconn, $query);
			
		}
		else if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$query = 'insert into trip ("source", destination, departure_date_time, way) values ('
				.'\''.$trip['source'].'\',\''.$trip['destination'].'\',\''.$trip['departure_date_time'].'\','.$trip['way'].');';

				echo $query;

			$res = pg_query($dbconn, $query);
		}

		break;

		// todo api
 }

 // Выполнение SQL-запроса
/*$query = 'SELECT * FROM trip';
$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());

// Вывод результатов в HTML
echo "<table>\n";
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    echo "\t<tr>\n";
    foreach ($line as $col_value) {
        echo "\t\t<td>$col_value</td>\n";
    }
    echo "\t</tr>\n";
}
echo "</table>\n";*/


?>
