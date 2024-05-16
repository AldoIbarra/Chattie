let localStream;
let remoteStream;

let startVideocall = async () => {
    if(document.getElementById('chat_selected').textContent != 'Bienvenido'){
        $('#videocallModal').modal("show");;
        console.log('Hay un chat seleccionado');
        localStream = await navigator.mediaDevices.getUserMedia({video:true, audio:false})
        document.getElementById('user-1').srcObject = localStream
        document.getElementById('user-2').srcObject = localStream
    }
}