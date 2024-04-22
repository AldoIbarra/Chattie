var chatId = "0";
		// Seleccionar chat
		document.querySelectorAll('.chat').forEach(function(element) {
			element.addEventListener('click', function() {
				chatId = this.getAttribute('data-index');

				document.getElementById("chat_selected").textContent = this.textContent.trim();
				if (this.getAttribute('data-estado') === '0') {
					document.getElementById("user_status").textContent = "No disponible";
				} else {
					document.getElementById("user_status").textContent = "";
				}

				$.ajax({
					type: "POST",
					url: "../php/controllers/showMessages.php",
					data: {
						Id: chatId
					},
					success: function(data) {
						try {
							var infoChat = data;
							$('#chat-messages').empty();
							if (infoChat.messages.length > 0) {

								infoChat.messages.forEach(function(row) {
									var messageClass = (row.UserId ===
										'<?php echo $user->getUserName(); ?>'
									) ? "MyMessage" : "YourMessage";
									var messageHTML = '<div class="' + messageClass +
										'">';
									if (messageClass === "YourMessage") {
										messageHTML += '<span>' + row.UserId + '</span>';
									}
									messageHTML += '<span>' + row.Message + '</span>';
									messageHTML += '<span>' + row.CreationDate +
										'</span>';
									messageHTML += '</div>';

									$('#chat-messages').append(messageHTML);
									$('.chat-messages').scrollTop($('.chat-messages')[0]
										.scrollHeight);

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
			});
		});

		// Mandar mensaje
		var sendMessage = document.getElementById("sendMessage");
		sendMessage.addEventListener("click", function(event) {
			event.preventDefault();
			if ($("#messageText").val() != "") {

				$.ajax({
					url: "../php/controllers/insertMessage.php",
					method: "POST",
					data: {
						fromUser: '<?php echo $idUser; ?>' ,
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
		})


		$(document).ready(function() {


			//Recargar mensajes
			setInterval(function() {
				if (chatId != "0")
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
								if (infoChat.messages.length > 0) {

									infoChat.messages.forEach(function(row) {

										var messageClass = (row.UserId ===
											'<?php echo $user->getUserName(); ?>'
										) ? "MyMessage" : "YourMessage";
										var messageHTML = '<div class="' + messageClass +
											'">';
										if (messageClass === "YourMessage") {
											messageHTML += '<span>' + row.UserId + '</span>';
										}
										messageHTML += '<span>' + row.Message + '</span>';
										messageHTML += '<span>' + row.CreationDate +
											'</span>';
										messageHTML += '</div>';

										$('#chat-messages').append(messageHTML);

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
			}, 700);

		})