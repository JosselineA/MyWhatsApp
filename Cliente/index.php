

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
	 <script src="materialize/js/materialize.min.js"></script><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body  class="fondo">
	<div id="divLogin"   style="background-color: #31bdb2;height:100%;width:100%">
		<div id="login-page"  class="row">
	    <div id="login"  class="col s9 m6 l6 z-depth-6 card-panel">
	      <div class="login-form">
	        <div class="row">
	          <div class="input-field col s12 center">
	            <img src="img/logo3.png" alt="" class="responsive-img valign profile-image-login">
	          </div>
	        </div>
	        <div class="row margin">
	          <div class="input-field col s12">
	            <i class="material-icons prefix">account_circle</i>
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
		<nav style="background-color: #008b80">
			 <div class="nav-wrapper">
			 	<a id="usuarioChat" href="#!" class="brand-logo center"></a>
				 				 <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
				 <ul class="right hide-on-med-and-down">

				 </ul>
				 <ul class="side-nav" id="mobile-demo">

				 </ul>
			 </div>
		 </nav>

		<div class="row fondo"  style="height:86%;margin-bottom:0!important">
			<div id="Contacts" class="col l3 hide-on-med-and-down green lighten-4">
					<ul class="collection" id="listaUsuarios" style="border:none">
				  </ul>
			</div>
			<div class="col s12 l9 green lighten-4" style="height:100%;">
				<div class="row"></div>
							<!--Mostar los mensajes-->
					<div id="bandeja">

					</div>

					<input style="background-color: white!important;" placeholder="mensaje" id="first_name2" type="text" class="validate">
					<label class="active" for="first_name2"></label>

					<a id="btnEnviar" class="btn waves-effect waves-light"  name="action">enviar
						<i class="material-icons right">send</i>
					</a>
				</div>

		</div>




		</div>


	</div>
  <div  style="display: none;" id="divSecret">

  </div>
  <div style="display:none">
  	<audio id="myAudio" controls>
	  <source src="sound/tweet.mp3" type="audio/mp3">
	</audio>
  </div>
</body>
</html>
<script language="javascript" type="text/javascript">
var chatsAlmacenados =  new Array();
function setChat(a) {
	var id = document.getElementById("usuarioChat");
	var b;
	if(id.innerHTML != ""){
		b = id.innerHTML+"Div";
		document.getElementById(b).style.display = "none";
	}
	document.getElementById(a+"Div").style.display = "";
	id.innerHTML = a;
	removeEtiquetas(a);

}
function setEtiquetas(fromUser){
  	if(document.getElementById("usuarioChat").innerHTML != fromUser){
  		document.getElementById("myAudio").play(); 	
		Materialize.toast('Nuevo mensaje de '+fromUser, 4000) // 4000 is the duration of the toast
			  if( $("#"+fromUser+"Li> span ").length){
			  	var a = $("#"+fromUser+"Li> span ").text();
			  	console.log("msg val: "+a);
			  	 var value = parseInt(a) + 1;
			  	 $("#"+fromUser+"Li> span ").remove();
			  	 $("#"+fromUser+"LiP> span ").remove();
			  	 $("#"+fromUser+"Li").append("<span class='new badge'>"+value+"</span>");
			  	$("#"+fromUser+"LiP").append("<span class='new badge'>"+value+"</span>");
			  }else{
			  	console.log("add")
			  	$("#"+fromUser+"Li").append("<span class='new badge'>1</span>");
			  	$("#"+fromUser+"LiP").append("<span class='new badge'>1</span>");
			  }
	}
  }
  function removeEtiquetas(fromUser){
  	if( $("#"+fromUser+"Li> span ").length){

  		$("#"+fromUser+"Li> span ").remove();
		$("#"+fromUser+"LiP> span ").remove();
  	}
  }

$(document).ready(function(){
	   $(".button-collapse").sideNav({closeOnClick: true});
	document.getElementById('divChat').style.display = "none";

  //create a new WebSocket object.
  var wsUri = "ws://192.168.1.74:9000/MyWhatsApp/Servidor/server.php";
  websocket = new WebSocket(wsUri);


	$("#btnEnviar").click(function (){
		var to = document.getElementById("usuarioChat").innerHTML;
		if(to == ""){
			return;
		}
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
		//alert("Mensaje de tipo: "+type)
    if(type == 'id')
    {
      $('#divSecret').append("<input type='text' id='secretSocket' value='"+msg.id+"'  name=''>");
			return;
    }
   if(type == 'sucess')
    {
    			
			document.getElementById("secretSocket").setAttribute('value',msg.name+"");
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
	
			$("#listaUsuarios").empty();
			$("#mobile-demo").empty();
			var nombreUsuario =  document.getElementById("secretSocket").value;

			$.each( msg.arreglo, function( key, value ) {
				if(nombreUsuario != value){


				var aux = "<li id=\""+value+"Li\" onclick='setChat(\""+value+"\")' class='collection-item hov avatar green lighten-4'>";
				aux += "<img src='imagenes/hombre.png' alt='' class='circle'>";
				aux += "<h4 class='title'>"+value+"</h4>";
				aux += "</li>";
				$("#listaUsuarios").append(aux)
				var aux2 = "<li id=\""+value+"LiP\" onclick='setChat(\""+value+"\")' class='collection-item avatar'style='background-color: transparent!important'>";
				aux2 += "<a><div class='row'><div class='col s3'><img src='imagenes/hombre.png' alt=''  class='circle icon-lis'> </div>";
				aux2 += "<div class='col s9'><h4 style='color:gray' class='title'>"+value+"</h4></div></div></a>";
				aux2 += "</li>";
				$("#mobile-demo").append(aux2)
				


				var idU = value+"Div";
			//	<!-- <h1 class='nombreChat'>/h1> -->
				var div = "<div id=\""+idU+"\" style='hight:100%;width:100%'></div>";
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

			 var a = "<ul class='collection estilito' >";
			 a += "<div class='chip izquierdo'><img src='imagenes/mujer.png' alt='Contact Person'>"+message+"</div></ul>"
			 //setChat(fromUser);
			 	$("#"+fromUser+"Div").append(a);
			 setEtiquetas(fromUser);
			
			 return;
		}
		if(type == 'userExist'){
			  Materialize.toast('El usuario ya existe, ingrese otro por favor ', 4000) 
			  return;
		}
		if(type == 'usuarioOff'){
			var usuario =  msg.usuario;
			$("#"+usuario+"Div").remove();
			console.log("Borrado Div: "+usuario);
			$("#"+usuario+"Li").remove();
			console.log("Borrado Lista: "+usuario);
			$("#"+usuario+"LiP").remove();
			console.log("Borrado ListaP: "+usuario);
			var nameIn = document.getElementById("usuarioChat").innerHTML;
			if(nameIn == usuario){
				document.getElementById("usuarioChat").innerHTML = "";
			}

		}

  };
  
  websocket.onerror = function(ev){};
  websocket.onclose   = function(ev){};
});




</script>
