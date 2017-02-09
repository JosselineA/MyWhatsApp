<!DOCTYPE html>
<html>
<head>
	<title>MyWhatsApp</title>
	<link rel="stylesheet" type="text/css" href="estilo/estilo.css">
	<link rel="stylesheet" type="text/css" href="css/estilo.css">
	<link rel="stylesheet" href="materialize/css/materialize.min.css">
	 <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
	 <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	 <script src="jquery-3.1.1.js"></script>

</head>
<body>


	<header>
		<nav>
	    <div class="nav-wrapper teal accent-3">

	      <img id="chat_logo" src="imagenes/chat.png">
	      <ul class="right hide-on-med-and-down">

	      </ul>
	    </div>
	  </nav>

	</header>

	<div class="row">

		<div id="Contacts" class="col s3 green lighten-4">
				<ul class="collection" id="listaUsuarios">

			  </ul>

		</div>

		<div class="col s9 green lighten-4">

					    <form>


					          <textarea   class="materialize-textarea"></textarea>
					          <label for="textarea1"></label>



					      <!--Comienza el envio de mensaje-->


							      <input placeholder="mensaje" id="first_name2" type="text" class="validate">
							      <label class="active" for="first_name2"></label>


							    <!--No olvides el botón prro :v-->
							    <button id="btnEnviar" class="btn waves-effect waves-light" type="submit" name="action">enviar
								    <i class="material-icons right">send</i>
								</button>



					    </form>

			</div>

	</div>




	</div>


</body>
</html>

<script language="javascript" type="text/javascript">
$(document).ready(function(){
  //create a new WebSocket object.

	var msg = {
	type: "getAllUser"
	};
	websocket.send(JSON.stringify(msg));

  $('#btnEnviar').click(function(){ //use clicks message send button

    var myname = document.getElementById("usuario").value;

    if(myname == ""){ //empty name?
      alert("Enter your Name please!");
      return;
    }

    var id = document.getElementById("secretSocket").value;


    //prepare json data
    var msg = {
    type: "setID",
    i: id,
    name: myname
    };
    //convert and send data to server
    websocket.send(JSON.stringify(msg));
  });

  //#### Message received from server?
  websocket.onmessage = function(ev) {
    var msg = JSON.parse(ev.data); //PHP sends Json data
    var type = msg.type; //message type
		alert("mensaje");
		if(type == 'setAllUser'){
			alert ("mensaje2")
			$("#listaUsuarios").empty();
			$.each( msg, function( key, value ) {
				var aux = "<li class='collection-item avatar green lighten-4'>";
				aux += "<i class='material-icons circle green'>perm_identity</i>";
				aux += "<span class='title'>"+value+"</span>";
				aux += "<a href='#!' class='secondary-content'><i class='material-icons'>info</i></a></li>";
				$("#listaUsuarios").append(aux)
			});
		}

  };

  /*websocket.onerror = function(ev){$('#message_box').append("<div class=\"system_error\">Error Occurred - "+ev.data+"</div>");};*/
  websocket.onclose   = function(ev){alert("no function")};
});




</script>
