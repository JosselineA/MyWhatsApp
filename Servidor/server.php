
<?php

	$host = '192.168.1.74'; //host
	$port = '9000'; //port
	$null = NULL; //null var

	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
	socket_bind($socket, 0, $port);
	socket_listen($socket);

	$contador = 1;
	$clients = array(array('socket'=>$socket,'name'=>""));
	while(true){
		//Realizamos una copia de los clientes ya que serán modificados en el siguiente método
		$changed = getSockets();
		//Checa durante 10 segundos si algun cliente se quiere conectar y los almacena en el array $changed
		socket_select($changed, $null, $null, 0, 10);

		//Checamos nuevas conexiones por el socket principal
		if (in_array($socket, $changed)) {
			$socket_new = socket_accept($socket); 
			echo "Nuevo cliente conectado \n";
			$header = socket_read($socket_new, 1024); 
			perform_handshaking($header, $socket_new, $host, $port); 

			socket_getpeername($socket_new, $ip); 
			$clients[] = array('socket' => $socket_new, 'name' => ++$contador+""); 
			$response_text = mask(json_encode(array('type'=> 'id','id'=>$contador)));

			send_message_toUser($response_text, $socket_new); 

			$found_socket = array_search($socket, $changed);
			unset($changed[$found_socket]);
		}
		
	foreach ($changed as $changed_socket) {

		//Checa si un socket envia información
		while(socket_recv($changed_socket, $buf, 1024, 0) >= 1)
		{
			$received_text = unmask($buf); //unmask data
			$tst_msg = json_decode($received_text); //json decode

			if (isset($tst_msg->type) ){
				switch($tst_msg->type){
					case "setID":
						$id = $tst_msg->i;
						$usuario = $tst_msg->name;
						
						if(checkUserName($usuario)){
							setUsuario($id,$usuario);
							echo "El usuario ".$usuario." ha entrado al chat\n";
							$response_text = mask(json_encode(array('type'=> 'sucess','name'=>$usuario)));							
							send_message_toUser($response_text, $changed_socket); //send data
							//Se actualiza la lista de usuarios en el cliente
							echo "setAll";
							$response_text = mask(json_encode(array('type'=> 'setAllUser',"arreglo" => getUsuarios())));
							send_message($response_text);
						}else{
							$response_text = mask(json_encode(array('type'=> 'userExist')));							
							send_message_toUser($response_text, $changed_socket); //send data
						}
						
						break 3;
					case "getAllUser":
						$response_text = mask(json_encode(array('type'=> 'setAllUser',"arreglo" => getUsuarios())));
						
						send_message_toUser($response_text, $changed_socket); //send data
						break 3;
					case "sentMessageTo":
						$toUser = $tst_msg->toUser;
						$fromUser = $tst_msg->fromUser;
						$messageT = $tst_msg->msg;

						$response_text = mask(json_encode(array('type'=> 'messageFrom','from' => $fromUser, 'msg' => $messageT)));

						send_message_toUser($response_text, getUserSocket($toUser)); //send data
						echo "El usuario ".$fromUser." ha mandado un mensaje a  ".$toUser."\n";
						break 3;
				}
      }

		}

$buf = @socket_read($changed_socket, 1024, PHP_NORMAL_READ);
		if ($buf === false) { // check disconnected client

			$nameU = deleteCliente($changed_socket);
	//		socket_shutdown($changed_socket, 2);
		//	socket_close($changed_socket);
			//notify all users about disconnected connection
			echo "El usuario ".$nameU." ha salido del chat \n";
			$response = mask(json_encode(array('type'=>'usuarioOff', 'usuario' => $nameU)));
			send_message($response);
		}
	}


}

socket_close($socket);
function setUsuario($id,$usuario){
  global $clients;
  $aux = array();
  foreach ($clients as $socket) {

    if ($socket['name'] == $id) {
      $aux[] = array('socket' => $socket['socket'],  "name" => $usuario);

    }else{
      $aux[] = $socket;
    }
  }
  $clients = $aux;
}
function deleteCliente($socketP){
  global $clients;
  $aux = array();
	$name ="";
  foreach ($clients as $socket) {

    if ($socket['socket'] === $socketP) {
     
			$name = $socket['name'];

    }else{
      $aux[] = $socket;
    }
  }
  $clients = $aux;
	return $name;
}
function getSockets(){
  $sockets = array();
  global $clients;
  foreach ($clients as $socket) {
    $sockets[] = $socket['socket'];
  }
  return $sockets;
}
function getUsuarios(){
  $nombres = array();
  global $clients;
	$number = array("0","1","2","3","4","5","6","7","8","9");
  foreach ($clients as $socket) {
  		if(!is_numeric($socket['name'])){

			if(str_replace($number,"",$socket['name']) != ""){
				$nombres[] = $socket['name'];
			}
		}

  }
  return $nombres;
}
function send_message($msg)
{
	$clients = getSockets();
	foreach($clients as $changed_socket)
	{
		@socket_write($changed_socket,$msg,strlen($msg));
	}
	return true;
}
function send_message_toUser($msg,$socket)
{

	@socket_write($socket,$msg,strlen($msg));


	return true;
}

function getUserSocket($toUser){
	$sockets;
  global $clients;
  foreach ($clients as $socket) {
		if($socket['name'] == $toUser){
				return $socket['socket'];
		}
  }

  return null;
}
function checkUserName($user){
	
  global $clients;
  foreach ($clients as $socket) {
		if($socket['name'] == $user || $socket['name'] == ucfirst($user)){
			echo "mismo nombre \n";
				return false;
		}
  }

  return true;
}
function getUser($so){
	$sockets;
  global $clients;
  foreach ($clients as $socket) {
		if($socket['socket'] == $so){

				return $socket['name'];
		}
  }
  return null;
}
//Unmask incoming framed message
function unmask($text) {
	$length = ord($text[1]) & 127;
	if($length == 126) {
		$masks = substr($text, 4, 4);
		$data = substr($text, 8);
	}
	elseif($length == 127) {
		$masks = substr($text, 10, 4);
		$data = substr($text, 14);
	}
	else {
		$masks = substr($text, 2, 4);
		$data = substr($text, 6);
	}
	$text = "";
	for ($i = 0; $i < strlen($data); ++$i) {
		$text .= $data[$i] ^ $masks[$i%4];
	}
	return $text;
}

//Encode message for transfer to client.
function mask($text)
{
	$b1 = 0x80 | (0x1 & 0x0f);
	$length = strlen($text);

	if($length <= 125)
		$header = pack('CC', $b1, $length);
	elseif($length > 125 && $length < 65536)
		$header = pack('CCn', $b1, 126, $length);
	elseif($length >= 65536)
		$header = pack('CCNN', $b1, 127, $length);
	return $header.$text;
}

//handshake new client.
function perform_handshaking($receved_header,$client_conn, $host, $port)
{
	$headers = array();
	$lines = preg_split("/\r\n/", $receved_header);
	foreach($lines as $line)
	{
		$line = chop($line);
		if(preg_match('/\A(\S+): (.*)\z/', $line, $matches))
		{
			$headers[$matches[1]] = $matches[2];
		}
	}

	$secKey = $headers['Sec-WebSocket-Key'];
	$secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
	//hand shaking header
	$upgrade  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
	"Upgrade: websocket\r\n" .
	"Connection: Upgrade\r\n" .
	"WebSocket-Origin: $host\r\n" .
	"WebSocket-Location: ws://$host:$port/demo/shout.php\r\n".
	"Sec-WebSocket-Accept:$secAccept\r\n\r\n";
	socket_write($client_conn,$upgrade,strlen($upgrade));
}


?>
