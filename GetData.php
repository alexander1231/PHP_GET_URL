
<?php

require 'conexion.php';

function getJson($user,$pwd,$url){
$login = $user;//'admin';
$password = $pwd;//'admin18';
//$url = url//'http://cdn.win.pe:8080/flussonic/api/media';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
$result = curl_exec($ch);
curl_close($ch);  
return $result;	
}

	$hostname="localhost";
	$username="root";
	$password=".3ciwreiY";
	$dbname="Monitoreo";
	$usertable="`user_credenciales`";

//ConxInsert($hostname,$username, $password, $dbname, $usertable, $colum, $data);
//$credenciales = ConxSelec($hostname,$username,$password, $dbname,$usertable,$yourfield,$whereQuery);

//$datosMedia = getJson($credenciales[0]['user_name'],$credenciales[0]['user_pass'],$credenciales[0]['user_url']);

//$data = json_decode($datosMedia,true);

//echo var_dump($data);


/*foreach ($data as $value) {
	if (!empty($value['value']['stats']['client_count'])) {
		echo '--';
		print_r($value['value']['name']);
		echo '-';
		print_r($value['value']['stats']['client_count']);//Numero de Clientes
		echo '--';
	}
}*/


function getCanal($hostname,$username,$password,$dbname){

	
	$usertableBloque="`bloque_destino`";
	
	$whereQueryBloque = "where bloque_destino = 1 ORDER BY bloque_id DESC limit 1";

	

	//Obtengo Codigo de Bloque Insertar
	$columSerieBloque = ['bloque_numero','bloque_destino'];
	$dataBloque =  ConxSelec($hostname,$username,$password, $dbname,$usertableBloque,$columSerieBloque,$whereQueryBloque);

	//echo print_r($dataBloque);

	//Insertar En la tabla Bloque
	$columSerieBloqueData = [[$dataBloque[0]['bloque_numero']+1,1]];
	ConxInsert($hostname,$username, $password, $dbname, $usertableBloque, $columSerieBloque, $columSerieBloqueData);	


	$fieldCredencial = ["user_name","user_pass","user_url"];
	$usertable="`user_credenciales`";
	$whereQuery = "where user_id = 1";
	//Obtener Credenciales and url de la base datos
	$credenciales = ConxSelec($hostname,$username,$password, $dbname,$usertable,$fieldCredencial,$whereQuery);

	

	//Obtener Datos de la API
	$datosMedia = getJson($credenciales[0]['user_name'],$credenciales[0]['user_pass'],$credenciales[0]['user_url']);	
	//Convertimos la Data to Array Para poder leerla
	$data = json_decode($datosMedia,true);
	
	//echo print_r($data,true);

	$dataCanal = array();
	//prepara el array para insertar
	foreach ($data as $value) {
		
			if(!empty($value['value']['stats']['client_count'])){
				$dataCanal2[0] = "'".$value['value']['name']."'";
			
				$dataCanal2[1] =  $value['value']['stats']['client_count'];//Numero de Clientes

				$dataCanal2[2] = $dataBloque[0]['bloque_numero']+1;

				$dataCanal2[3] = !empty($value['value']['stats']['bitrate'])? $value['value']['stats']['bitrate'] : 0;
				$dataCanal2[4] = !empty($value['value']['stats']['bytes_in'])? $value['value']['stats']['bytes_in'] : 0;
				$dataCanal2[5] = !empty($value['value']['stats']['bytes_out'])? $value['value']['stats']['bytes_out'] : 0;
				$dataCanal2[6] = !empty($value['value']['stats']['last_access_at']) ? $value['value']['stats']['last_access_at'] : 0;
				$dataCanal2[7] = !empty($value['value']['stats']['last_dts_at']) ? $value['value']['stats']['last_dts_at'] : 0;
				$dataCanal2[8] = !empty($value['value']['stats']['lifetime']) ? $value['value']['stats']['lifetime'] : 0;
				$dataCanal2[9] = !empty($value['value']['stats']['out_bandwidth']) ? $value['value']['stats']['out_bandwidth'] : 0;
				$dataCanal2[10] = !empty($value['value']['stats']['start_running_at']) ? $value['value']['stats']['start_running_at'] : 0;				

				array_push($dataCanal, $dataCanal2);
			}else{
				$dataCanal2[0] = "'".$value['value']['name']."'";
			
				$dataCanal2[1] =  0;//Numero de Clientes

				$dataCanal2[2] = $dataBloque[0]['bloque_numero']+1;

				$dataCanal2[3] = !empty($value['value']['stats']['bitrate'])? $value['value']['stats']['bitrate'] : 0;
				$dataCanal2[4] = !empty($value['value']['stats']['bytes_in'])? $value['value']['stats']['bytes_in'] : 0;
				$dataCanal2[5] = !empty($value['value']['stats']['bytes_out'])? $value['value']['stats']['bytes_out'] : 0;
				$dataCanal2[6] = !empty($value['value']['stats']['last_access_at']) ? $value['value']['stats']['last_access_at'] : 0;
				$dataCanal2[7] = !empty($value['value']['stats']['last_dts_at']) ? $value['value']['stats']['last_dts_at'] : 0;
				$dataCanal2[8] = !empty($value['value']['stats']['lifetime']) ? $value['value']['stats']['lifetime'] : 0;
				$dataCanal2[9] = !empty($value['value']['stats']['out_bandwidth']) ? $value['value']['stats']['out_bandwidth'] : 0;
				$dataCanal2[10] = !empty($value['value']['stats']['start_running_at']) ? $value['value']['stats']['start_running_at'] : 0;				


				array_push($dataCanal, $dataCanal2);
			}	

	}
	//echo print_r($dataCanal,true);				
	$canalTable='`api_canal`';
	$colum = ["canal_name", "canal_count_client", "canal_bloque","bitrate","bytes_in","bytes_out","last_access_at","last_dts_at","lifetime","out_bandwidth","start_running_at"];

		
	ConxInsert($hostname,$username, $password, $dbname, $canalTable, $colum, $dataCanal);	


}




	
function getUsuario($hostname,$username,$password,$dbname){
	/*$hostname="localhost";
	$username="root";
	$password=".3ciwreiY";*/
	

	$usertableBloque="`bloque_destino`";
	
	$whereQueryBloque = "where bloque_destino = 2 ORDER BY bloque_id DESC limit 1";

	

	//Obtengo Codigo de Bloque Insertar
	$columSerieBloque = ['bloque_numero','bloque_destino'];
	$dataBloque =  ConxSelec($hostname,$username,$password, $dbname,$usertableBloque,$columSerieBloque,$whereQueryBloque);

	//echo print_r($dataBloque);

	//Insertar En la tabla Bloque
	$columSerieBloqueData = [[$dataBloque[0]['bloque_numero']+1,2]];
	ConxInsert($hostname,$username, $password, $dbname, $usertableBloque, $columSerieBloque, $columSerieBloqueData);	


	$fieldCredencial = ["user_name","user_pass","user_url"];
	$usertable="`user_credenciales`";
	$whereQuery = "where user_id = 1";
	//Obtener Credenciales and url de la base datos
	$credenciales = ConxSelec($hostname,$username,$password, $dbname,$usertable,$fieldCredencial,$whereQuery);

	

	//Obtener Datos de la API
	$datosMedia = getJson($credenciales[0]['user_name'],$credenciales[0]['user_pass'],$credenciales[0]['user_url']);	
	//Convertimos la Data to Array Para poder leerla
	$data = json_decode($datosMedia,true);
	
	//echo print_r($data,true);

	$dataCanal = array();
	//Obtener lista de canales.
	foreach ($data as $value) {
		
			if(!empty($value['value']['stats']['client_count'])){
				$dataCanal2[0] = $value['value']['name'];
							

				array_push($dataCanal, $dataCanal2);
			}					
	}
	

	
	$whereQueryUsuario = "where user_id = 2";
	//Obtener Credenciales and url de la base datos
	$credenciales = ConxSelec($hostname,$username,$password, $dbname,$usertable,$fieldCredencial,$whereQueryUsuario);
	$count = 0;

	$usuarioTabla = "`api_usuario`";
	$columUsuario = ["usuario_ip","usuario_code","canal_name","usuario_tiempo_ingreso","usuario_tiempo_duracion","usuario_bloque","usuario_pais"];
	foreach ($dataCanal as  $value) {
						
		//$value[0]; Add el canal a consultar y retorna los usuarios.
		$datosMedia = getJson($credenciales[0]['user_name'],$credenciales[0]['user_pass'],$credenciales[0]['user_url'].$value[0]);
		$data = json_decode($datosMedia,true);
		
		//echo print_r($data,true);
	
			$datausuarios = array();
			//echo print_r($data['sessions'],true);
			for($i=0; $i < count($data['sessions']); $i++){
				$usuariosX[0] =  "'".$data['sessions'][$i]['ip']."'";	
				$usuariosX[1] =  "'".$data['sessions'][$i]['id']."'";
				$usuariosX[2] =  "'".$data['sessions'][$i]['name']."'";									
				$usuariosX[3] =  $data['sessions'][$i]['created_at'];					
				$usuariosX[4] =  !empty($data['sessions'][$i]['current_time']) ? $data['sessions'][$i]['current_time'] : 0;	
				$usuariosX[5] =  $dataBloque[0]['bloque_numero']+1;
				$usuariosX[6] =  "'".$data['sessions'][$i]['country']."'";									
				//$usuariosX[5] =  $data['sessions'][$i]['country'];					
				array_push($datausuarios, $usuariosX);
			}
			//echo print_r($datausuarios,true);
			//Inserta Cada Usuarios por Canal
			ConxInsert($hostname,$username, $password, $dbname, $usuarioTabla, $columUsuario, $datausuarios);			
	}
}

getCanal($hostnameCn,$usernameCn,$passwordCn,$dbnameCn); //Ejecutar Canal
getUsuario($hostnameCn,$usernameCn,$passwordCn,$dbnameCn); //Ejecutar Usuario
?>
