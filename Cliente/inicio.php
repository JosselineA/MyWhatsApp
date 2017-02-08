<!DOCTYPE html>
<html>
<head>
	<title>MyWhatsApp</title>
	<link rel="stylesheet" type="text/css" href="estilo/estilo.css">
	<link rel="stylesheet" href="materialize/css/materialize.min.css">
	 <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
	 <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	
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
		

			<div  id="contactos" class="col s3 teal accent-4">Chats</div>
				<div class="row">
				    <form class="col s9">
				      <div class="row">
				        <div class="input-field col s12">
				          <textarea   class="materialize-textarea"></textarea>
				          <label for="textarea1"></label>
				        </div>
				      </div>

				      <!--Comienza el envio de mensaje-->
				      <div class="row">
						    <div class="input-field col s10">
						      <input placeholder="mensaje" id="first_name2" type="text" class="validate">
						      <label class="active" for="first_name2"></label>
						    </div>

						    <!--No olvides el botón prro :v-->
						    <button class="btn waves-effect waves-light" type="submit" name="action">enviar
							    <i class="material-icons right">send</i>
							</button>

					  </div>

				    </form>
				</div>
			
	</div>

		

	</div>
	

</body>
</html>