<!DOCTYPE html>
<html>
<head>
	<title>MyWhatsApp</title>
	<link rel="stylesheet" type="text/css" href="estilo/estilo.css">
	<link rel="stylesheet" type="text/css" href="css/estilo.css">
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

		<div id="Contacts" class="col s3 green lighten-4">
		 		<div class="collection green darken-1">
		        <a href="#!" class="collection-item green lighten-5">
		        <h6>Ernesto</h6>   
		        </a>
		        <a href="#!" class="collection-item green lighten-5">
		        <h6>Javier</h6>
		        </a>
		        <a href="#!" class="collection-item green lighten-5">
		        <h6>Edgardo</h6>
		        </a>
		      	</div>

		</div>
			
		<div class="col s9 green lighten-4">
					
					    <form>
					      
					        
					          <textarea   class="materialize-textarea"></textarea>
					          <label for="textarea1"></label>
					       
					      

					      <!--Comienza el envio de mensaje-->
					      
							    
							      <input placeholder="mensaje" id="first_name2" type="text" class="validate">
							      <label class="active" for="first_name2"></label>
							   

							    <!--No olvides el botón prro :v-->
							    <button class="btn waves-effect waves-light" type="submit" name="action">enviar
								    <i class="material-icons right">send</i>
								</button>

						

					    </form>
					
			</div>
			
	</div>

		
		

	</div>
	

</body>
</html>