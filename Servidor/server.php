
<?php
	
	$host = 'localhost'; //host
	$port = '9000'; //port
	$null = NULL; //null var

	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	//socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
	socket_bind($socket, 0, $port);
	socket_listen($socket);

	$contador = 1;
	$clients = array($socket);
	while(true){
		//Realizamos una copia de los clientes ya que serán modificados en el siguiente método
		$changed = getSockets();
		//Checa durante 10 segundos si algun cliente se quiere conectar y los almacena en el array $changed
		socket_select($changed, $null, $null, 0, 10);

		//Checamos nuevas conexiones por el socket principal
		if (in_array($socket, $changed)) {
			$socket_new = socket_accept($socket); //accpet new socket
			
			$header = socket_read($socket_new, 1024); //read data sent by the socket
			perform_handshaking($header, $socket_new, $host, $port); //perform websocket handshake
			
			socket_getpeername($socket_new, $ip); //get ip address of connected socket
			$response = mask(json_encode(array('type'=>'system', 'message'=>$ip.' connected'))); //prepare json data
			send_message($response); //notify all users about new connection

			socket_read($socket_new, 1024); 

			$clients[] = array('socket' => $socket_new, 'name' => ++$contador+""); //add socket to client array

			$response_text = mask(json_encode(array('type'=> 'id','id'=>$contador)));

			send_message($response_text, $socket_read); //send data

 
			//make room for new socket
			$found_socket = array_search($socket, $changed);
			unset($changed[$found_socket]);
		}
		//loop through all connected sockets
	foreach ($changed as $changed_socket) {	
		
		//check for any incomming data
		while(socket_recv($changed_socket, $buf, 1024, 0) >= 1)
		{
			$received_text = unmask($buf); //unmask data
			$tst_msg = json_decode($received_text); //json decode 
			/*$user_name = $tst_msg->name; //sender name
			$user_message = $tst_msg->message; //message text
			$user_color = $tst_msg->color; //color*/

			switch($tst_msg->type){
				case "setID":
					$id = $tst_msg-> i; 
					$usuario = $tst_msg->name;
					setUsuario($id,$usuario); 

					$response_text = mask(json_encode(array('type'=> 'sucess')));

					send_message($response_text, $changed_socket); //send data
					break;
			}
			
			//prepare data to be sent to client
			$response_text = mask(json_encode(array('type'=>'usermsg', 'name'=>$user_name, 'message'=>$user_message, 'color'=>$user_color)));
			send_message($response_text); //send data
			break 2; //exist this loop
		}
		
		$buf = @socket_read($changed_socket, 1024, PHP_NORMAL_READ);
		if ($buf === false) { // check disconnected client
			// remove client for $clients array
			$found_socket = array_search($changed_socket, $clients);
			socket_getpeername($changed_socket, $ip);
			unset($clients[$found_socket]);
			
			//notify all users about disconnected connection
			$response = mask(json_encode(array('type'=>'system', 'message'=>$ip.' disconnected')));
			send_message($response);
		}
	}



	/*//Checa cada uno de los clientes
	foreach ($changed as $changed_socket) {	
		
		//Verifica si se espera recibir datos del cliente
		while(socket_recv($changed_socket, $buf, 1024, 0) >= 1)
		{
			socket_getpeername($socket_new, $ip);

			$received_text = unmask($buf); //unmask data
			$tst_msg = json_decode($received_text); //json decode 
			$user_name = 2; //sender name
			$to_user_name = $tst_msg->toUser
			$user_message = $tst_msg->message; //message text
			$user_color = $tst_msg->color; //color
			
			//prepare data to be sent to client
			$response_text = mask(json_encode(array('type'=>'usermsg', 'name'=>$user_name, 'message'=>$user_message, 'color'=>$user_color)));
			send_message($response_text); //send data
			break 2; //exit this loop
		}
		
		$buf = @socket_read($changed_socket, 1024, PHP_NORMAL_READ);
		if ($buf === false) { // check disconnected client
			// remove client for $clients array
			$found_socket = array_search($changed_socket, $clients);
			socket_getpeername($changed_socket, $ip);
			unset($clients[$found_socket]);
			
			//notify all users about disconnected connection
			$response = mask(json_encode(array('type'=>'system', 'message'=>$ip.' disconnected')));
			send_message($response);
		}
	}*/
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
function getSockets(){
  $sockets = array();
  global $clients;
  foreach ($clients as $socket) {
    $sockets[] = $socket['socket'];
  }
  return $sockets;
}
function send_message($msg)
{
	global $clients;
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