

<!DOCTYPE html>
<html>
<head>
	<title>Inicio</title>
	 <link rel="stylesheet" href="materialize/css/materialize.min.css">
	 <link rel="stylesheet" type="text/css" href="css/style.css">
	 <link rel="stylesheet" type="text/css" href="estilo/estilo.css">
 	<link rel="stylesheet" type="text/css" href="css/estilo.css">
	<link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
	 <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
   <script src="jquery-3.1.1.js"></script>
</head>
<body  >
	<div id="divLogin" class="dark cyan"  style="height:100%;width:100%">
		<div id="login-page"  class="row">
	    <div id="login"  class="col s3 z-depth-6 card-panel">
	      <form class="login-form">
	        <div class="row">
	          <div class="input-field col s12 center">
	            <img src="img/logo3.png" alt="" class="responsive-img valign profile-image-login">
	          </div>
	        </div>
	        <div class="row margin">
	          <div class="input-field col s12">
	            <i class="mdi-social-person-outline prefix"></i>
	            <input class="validate" id="usuario" type="text" placeholder="usuario">

	          </div>
	        </div>
	     <div class="row">
	          <div class="input-field col s12">
	            <a id="entrar" class="btn waves-effect waves-light col s12">Login</a>
	          </div>
	        </div>

	      </form>
	    </div>
	  </div>
	</div>
	<div class="colorDiv" id="divChat" style="height:100%">
		<header>
			<nav>
		    <div class="nav-wrapper teal accent-3">

		      <img id="chat_logo" src="imagenes/chat.png">
		      <ul class="right hide-on-med-and-down">

		      </ul>
		    </div>
		  </nav>

		</header>

		<div class="row fondo" >

			<div id="Contacts" class="col s3 green lighten-4">
					<ul class="collection" id="listaUsuarios">

				  </ul>

			</div>

			<div class="col s9 green lighten-4">

							<!--Mostar los mensajes-->
							<div id="bandeja">
								<ul class="collection estilito">
							       <div class="chip izquierdo"><img src="imagenes/hombre.png" alt="Contact Person">contacto</div>
							    </ul>
							    <ul class="collection estilito">
							       <div class="chip derecho"><img src="imagenes/mujer.png" alt="Contact Person">contacto</div>
							    </ul>

							</div>

						    <form>
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


	</div>
  <div style="display: none;" id="divSecret">

  </div>
</body>
</html>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	document.getElementById('divLogin').style.display = "none";
  //create a new WebSocket object.
  var wsUri = "ws://192.168.0.12:9000/MyWhatsApp/Servidor/server.php";
  websocket = new WebSocket(wsUri);

  $('#entrar').click(function(){ //use clicks message send button

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
		alert("Mensaje de tipo: "+type)
    if(type == 'id')
    {
      $('#divSecret').append("<input type='text' id='secretSocket' value='"+msg.id+"'  name=''>");
			return;
    }
   if(type == 'sucess')
    {
			document.getElementById('divLogin').style.display = "none";
      document.getElementById('divChat').style.display = "";
			var al = {
			type: "getAllUser"
			};
			websocket.send(JSON.stringify(al));
			alert("send getakk")
			return;
    }
		if(type == 'setAllUser'){
			alert ("mensaje2")
			$("#listaUsuarios").empty();
			$.each( msg.arreglo, function( key, value ) {
				var aux = "<li class='collection-item avatar green lighten-4'>";
				aux += "<i class='material-icons circle green'>perm_identity</i>";
				aux += "<span class='title'>"+value+"</span>";
				aux += "<a href='#!' class='secondary-content'><i class='material-icons'>info</i></a></li>";
				$("#listaUsuarios").append(aux)
			});
			return;
		}

  };

  /*websocket.onerror = function(ev){$('#message_box').append("<div class=\"system_error\">Error Occurred - "+ev.data+"</div>");};
  websocket.onclose   = function(ev){$('#message_box').append("<div class=\"system_msg\">Connection Closed</div>");}; */
});




</script>
