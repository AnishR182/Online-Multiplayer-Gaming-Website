const express = require('express');
const path = require('path');

const app = express();
const server = require('http').Server(app);
const io = require('socket.io')(server);

let rooms = 0;

app.use(express.static('.'));

app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'game.html'));
});

io.on('connection', (socket) => {

    // Create a new game room and notify the creator of game.
    socket.on('createGame', (data) => {
         rooms = Math.floor(Math.random() * 90000) + 10000;
        socket.join(`${++rooms}`);
        socket.emit('newGame', { name: data.name, room: `${rooms}` });
    });

    // Connect the Player 2 to the room he requested. Show error if room full.
    socket.on('joinGame', function (data) {
        var room = io.nsps['/'].adapter.rooms[data.room];
        if (room
            ) {
            socket.join(data.room);

            if(room.length === 2 ){
            socket.broadcast.to(data.room).emit('player1', {room: data.room});
            socket.emit('player2', { name: data.name, room: data.room });
             }
             else {
           //socket.emit('err', { message: 'Sorry, The room is full!', room: data.room});
             socket.emit('spectator', {name: data.name, room: data.room });
            }
           
        } 
    });

    /**
       * Handle the turn played by either player and notify the other.
       */
    socket.on('playTurn', (data) => {
        socket.to(data.room).emit('turnPlayed', {
            type: data.type,
            tile: data.tile,
            room: data.room
        });
    });

    /**
       * Notify the players about the victor.    
       */
    socket.on('gameEnded', (data) => {
        socket.broadcast.to(data.room).emit('gameEnd', data);
    });
});

server.listen(process.env.PORT || 5000);