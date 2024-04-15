(function () { //Function IIFE
    
    const formLogin = document.getElementById("formLogin");
    formLogin.onsubmit = function (e) {
        //Quitar submit
        e.preventDefault();
        const iEmail = document.getElementById("Email");
        const iPassword = document.getElementById("Password");

        let errors = [];
        if(!iEmail.value || !iEmail.value.trim() ||
        !iPassword.value || !iPassword.value.trim()) {
            errors.push({ msg: "Favor de llenar todos los campos." });
        }

        if(errors.length) { 
            showModal(errors);
            return;
        }
        
        let xhr = new XMLHttpRequest();
        
        const user = {
            email: iEmail.value.trim(),
            password: iPassword.value.trim()
        };
       
        xhr.open("POST", "php/controllers/signIn.php", true); // true en modo asicrono
        
        xhr.onreadystatechange = function () {
            //Termina peticion 200 = OK
            
            try {
                if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200)  {
                    let res = JSON.parse(xhr.response);
                    if(res.success != true) {
                        showModal([{ msg: res.msg }]);
                        return;
                    }
                    // Sucess ...
                    showModal([{ msg: res.msg }]);
          
                    window.location.replace("Chats/Chats.php");
                }
            } catch(error) {
                // Se imprime el error del servidor
                console.error("Error: " + xhr.response);
            }
            
        }
        //Enviarlo en formato JSON
        xhr.send(JSON.stringify(user));
    }

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
})();