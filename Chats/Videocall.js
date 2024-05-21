
const localVideo = document.getElementById('localVideo');
const remoteVideo = document.getElementById('remoteVideo');
const socket = io('https://mesquite-malachite-pirate.glitch.me');
let localStream;
let peerConnection;
const configuration = {
iceServers: [{ urls: 'stun:stun.l.google.com:19302' }]
};
let finishVideocall = async () => {
    $('#videocallModal').modal("hide");
    localStream.getTracks().forEach(function(track) {
        track.stop();
    });
}
async function startCall() {
    localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
    localVideo.srcObject = localStream;
    peerConnection = new RTCPeerConnection(configuration);
    localStream.getTracks().forEach(track => peerConnection.addTrack(track, localStream));
    peerConnection.ontrack = event => {
        remoteVideo.srcObject = event.streams[0];
    };
    peerConnection.onicecandidate = event => {
        if (event.candidate) {
        socket.emit('candidate', event.candidate);
        }
    };
    const offer = await peerConnection.createOffer();
    await peerConnection.setLocalDescription(offer);
    socket.emit('offer', offer);
}
socket.on('offer', async (description) => {
    if (!peerConnection) startCall();
    await peerConnection.setRemoteDescription(new RTCSessionDescription(description));
    const answer = await peerConnection.createAnswer();
    await peerConnection.setLocalDescription(answer);
    socket.emit('answer', answer);
});
socket.on('answer', async (description) => {
    await peerConnection.setRemoteDescription(new RTCSessionDescription(description));
});
socket.on('candidate', async (candidate) => {
    try {
        await peerConnection.addIceCandidate(new RTCIceCandidate(candidate));
    } catch (e) {
        console.error('Error adding received ice candidate', e);
    }
});
let startVideocall = async () => {
    if(document.getElementById('chat_selected').textContent != 'Bienvenido'){
        $('#videocallModal').modal("show");
        startCall();
    }
}