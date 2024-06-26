<?php
require 'php/db.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrarse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  </head>
  <body>
     <!-- Modal -->
  <div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="customModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="customModalLabel">Aviso</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p id="modalMessage"></p>
        </div>
      </div>
    </div>
  </div>
    <div class="container">
        <div class="row">
            <div class="col-5 background-chattie">
                <h1 class="bebas h-text white-text">ENVÍA, RECIBE, COMUNICA</h1>
                <img src="Chattie-front.png" alt="">
            </div>
            <div class="col-7 sign-up-form background-form">
                <div style="background-color: #00ADB5;">
                    <form action="#" id="formSignup" method="post">
                        <h1 class="bebas white-text">REGISTRATE</h1>
                        <input placeholder="Tu nombre" type="text" id="UserName">
                        <input placeholder="Correo electronico" type="text" id="Email">
                        <input placeholder="Contraseña" type="text" id="Password">
                        <input placeholder="Fecha de nacimiento" id="DateBirth" class="textbox-n" type="text" onfocus="(this.type='date')" onblur="(this.type='text')" id="date" />
                        <button class="inter white-text">Registrate</button>
                        <span class="inter">¿Ya tienes cuenta?, <a href="signIn.php">¡Ingresa!</a></span>
                    </form>
                    <script src="js/signup.js"></script>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>