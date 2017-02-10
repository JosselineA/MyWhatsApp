

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
	      <div class="login-form">
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

	      </div>
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
				<input style="display:none" id="usuarioChat" value="" type="text" />
							<!--Mostar los mensajes-->
							<div id="bandeja">

							</div>


						      <!--Comienza el envio de mensaje-->


								      <input placeholder="mensaje" id="first_name2" type="text" class="validate">
								      <label class="active" for="first_name2"></label>


								    <!--No olvides el botón prro :v-->
								    <a id="btnEnviar" class="btn waves-effect waves-light"  name="action">enviar
									    <i class="material-icons right">send</i>
									</a>





				</div>

		</div>




		</div>


	</div>
  <div  style="display: none;" id="divSecret">

  </div>
</body>
</html>
<script language="javascript" type="text/javascript">
var chatsAlmacenados =  new Array();
function setChat(a) {
	var id = document.getElementById("usuarioChat");
	var b;
	if(id.getAttribute("value") != ""){
		b = id.getAttribute("value")+"Div";
		document.getElementById(b).style.display = "none";
	}
	document.getElementById(a+"Div").style.display = "";
	id.setAttribute('value',a);

}

$(document).ready(function(){
	document.getElementById('divChat').style.display = "none";

  //create a new WebSocket object.
  var wsUri = "ws://192.168.0.12:9000/MyWhatsApp/Servidor/server.php";
  websocket = new WebSocket(wsUri);


	$("#btnEnviar").click(function (){
		var to = document.getElementById("usuarioChat").value;
		var from = document.getElementById("secretSocket").value;
		var msg1 =  document.getElementById("first_name2").value;
		document.getElementById("first_name2").value = "";
		//prepare json data
		var mesg = {
		type: "sentMessageTo",
		msg : msg1,
		toUser: to,
		fromUser: from
		};
		//convert and send data to server
		websocket.send(JSON.stringify(mesg));

		var a = "<ul class='collection estilito'><div class='chip derecho'>"
		a+= "<img src='imagenes/hombre.png' alt='Contact Person'>"+msg1+"</div></ul>";
		$("#"+to+"Div").append(a);
	});


  $('#entrar').click(function(){ //use clicks message send button

    var myname = document.getElementById("usuario").value;

    if(myname == ""){ //empty name?
      alert("Enter your Name please!");
      return;
    }
		var codigoUser = document.getElementById("secretSocket");
    var id = codigoUser.value;
		codigoUser.setAttribute('value',myname+"");

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
		//alert("Mensaje de tipo: "+type)
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
			//alert("send getakk")
			return;
    }
		if(type == 'setAllUser'){
			//alert ("mensaje2")
			$("#listaUsuarios").empty();
			var nombreUsuario =  document.getElementById("secretSocket").value;

			$.each( msg.arreglo, function( key, value ) {
				if(nombreUsuario != value){


				var aux = "<li id=\""+value+"Li\" onclick='setChat(\""+value+"\")' class='collection-item hov avatar green lighten-4'>";
				aux += "<i class='material-icons circle green'>perm_identity</i>";
				aux += "<span class='title'>"+value+"</span>";
				aux += "<a href='#!' class='secondary-content'><i class='material-icons'>info</i></a></li>";
				$("#listaUsuarios").append(aux)
				var idU = value+"Div";
				var div = "<div id=\""+idU+"\" style='hight:100%;width:100%'><h1 class='nombreChat'>"+value+"</h1></div>";
				if(document.getElementById(idU) == null){
					$("#bandeja").append(div);
					document.getElementById(idU).style.display = "none";
				}

				}
			});
			return;
		}
		if(type == 'messageFrom'){
			 var fromUser = msg.from;
			 var message = msg.msg;

			 var a = "<ul class='collection estilito'>";
			 a += "<div class='chip izquierdo'><img src='imagenes/mujer.png' alt='Contact Person'>"+message+"</div></ul>"
			 setChat(fromUser);
			 $("#"+fromUser+"Div").append(a);

			 return;
		}
		if(type == 'usuarioOff'){
			var usuario =  msg.usuario;
			$("#"+usuario+"Div").remove();
			$("#"+usuario+"Li").remove();
			var nameIn = document.getElementById("usuarioChat").value;
			if(nameIn == usuario){
				document.getElementById("usuarioChat").setAttribute('value',"");
			}

		}

  };

  websocket.onerror = function(ev){};
  websocket.onclose   = function(ev){};
});




</script>
