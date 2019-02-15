<?php
	//Sintaxis de conexión de la base de datos de muestra para PHP y MySQL.
	
	//Conectar a la base de datos
	
	$hostnameCn="localhost";
	$usernameCn="root";
	$passwordCn=".3ciwreiY";
	$dbnameCn="Monitoreo";
	//$usertable="`user_credenciales`";
	//$yourfield = ["user_name","user_pass","user_url"];
	//$whereQuery = "where user_id in (1)"; //if 2 Url add Frecuencia Latina

	//echo gettype($yourfield);
	function ConxSelec($hostname, $username, $password, $dbname, $usertable, $yourfield, $whereQuery){

		try {

			$link = @mysqli_connect($hostname, $username, $password, $dbname);

			//Obtiene toda la tabla
			$query = "SELECT * FROM ".$usertable.$whereQuery;

			$result = mysqli_query($link, $query);

			if ($result->num_rows == 0 ) {
				throw new Exception('Numero de filas 0. - ConxSelec');
			}
			
			$array = array();
			$count = 0;
			//Bucle que selecciona las columnas a utilizar 
			while ($row = mysqli_fetch_array($result)) {
			    foreach ($yourfield as $value) {
			    	//echo $value;
			    	//echo ($value);	
			    	$array2[$value] = $row[$value];			    				    	
			    	/*if ($count($row) = 1 ) {
			    		array_push($array,$array);
			    	}*/
			    }
			   	array_push($array, $array2);
			}
			return $array;

			mysqli_free_result($result);
			mysqli_close($link);		

		} catch (Exception $e) {
			mysqli_free_result($result);
			mysqli_close($link);				
    		echo 'Excepción capturada: ',  $e->getMessage(), "\n";
		}
	}

	//Crear Columnas por Tabla a insertar;
	$colum = ["user_name", "user_pass", "user_url", "user_registro", "user_fecha_registro", "user_fecha_modificacion"];
	//Crear Array de Datos a Insertar;
	$data = [["'admin'", "'admin18'", "'http://cdn.win.pe:8080/flussonic/api/sessions?name='", "'1'", "now()", "now()"],["'admin'", "'admin18'", "'http://cdn.win.pe:8080/flussonic/api/media'", "'1'", "now()", "now()"]];

	function ConxInsert($hostname,$username, $password, $dbname, $usertable, $colum, $data ){
		$link = @mysqli_connect($hostname, $username, $password, $dbname);
		//echo var_dump($data);
		

		//Cabecera del Query de Insertar
		$query_insert = "INSERT INTO ".$usertable." (";

		for ($i=0; $i < count($colum) ; $i++) { 
			
			if ($i == count($colum)-1) {
				$query_insert .= "`". $colum[$i] . "`)";
			}else{
				$query_insert .= "`". $colum[$i] . "`,";
			}

		}


		//Values del Query de Inserar
		$query_insert_value = " VALUES ";
		for ($i=0; $i < count($data); $i++) { 
			for ($j=0; $j < count($data[$i]); $j++) { 
				//echo print_r($data[$i],true);
				echo count($data[$i]);
				if ($j == 0) {
					$query_insert_value .= "(".$data[$i][$j].',';	
				}else if ($j == count($data[$i])-1) {
					if ($j == count($data[$i])-1 && $i == count($data)-1) {
						$query_insert_value .= $data[$i][$j].');';
					}else
					{
						$query_insert_value .= $data[$i][$j].'),';	
					}					
				}				
				else{
					$query_insert_value .= $data[$i][$j]. ',';				
				}

				
			}
		}

		//echo $query_insert;
		//echo $query_insert_value."";

		$query = $query_insert.$query_insert_value;

		//echo "";
		//echo $query;
		
		$result = $link->query($query);

		echo var_dump($result);

		

		mysqli_close($link);
	}

	//Function que inserta data
	//ConxInsert($hostname,$username, $password, $dbname, $usertable, $colum, $data);
	//Function que busca data
	//ConxSelec($hostname,$username,$password, $dbname,$usertable,$yourfield);
	
	
	
	
?>