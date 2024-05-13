function showModal(messages) {
    const modal = document.getElementById("myModal");
    const modalContent = document.querySelector("#myModal .modal-content");
    const modalMessage = document.getElementById("modalMessage");

    // Construir mensajes
    let messageHTML = '';
    messages.forEach(function (message) {
        messageHTML += `<p>${message.msg}</p>`;
    });


    modalMessage.innerHTML = messageHTML;

    // Mostrar modal
    modal.style.display = "block";

    const closeBtn = document.querySelector("#myModal .close");
    closeBtn.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
}