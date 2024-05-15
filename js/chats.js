function showModal(messages) {
    const modal = document.getElementById("encryptionModal");
    const modalContent = document.querySelector("#encryptionModal .modal-content");
    const modalMessage = document.getElementById("modalMessage");

    // Construir mensajes
    let messageHTML = '';
    messages.forEach(function (message) {
        messageHTML += `<p>${message.msg}</p>`;
    });


    modalMessage.innerHTML = messageHTML;

    // Mostrar modal
    modal.style.display = "block";

    const closeBtn = document.querySelector("#encryptionModal .close");
    closeBtn.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
}