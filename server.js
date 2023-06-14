// ------------------------- Socket ---------------------------------

// const express = require('express');

// const app = express();

// const server = require('http').createServer(app);

// const io = require('socket.io')(server, {
//     cors: { origin: "*" }
// });

// io.on('connection', (socket) => {
//     console.log('connection');

//     socket.on('disconnect', (socket) => {
//         console.log('disconnect');
//     });
// });

// server.listen(3000, () => {
//     console.log('server is running');
// });






// let http = require("http").createServer(app);
// let io = require("socket.io")(http,
//     {
//         cors: {
            // origin: '*',//["http://3.137.153.221:3006","http://143.244.210.208"],//["http://3.137.153.221:3006", "http://3.137.153.221:3000","http://localhost:3006","http://localhost:3000", "http://143.244.210.208:3006","http://143.244.210.208:3006"],
//             methods: ["GET", "POST"],
//             allowedHeaders: ["Access-Control-Allow-Origin", "*", "Access-Control-Allow-Headers",
//             "Origin, X-Requested-With, Content-Type, Accept, Authorization"
//         ],
//         credentials: true
//     }
// });

// app.use(express.static('public'));
// app.get('/chat', (req, res) => {
//     res.redirect('index.html')
// });

// io.on("connection", socket => {

//     console.log("user connected", socket.id);
//     socket.on("disconnect", function () {
//         console.log("user disconnected", socket.id);
//     });

//     socket.on("message", async message => {
//     let msg=await chat.chatMessage(message);
//     io.emit("message", { "message": msg });
//     });
// });




// http.listen(3006, () => console.log("server running on port:" + 3006));
// ------------------------- Socket ---------------------------------   