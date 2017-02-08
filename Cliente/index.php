

<!DOCTYPE html>
<html>
<head>
	<title>Inicio</title>
	 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.1/css/materialize.min.css">
	 <link rel="stylesheet" type="text/css" href="css/style.css">
   <script src="jquery-3.1.1.js"></script>
</head>
<body class="dark cyan" id="login">
	<div id="login-page" class="row">
    <div class="col s12 z-depth-6 card-panel">
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
  <div style="visibility: hidden;" id="divSecret">

  </div>
</body>
</html>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
  //create a new WebSocket object.
  var wsUri = "ws://localhost:9000/MyWhatsApp/Servidor/server.php";
  websocket = new WebSocket(wsUri);

  websocket.onopen = function(ev) { // Colocar lista de usuarios

  }

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
    if(type == 'id')
    {
      $('#divSecret').append("<input type='text' id='secretSocket' value='"+msg.id+"'  name=''>");
    }
   if(type == 'sucess')
    {
		
      window.location= "inicio.php";
    }

  };

  /*websocket.onerror = function(ev){$('#message_box').append("<div class=\"system_error\">Error Occurred - "+ev.data+"</div>");};
  websocket.onclose   = function(ev){$('#message_box').append("<div class=\"system_msg\">Connection Closed</div>");}; */
});




</script>
