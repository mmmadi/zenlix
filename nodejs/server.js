/*
    npm install dotenv --save -g
    npm install pm2 -g
    pm2 start ./nodejs/server.js -n zenserver --watch

    sudo npm install express socket.io ioredis dotenv

*/
var base = __dirname;
//console.log(__dirname);
//require('dotenv').load();
require('dotenv').config({
    path: base + '/config.env'
});
//console.log(process.env.NODE_PORT);
var PORT = process.env.NODE_PORT;
var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
//var io = require('socket.io').listen(PORT);
var Redis = require('ioredis');
var redis = new Redis();
redis.subscribe('ZEN-channel', function(err, count) {});
redis.on('message', function(channel, message) {
    //console.log('Channel: ' + channel + ' | Message Recieved: ' + message);
    message = JSON.parse(message);
    if (message.msgType == 'webPush') {
        io.to(message.login).emit('webPush', {
            message: message.message,
            title: message.title,
            url: message.url
        });
    } else if (message.msgType == 'chatPush') {
        io.to(message.login).emit('chatPush', {
            message: message.message,
            fromid: message.fromid,
            fromName: message.fromName,
            from: message.from,
            total: message.total
        });
    } else if (message.msgType == 'chatReq') {
        io.emit('chatReq', {
            val: 'updateReqMenu'
        });
    } else if (message.msgType == 'chatReqAccept') {
        io.to(message.login).emit('chatReqAccept', {
            fromid: message.fromid,
            fromName: message.fromName,
            from: message.from
        });
    } else if (message.msgType == 'chatClose') {
        io.to(message.login).emit('chatClose', {
            val: 'close'
        });
    }
    //NotifyMenuMsg
    else if (message.msgType == 'NotifyMenuMsg') {
        io.to(message.login).emit('NotifyMenuMsg', {
            val: 'fire'
        });
    }
    //chatClose
});
http.listen(PORT, function() {
    //console.log('Listening on Port ' + PORT);
    io.on('connection', function(socket) {
        socket.on('join', function(data) {
            // We are using room of socket io
            socket.join(data.uniq_id);
        });
    });
});