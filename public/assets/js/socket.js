const socket = io("https://euavisei.adaptable.app/", { transports : ['websocket'] }); 

socket.on('apontar-ocorrencia', async (msg) =>{
    console.log(msg);
});