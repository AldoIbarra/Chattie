let localStream;
let remoteStream;


let startVideocall = async () => {
    if(document.getElementById('chat_selected').textContent != 'Bienvenido'){
        $('#videocallModal').modal("show");;
        console.log('Hay un chat seleccionado');
        localStream = await navigator.mediaDevices.getUserMedia({video:true, audio:false})
        document.getElementById('localVideo').srcObject = localStream
        document.getElementById('remoteVideo').srcObject = localStream
    }
}

let toggleCamera = async () => {
    // let videoTrack = localStream.getTracks().find(track => track.kind === 'video')

    // if(videoTrack.enabled){
    //     videoTrack.enabled = false
    // }else{
    //     videoTrack.enabled = true
    // }
    
    // 
    // console.log('Hay un chat seleccionado');
    // localStream = await navigator.mediaDevices.getUserMedia({video:false})
    // document.getElementById('user-1').srcObject = localStream
    // document.getElementById('user-2').srcObject = localStream
    $('#videocallModal').modal("hide");
    localStream.getTracks().forEach(function(track) {
        track.stop();
      });
}