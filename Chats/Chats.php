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
	<section class="header-chat-section">
		<div class="container">
			<div class="row header-chat">
				<div class="col-4">
					<div>
						<span><?php echo $user->getUsername(); ?></span>
						<!-- <span>En línea</span> -->
						<select class="custom-select" id="statusSelect" onchange="saveStatusUser()">
							<option value="1" <?php if ($user->getStatus() == 1) {
							    echo "selected";
							} ?>
								>En línea</option>
							<option value="0" <?php if ($user->getStatus() == "0") {
							    echo "selected";
							} ?>>No disponible
							</option>
						</select>
					</div>
					<div class="user-options">
						<button onclick='closeSesion()'><img src='Close_square_fill.svg' alt=''></button>
						<button onclick='showContacts()'><img src='AddIcon.svg' alt=''></button>
						<a href=''><img src='UserIcon.svg' alt=''></a>
					</div>
				</div>
				<div class="col-8 chat-info">
					<div>
						<span id="chat_selected">Bienvenido</span>
						<span id="user_status"> </span>
					</div>
					<div class="chat-options">
						<button onclick="newEmail()">
							<img src='MessageIcon.svg' alt=''>
						</button>
						<button onclick="encryptData()">
							<img id="isDataEncrypted" src="Unlock_fill.svg" alt="">
						</button>
						<button onclick="startVideocall(); callOtherUser();">
							<img src="VideoIcon.svg" alt="">
						</button>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="container-chats-section">
		<div class="container">
			<div class="row container-chats">
				<div class="col-4 chat-list">
					<?php // se muestran los chats
                        if(count($chats) < 1) {
                            echo '<span style="display: flex; justify-content: center;">Aún no existen chats</span>';
                        } else {
                            foreach ($chats as $chat) {
                                echo '<div class="chat" id="chat'.$chat->getID().'" data-index="'.$chat->getID().'" data-group="'.$chat->getIsGroup().'" data-status="'.$chat->getStatusUser().'"   >'.$chat->getName().'</div>';
                            }
                        }

?>
				</div>
				<div class="col-8 messages">
					<div class="chat-messages" id="chat-messages">
						<div class="initial-Display">
							<img src="../Chattie-front.png" alt="">
							<img src="../videocall.gif" alt="">
						</div>
					</div>
					<div class="footer-container">
						<footer class="container">
							<div class="row">
								<div class="col-12">
									<div class="row">
										<div class="col-1">
											<label for="file-upload" class="custom-file-upload">
												<img src="Export.svg" alt="">
											</label>
											<input id="file-upload" type="file" name="files" />
										</div>
										<div class="col-1">
											<button id="location" onclick="sendLocation();">
												<img src="location.svg" alt="">
											</button>
										</div>
										<div class="col-9">
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
	</section>

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
            echo '<button onClick="nuevaConversacion('.$idUser.',\''.$contact->getID().'\')" class="Contact inter">'.$contact->getUsername().' </button>';
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

	<div class="modal" id="encryptionModal" tabindex="-1" role="dialog" aria-labelledby="customModalLabel"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="customModalLabel">Aviso</h5>
					<button type="button" class="btn-close btn-close-white close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<p id="modalMessage"></p>
				</div>
			</div>
		</div>
	</div>

	<div class="modal" id="videocallModal" tabindex="-1" role="dialog" aria-labelledby="customModalLabel"
		aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="customModalLabel">Videollamada</h5>
					<button type="button" class="btn-close btn-close-black close" onclick="finishVideocall()"></button>
				</div>
				<div class="modal-body">
					<video id="localVideo" autoplay muted></video>
					<video id="remoteVideo" autoplay></video>
					<div class="videocall-options">
						<button onclick="finishVideocall()">
							<img src="phone.svg" alt="">
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal" id="EmailModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="email-header">
					<h4 class="modal-title">Nuevo Correo electronico</h4>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
				</div>
				<div class="email-form">
					<form method="POST">
						<label for="email">Correo electronico</label>
						<input type="email" id="email" name="email" placeholder="Correo electronico" required="">
						<label for="subject">Asunto</label>
						<input type="text" id="subject" name="subject" placeholder="Asunto" required="">
						<label for="message">Mensaje</label>
						<textarea name="message" id="message" cols="30" rows="10" placeholder="Mensaje"
							required=""></textarea>
						<button type="submit" class="inter white-text" name="send">Enviar correo</button>
					</form>
				</div>
			</div>
		</div>
		<?php include 'email.php'; ?>
	</div>


	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

	<script>
		if (document.getElementById('chat_selected').textContent == 'Bienvenido') {
			$(".chat-options").hide();
			$(".footer-container").hide();
		}


		var statusSelect = document.getElementById("statusSelect");

		var currentChat;
		var chatId = "0";
		var chatIsGroup = "0";
		// Seleccionar chat
		document.querySelectorAll('.chat').forEach(function(element) {
			element.addEventListener('click', function() {
				$(".footer-container").show();
				$(".chat-options").show();
				currentChat = this.getAttribute('data-index');

				setChatReady(this.getAttribute('data-index'), this.textContent.trim(), this.getAttribute(
					'data-status'), this.getAttribute('data-group'));

				showMessages(false);
			});
		});

		function callOtherUser() {
			console.log('currentChat');
			console.log(currentChat);

			// event.preventDefault();
			// $.ajax({
			// 	url: "../php/controllers/callUser.php",
			// 	method: "POST",
			// 	data: {
			// 		fromUser: <?php echo $idUser; ?> ,
			// 		fromChat: currentChat
			// 	},
			// 	dateType: "text",
			// 	success: function(data) {
			// 		console.log('Comenzó la llamada');
			// 	}
			// })
		}

		// Mandar mensaje
		var sendMessage = document.getElementById("sendMessage");
		sendMessage.addEventListener("click", function(event) {
			// 1 = Mensaje de tipo texto
			sendMsg(1, $("#messageText").val());
			$('#messageText').val('');
		})



		$(document).ready(function() {
			//Recargar mensajes
			setInterval(function() {
				if (chatId != "0")
					showMessages(true);

				if (statusSelect.value == 1)
					isUserOnline();
			}, 700);
		})

		function showContacts() {
			$('#ContactsModal').modal("show");;
		}

		function nuevaConversacion(actualUserId, contactId) {
			checkIfChatExists(actualUserId, contactId);
		}
		//ChatId, chatName, status, IsGroup?
		function setChatReady(chatIndex, chatName, status, indexisGroup) {
			$(".footer-container").show();
			chatId = chatIndex;
			chatIsGroup = indexisGroup;
			document.getElementById("chat_selected").textContent = chatName;

			if (status == "0") {
				document.getElementById("user_status").textContent = "No disponible";
			} else if (status == 1) {
				document.getElementById("user_status").textContent = "En línea";
			} else {
				document.getElementById("user_status").textContent = "";
			}
		}


		function changeIconEncrypt(isDataEncrypted) { // cambia el icono/boton para encriptar los  mensajes
			var img = document.getElementById('isDataEncrypted');
			if (isDataEncrypted) {
				img.src = 'Lock_fill.svg'; // esta encriptado
			} else {
				img.src = 'Unlock_fill.svg'; // no esta encriptado
			}
		}

		function changeStatusUser(status) {
			if (status == 0) {
				document.getElementById("user_status").textContent = "No disponible";
			} else if (status == 1) {
				document.getElementById("user_status").textContent = "En línea";
			} else {
				document.getElementById("user_status").textContent = "";
			}
		}

		//Esta funcion solo envia archivos ya que el "tipo" que utiliza es 3
		$('#file-upload').on('change', function() {
			var fileInput = this;
			if (fileInput.files.length > 0) {
				var formData = new FormData();
				formData.append('file', fileInput.files[0]);
				formData.append('fromUser', <?php echo $idUser; ?> );
				formData.append('fromChat', chatId);
				formData.append('message', '');
				formData.append('Type', 3);

				$.ajax({
					url: "../php/controllers/insertMessageFile.php",
					type: "POST",
					data: formData,
					processData: false, // No procesar los datos
					contentType: false, // No establecer ningún tipo de contenido
					success: function(data) {
						console.log('Archivo enviado exitosamente');
						console.log(data);
					},
					error: function(xhr, status, error) {
						console.error('Error al enviar el archivo: ' + error);
					}
				});
			}
		});

		function showMessages(onInterval) {
			$.ajax({
				type: "POST",
				url: "../php/controllers/showMessages.php",
				data: {
					Id: chatId
				},
				success: function(infoChat) {
					try {
						$('.chat-messages').empty();
						// checar si el chat esta encriptado o no
						if (infoChat.messages.length > 0) {
							changeIconEncrypt(infoChat.messages[0].isDataEncrypted);
							infoChat.messages.forEach(function(row) {
								if (row.Type == 1) {
									var messageClass = (row.UserId ===
										'<?php echo $user->getUserName(); ?>'
									) ? "MyMessage" : (chatIsGroup === "1" ? "YourMessageGroup" :
										"YourMessage");
									messageHTML = '<div class="' + messageClass + '"><div>';
									if (messageClass === "YourMessageGroup") {
										messageHTML += '<span>' + row.UserId + '</span>';
									} else if (messageClass === "YourMessage") {
										changeStatusUser(row.statusUser);
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
								} else if (row.Type == 2) {
									var messageClass = (row.UserId ===
										'<?php echo $user->getUserName(); ?>'
									) ? "MyMessage" : (chatIsGroup === "1" ? "YourMessageGroup" :
										"YourMessage");
									messageHTML = '<div class="' + messageClass + '"><div>';
									if (messageClass === "YourMessageGroup") {
										messageHTML += '<span>' + row.UserId + '</span>';
									} else if (messageClass === "YourMessage") {
										changeStatusUser(row.statusUser);
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
									// console.log(row);
									// var tagId = 'map' + row.Id;
									// var [longitude, latitude] = row.Message.split(', ');
									// var messageClass = (row.UserId ===
									// '<?php echo $user->getUserName(); ?>'
									// ) ? "MyMessage" : (chatIsGroup === "1" ? "YourMessageGroup" : "YourMessage");
									// messageHTML = '<div class="' + messageClass + '"><div>';
									// if (messageClass === "YourMessageGroup") {
									// 	messageHTML += '<span>' + row.UserId + '</span>';
									// }
									// else if (messageClass === "YourMessage"){
									// 	changeStatusUser(row.statusUser);
									// }
									// messageHTML += '<div class="map-message" id="' + tagId + '"></div>';
									// messageHTML += '<span>' + row.CreationDate +
									// 	'</span>';

									// $('#chat-messages').append(messageHTML);

									// showMap(Number(longitude), Number(latitude), tagId);

									// if (!onInterval) {
									// 	$('.chat-messages').scrollTop($('.chat-messages')[0]
									// 		.scrollHeight);
									// }
								} else if (row.Type == 3) {
									var messageClass = (row.UserId ===
										'<?php echo $user->getUserName(); ?>'
									) ? "MyMessage" : (chatIsGroup === "1" ? "YourMessageGroup" :
										"YourMessage");
									messageHTML = '<div class="' + messageClass + '"><div>';
									if (messageClass === "YourMessageGroup") {
										messageHTML += '<span>' + row.UserId + '</span>';
									} else if (messageClass === "YourMessage") {
										changeStatusUser(row.statusUser);
									}

									messageHTML += '<span>';

									const tempDiv = document.createElement('div');
									if (row.Type === 3) {
										displayFile(row.Message, tempDiv);
									} else {
										tempDiv.textContent = row.Message;
									}
									messageHTML += tempDiv.innerHTML;

									messageHTML += '</span><span>' + row.CreationDate + '</span>';
									messageHTML += '</div></div>';

									$('#chat-messages').append(messageHTML);

									if (!onInterval) {
										$('.chat-messages').scrollTop($('.chat-messages')[0]
											.scrollHeight);
									}
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

		function sendMsg(type, message) {
			if (type == 1) {
				event.preventDefault();
			}
			if ((type == 1 && $("#messageText").val() != "") || type == 2) {

				$.ajax({
					url: "../php/controllers/insertMessage.php",
					method: "POST",
					data: {
						fromUser: <?php echo $idUser; ?> ,
						fromChat: chatId,
						message: message,
						Type: type
					},
					dateType: "text",
					success: function(data) {
						console.log('mensaje enviado exitosamente');
						console.log(data);
						$("#messageText").val("");

						$('#chat-messages').animate({
							scrollTop: $('#chat-messages').prop("scrollHeight")
						}, "slow");
					}
				})
			}
		}

		function sendLocation() {
			//Esta funcion solo envía ubicación ya que el "tipo" que utiliza es 2


			getPosition().then(function(value) {
				var coords = value.coords.latitude + ', ' + value.coords.longitude;
				sendMsg(2, coords);
				//sendMsg(2, coords);
			});
			// if ($("#messageText").val() != "") {

			// 	$.ajax({
			// 		url: "../php/controllers/insertMessage.php",
			// 		method: "POST",
			// 		data: {
			// 			fromUser: <?php echo $idUser; ?> ,
			// 			fromChat: chatId,
			// 			message: $("#messageText").val(),
			// 			Type: 1
			// 		},
			// 		dateType: "text",
			// 		success: function(data) {
			// 			console.log('mensaje enviado exitosamente');
			// 			console.log(data);
			// 			$("#messageText").val("");

			// 			$('#chat-messages').animate({
			// 				scrollTop: $('#chat-messages').prop("scrollHeight")
			// 			}, "slow");
			// 		}
			// 	})
			// }
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
						console.log('intenta crear chat');
						var chat = data;
						console.log(chat);
						if (chat != null) {
							console.log('ya existe el chat');
							$('#ContactsModal').modal("hide");
							setChatReady(chat.Id, chat.Name, 0);
						} else {
							console.log('se tiene que crear un chat');
							createPrivateChat(actualUserId, contactId);
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
					console.log("Error al encriptar los mensajes.");
				},
			});
		}

		function isUserOnline() {
			var selectedValue = statusSelect.value;
			$.ajax({
				type: "POST",
				url: "../php/controllers/isUserOnline.php",
				data: {
					Id: <?php echo $idUser; ?>
				},
				// success: function(data) {
				// 	try {
				// 		console.log("se esta actualizando la hora del usuario");
				// 	} catch (error) {
				// 		console.error("Error al analizar JSON:", error);
				// 	}
				// },
				error: function() {
					console.log("Error al cambiar el estatus");
				},
			});
		}

		function saveStatusUser() {
			//var statusSelect = document.getElementById("statusSelect");
			var selectedValue = statusSelect.value;
			$.ajax({
				type: "POST",
				url: "../php/controllers/saveStatusUser.php",
				data: {
					status: selectedValue,
					Id: <?php echo $idUser; ?>
				},
				// success: function(data) {
				// 	try {
				// 		console.log(data);
				// 	} catch (error) {
				// 		console.error("Error al analizar JSON:", error);
				// 	}
				// },
				error: function() {
					console.log("Error al cambiar el estatus");
				},
			});
		}

		function closeSesion() {
			$.ajax({
				type: "POST",
				url: "../php/controllers/logout.php",
				data: {
					Id: <?php echo $idUser; ?>
				},
				success: function() {
					//header("Location: ../../signIn.php");
					window.location.replace("../signIn.php");

				},
				error: function() {
					console.log("Error al cerrar sesión");
				},
			});
		}

		function createPrivateChat(actualUserId, contactId) {
			event.preventDefault();
			$.ajax({
				url: "../php/controllers/createPrivateChat.php",
				method: "POST",
				data: {
					actualUserId: actualUserId,
					contactId: contactId
				},
				dateType: "text",
				success: function(data) {
					//ChatId, chatName, status, IsGroup?
					console.log(data);
					console.log("Se creó el chat");
					checkIfChatExists(actualUserId, contactId);
				}
			})
		}

		function newEmail() {
			console.log('new email');
			console.log('currentChat');
			console.log(currentChat);
			$('#EmailModal').modal("show");;
		}

		function normalizePath(filePath) {
			const segments = filePath.split('/');
			const stack = [];

			for (const segment of segments) {
				if (segment === '..') {
					if (stack.length) {
						stack.pop();
					}
				} else if (segment !== '.' && segment !== '') {
					stack.push(segment);
				}
			}

			return stack.join('/');
		}


		function displayFile(filePath, container) {
			filePath = normalizePath(filePath);
			const fileExtension = filePath.split('.').pop().toLowerCase();

			switch (fileExtension) {
				case 'jpg':
				case 'jpeg':
				case 'png':
				case 'gif':
					displayImage(filePath, container);
					break;
				case 'mp4':
				case 'webm':
				case 'ogg':
					displayVideo(filePath, container);
					break;
				default:
					displayFileLink(filePath, container);
					break;
			}
		}

		function displayImage(filePath, container) {
			const img = document.createElement('img');
			img.src = "../" + filePath;
			img.alt = 'Image';
			img.style.maxWidth = '100%';
			container.appendChild(img);
		}

		function displayVideo(filePath, container) {
			const video = document.createElement('video');
			video.src = "../" + filePath;
			video.controls = true;
			video.style.maxWidth = '100%';
			container.appendChild(video);
		}

		function displayFileLink(filePath, container) {
			const link = document.createElement('a');
			link.href = "../" + filePath;
			link.textContent = filePath.split('/').pop();
			link.download = '';
			container.appendChild(link);
		}
	</script>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
	</script>
	<script src="https://mesquite-malachite-pirate.glitch.me/socket.io/socket.io.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCMKezTeAW43Iu6jRKx9dG5ooUDi3ZS7uY"></script>
	<script src="../js/chats.js"></script>
	<script src='Videocall.js'></script>
	<script src="maps.js"></script>
</body>

</html>