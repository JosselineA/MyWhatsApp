<?php
	
	$host = 'localhost'; //host
	$port = '9000'; //port
	$null = NULL; //null var

	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	//socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
	socket_bind($socket, 0, $port);
	socket_listen($socket);


	//$listaClientes = array($socket);
	while(true){
		
		//$lista = $listaClientes;
		//socket_select($changed, $null, $null, 0, 10);


		//Checamos nuevas conexiones 
		if (in_array($socket, $listaClientes)) {


			$nuevoSocket = socket_accept($socket);
			$clientes[] = $nuevoSocket;
			$header = socket_read($nuevoSocket, 1024);
			perform_handshaking($header, $socket_new, $host, $port); 
			socket_getpeername($socket_new, $ip);




		}



	}

?>