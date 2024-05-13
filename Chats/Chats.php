<?php
/*
Por ahora solo muestra el nombre del usuario
los chats del usuario
los mensajes de cada chat
se envian y reciben mensajes
*/

// Comprueba el estado de la sesión
$sessionStatus = session_status();
session_start();

if (!isset($_SESSION['AUTH'])) {
    // Si la sesion de usuario no existe redirigir a login
    header('Location: ../signIn.php');
    exit;
}
require_once '../php/models/User.php';
require_once '../php/models/Chats.php';
require_once '../php/db.php';

$idUser = $_SESSION['AUTH'];
$mysqli = db::connect();

$user = User::findUserById($mysqli, (int) $idUser);
$chats = Chat::mostrarChats($mysqli, $idUser);
$contacts = User::getUserContacts($mysqli, $idUser);

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Chats</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="Chats.css">
	<link
		href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
		rel="stylesheet">
</head>

<body>
	<div class="container">
		<!-- Modal -->
		<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="customModalLabel"
			aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="customModalLabel">Aviso</h5>
						<button type="button" class="btn-close btn-close-white close" data-bs-dismiss="modal"></button>
						</button>
					</div>
					<div class="modal-body">
						<p id="modalMessage"></p>
					</div>
				</div>
			</div>
		</div>
		<div class="row header-chat">
			<div class="col-4">
				<div>
					<span><?php echo $user->getUsername(); ?></span>
					<span>En línea</span>
				</div>
				<div>
					<a href=""><img src="AddIcon.svg" alt=""></a>
					<button onclick="showContacts()"><img src="MessageIcon.svg" alt=""></button>
					<a href=""><img src="UserIcon.svg" alt=""></a>
				</div>
			</div>
			<div class="col-8">
				<div>
					<span id="chat_selected">Bienvenido</span>
					<span id="user_status"> </span>
				</div>
				<div>
					<button onclick="encryptData()"><img id="isDataEncrypted" src=" Unlock_fill.svg" alt=""></button>
					<a href=""><img src="VideoIcon.svg" alt=""></a>
				</div>
			</div>
		</div>
		<div class="row container-chats">
			<div class="col-4 chat-list">
				<?php // se muestran los chats
                    if(count($chats) < 1) {
                        echo '<span style="display: flex; justify-content: center;">Aún no existen chats</span>';
                    } else {
                        foreach ($chats as $chat) {
                            echo '<div class="chat" id="chat'.$chat->getID().'" data-index="'.$chat->getID().'" data-estado="'.$chat->getIsGroup().'">'.$chat->getName().'</div>';
                        }
                    }

?>
			</div>
			<div class="col-8 messages">
				<div class="chat-messages" id="chat-messages">

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
										<input type="text" id="messageText">
									</div>
									<div class="col-1">
										<a href="" id="sendMessage"><img src="Send.svg" alt=""></a>
									</div>
								</div>
							</div>
						</div>
					</footer>
				</div>
			</div>
		</div>
	</div>

	<div class="modal" id="ContactsModal">
		<div class="modal-dialog">
			<div class="modal-content">

				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title">Inicia una conversación</h4>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
				</div>

				<!-- Modal body -->
				<div class="Contacts">
					<?php // se muestran los contactos
        if(count($contacts) < 1) {
            echo '<span style="display: flex; justify-content: center;">No hay nungún contacto disponible</span>';
        } else {
            foreach ($contacts as $contact) {
                echo '<button onClick="testModal('.$idUser.',\''.$contact->getID().'\')" class="Contact inter">'.$contact->getUsername().' </button>';
            }
        }
?>
				</div>

				<!-- Modal footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
				</div>

			</div>
		</div>
	</div>


	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

	<script>
		var chatId = "0";
		// Seleccionar chat
		document.querySelectorAll('.chat').forEach(function(element) {
			element.addEventListener('click', function() {

				setChatReady(this.getAttribute('data-index'), this.textContent.trim(), this.getAttribute(
					'data-estado'));

				showMessages(false);
			});
		});

		// Mandar mensaje
		var sendMessage = document.getElementById("sendMessage");
		sendMessage.addEventListener("click", function(event) {
			sendMsg();
		})


		$(document).ready(function() {
			//Recargar mensajes
			setInterval(function() {
				if (chatId != "0")
					showMessages(true);
			}, 700);

		})

		function showContacts() {
			$('#ContactsModal').modal("show");;
		}

		function testModal(actualUserId, contactId) {
			checkIfChatExists(actualUserId, contactId);
		}

		function setChatReady(chatIndex, chatName, status) {
			chatId = chatIndex;
			document.getElementById("chat_selected").textContent = chatName;

			if (status === '0') {
				document.getElementById("user_status").textContent = "No disponible";
			} else {
				document.getElementById("user_status").textContent = "";
			}
		}

		function showMessages(onInterval) {
			var img = document.getElementById('isDataEncrypted');
			$.ajax({
				type: "POST",
				url: "../php/controllers/showMessages.php",
				data: {
					Id: chatId
				},
				success: function(data) {
					try {
						var infoChat = data;
						$('.chat-messages').empty();

						// checar si el chat esta encriptado o no
						if (infoChat.messages.length > 0) {
							infoChat.messages.forEach(function(row) {
								if (row.isDataEncrypted) {
									img.src = 'Lock_fill.svg'; // esta encriptado
								} else {
									img.src = 'Unlock_fill.svg'; // no esta encriptado
								}

								var messageClass = (row.UserId ===
									'<?php echo $user->getUserName(); ?>'
								) ? "MyMessage" : "YourMessage";
								var messageHTML = '<div class="messageContainer"><div class="' +
									messageClass +
									'">';
								if (messageClass === "YourMessage") {
									messageHTML += '<span>' + row.UserId + '</span>';
								}
								messageHTML += '<span>' + row.Message + '</span>';
								messageHTML += '<span>' + row.CreationDate +
									'</span>';
								messageHTML += '</div></div>';

								$('#chat-messages').append(messageHTML);

								if (!onInterval) {
									$('.chat-messages').scrollTop($('.chat-messages')[0]
										.scrollHeight);
								}
							});
						} else {
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
		}

		function sendMsg() {
			event.preventDefault();
			if ($("#messageText").val() != "") {

				$.ajax({
					url: "../php/controllers/insertMessage.php",
					method: "POST",
					data: {
						fromUser: <?php echo $idUser; ?> ,
						fromChat: chatId,
						message: $("#messageText").val()
					},
					dateType: "text",
					success: function(data) {
						$("#messageText").val("");

						$('#chat-messages').animate({
							scrollTop: $('#chat-messages').prop("scrollHeight")
						}, "slow");
					}
				})
			}
		}

		function checkIfChatExists(actualUserId, contactId) {
			$.ajax({
				type: "POST",
				url: "../php/controllers/getChatsByUsers.php",
				data: {
					userId: actualUserId,
					contactId: contactId
				},
				success: function(data) {
					try {
						var chat = data;
						if (chat != null) {
							$('#ContactsModal').modal("hide");;
							setChatReady(chat.Id, chat.Name, 0);
						} else {
							console.log('se tiene que crear un chat');
						}
					} catch (error) {
						console.error("Error al analizar JSON:", error);
					}
				},
				error: function() {
					console.log("Error al obtener mensajes.");
				},
			});
		}

		function encryptData() {
			let messages = [];

			$.ajax({
				type: "POST",
				url: "../php/controllers/encryptData.php",
				data: {
					Id: chatId
				},
				success: function(data) {
					try {

						var chat = data.isEncrypted;

						if (chat.isDataEncrypted === 1) {
							messages.push({
								msg: "Se han desencriptado los datos con exito."
							});
						} else {
							messages.push({
								msg: "Se han encriptado los datos con exito."
							});
							messages.push({
								msg: "Nadie fuera de este chat puede leerlos."
							});

						}

						showModal(messages);
					} catch (error) {
						console.error("Error al analizar JSON:", error);
					}
				},
				error: function() {
					console.log("Error al obtener mensajes.");
				},
			});
		}
	</script>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
	</script>
	<script src="../js/chats.js"></script>
</body>

</html>