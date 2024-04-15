<?php
/*
Por ahora solo muestra el nombre del usuario
los chats del usuario
y los mensajes de cada chat
*/

// Comprueba el estado de la sesión
$sessionStatus = session_status();
session_start();

if (!isset($_SESSION['AUTH'])) {
    // Si la sesion de usuario no existe redirigir a login
    header('Location: signIn.php');
    exit;
}
require_once '../php/models/User.php';
require_once '../php/models/Chats.php';
require_once '../php/db.php';

$idUser = $_SESSION['AUTH'];
$mysqli = db::connect();

$user = User::findUserById($mysqli, (int) $idUser);
$chats = Chat::mostrarChats($mysqli, $idUser);

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="Chats.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  </head>
  <body>
    <div class="container">
        <div class="row header-chat">
            <div class="col-4">
              <div>
                <span><?php echo $user->getUsername(); ?></span>
                <span>En línea</span>
              </div>
              <div>
                <a href=""><img src="AddIcon.svg" alt=""></a>
                <a href=""><img src="MessageIcon.svg" alt=""></a>
                <a href=""><img src="UserIcon.svg" alt=""></a>
              </div>
            </div>
            <div class="col-8">
              <div>
                <span>Usuario_0</span>
                <span>No Disponible</span>
              </div>
              <div>
                <a href=""><img src="VideoIcon.svg" alt=""></a>
              </div>
            </div>
        </div>
        <div class="row container-chats">
            <div class="col-4 chat-list">
              <!-- <div class="chat">Usuario 1</div>
              <div class="chat">Usuario 2</div>
              <div class="chat">Grupo 1</div>
              <div class="chat">Usuario 3</div> -->
              <?php // se muestran los chats
          foreach ($chats as $chat) {
              echo '<div class="chat" id="chat'.$chat->getID().'" data-index="'.$chat->getID().'">'.$chat->getName().'</div>';
          }
?>
            </div>
            <div class="col-8 messages">
              <div class="chat-messages">
                <!-- <div class="YourMessage">
                  <span>Hola, ¿qué tal?</span>
                  <span>19:00</span>
                </div>
                <div class="MyMessage">
                  <span>Bien, ¿y tú?</span>
                  <span>19:05</span>
                </div> -->
                
              </div>
              <div class="footer-container">
                <footer class="container">
                  <div class="row">
                    <div class="col-12">
                      <div class="row">
                        <div class="col-1">
                          <a href=""><img src="Export.svg" alt=""></a>
                        </div>
                        <div class="col-10">
                          <input type="text">
                        </div>
                        <div class="col-1">
                          <a href=""><img src="Send.svg" alt=""></a>
                        </div>
                      </div>
                    </div>
                  </div>
                </footer>
              </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
document.querySelectorAll('.chat').forEach(function(element) {
    element.addEventListener('click', function() {
        var chatId = this.getAttribute('data-index');

        $.ajax({
            type: "POST",
            url: "../php/controllers/showMessages.php",
            data: { Id: chatId },
            success: function(response) {
                try {
                    var infoChat = response;
                    $('.chat-messages').empty();
                    if (infoChat.messages.length > 0) {
                      console.log("<?php echo $idUser; ?>");
                    infoChat.messages.forEach(function(row) {
                      console.log(row.UserId);
                        var messageClass = (row.UserId === '<?php echo $user->getUserName(); ?>') ? "MyMessage" : "YourMessage";
                        var messageHTML = '<div class="' + messageClass + '">';
                        messageHTML += '<span>' + row.Message + '</span>';
                        messageHTML += '<span>' + row.CreationDate + '</span>';
                        messageHTML += '</div>';
                        
                        $('.chat-messages').append(messageHTML);
                    });
                  }
                  else{
                    var noMessages = '<span>No hay mensajes disponibles</span>';
                    $('.chat-messages').append(noMessages);
                  }
                } catch (error) {
                    console.error("Error al analizar JSON:", error);
                }
            },
            error: function() {
                console.log("Error al obtener mensajes.");
            },
        });
    });
});
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
  </body>
</html>