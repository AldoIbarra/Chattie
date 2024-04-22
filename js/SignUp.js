(function () {
    //Function IIFE
    const formSignup = document.getElementById("formSignup");
    formSignup.onsubmit = function (e) {
      //Quitar submit
      e.preventDefault();
  
      const iUsername = document.getElementById("UserName");
      const iEmail = document.getElementById("Email");
      const iPassword = document.getElementById("Password");
      const idateBirth = document.getElementById("DateBirth");
  
      let errors = [];
  
      if (
        !iUsername ||
        !iUsername.value.trim() ||
        !iEmail ||
        !iEmail.value.trim() ||
        !iPassword ||
        !iPassword.value.trim() ||
        !idateBirth ||
        !idateBirth.value.trim()
      ) {
        errors.push({ msg: "Favor de llenar todos los campos." });
      }
  
      if (errors.length) {
        showModal(errors);
        return;
      }
  
      let xhr = new XMLHttpRequest();
  
      const user = {
        UserName: iUsername.value.trim(),
        Email: iEmail.value.trim(),
        Password: iPassword.value.trim(),
        DateBirth: idateBirth.value.trim(),
      };
  
      xhr.open("POST", "php/controllers/SignUp.php", true); // true en modo asicrono
      xhr.onreadystatechange = function () {
        //Termina peticion 200 = OK
  
        try {
          if (xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
            let res = JSON.parse(xhr.response);
            if (res.success != true) {
              showModal([{ msg: res.msg }]);
              return;
            }
            // Sucess ...
            showModal([{ msg: res.msg }]);
            window.location.replace("SignIn.php");
          }
        } catch (error) {
          // Se imprime el error del servidor
          console.error(xhr.response);
        }
      };
      //Enviarlo en formato JSON
      xhr.send(JSON.stringify(user));
    };
  
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
  