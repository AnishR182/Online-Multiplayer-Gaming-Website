(function init() {
  const P1 = 'X';
  const PS = '';
  const P2 = 'O';
  let player;
  let game;
 // let flagX= false;
  
  const socket = io.connect('http://localhost:3000');


function createlink(user, str){
    var link = "http://localhost/trophy.php?name="+user+"&game="+str;
    return link;
  }

 function getRoomid(){
    	var msg =$("#userHello").text();
    	var str = msg.split(":");
    	var gameid = str[1].slice(0,5);
    	return gameid;
    	//document.getElementbyID("gameID").innerHTML = gameID;
    }


 class Player {
    constructor(name, type) {
      this.name = name;
      this.type = type;
      this.currentTurn = true;
      this.playsArr = 0;
      this.flagX= false;
    }

    

    // Set the bit of the move played by the player
    // tileValue - Bitmask used to set the recently played move.
    updatePlaysArr(tileValue) {
      this.playsArr += tileValue;
    }

    getPlaysArr() {
      return this.playsArr;
    }

    // Set the currentTurn for player to turn and update UI to reflect the same.
    setCurrentTurn(turn) {
      this.currentTurn = turn;
      const message = turn ? 'Your turn' : 'Waiting for Opponent';
      $('#turn').text(message);
    }

    getPlayerName() {
      return this.name;
    }

    getPlayerType() {
      return this.type;
    }

    getCurrentTurn() {
      return this.currentTurn;
    }
  }

class Game {
    constructor(roomId) {
      this.roomId = roomId;
      this.board = [];
      this.moves = 0;
      this.movefrom = '';
      this.moveto ='';
      this.startflag=0;
      this.clickflag=false;
    }

    // Create the Game board by attaching event listeners to the buttons.
    createGameBoard() {
      function tileClickHandler() {
        const row = parseInt(this.id.split('_')[1][0], 10);
        const col = parseInt(this.id.split('_')[1][1], 10);
        //if(!(player.getPlayerType() === PS)){
        if (!player.getCurrentTurn() || !game) {
          alert('Its not your turn!');
          return;
        }

        if ($(this).prop('disabled')) {
          alert('This tile has already been played on!');
          return;
        }
        
        

      ////}else {
        
        //alert("Spectators can only view!");
      //}
        // Update board after your turn.
        game.playTurn(this);
        //game.updateBoard(player.getPlayerType(), row, col, this.id);
        

        
         if(!(player.getPlayerType() === PS)){
          if(!(game.clickflag))
          player.setCurrentTurn(false);
        //player.updatePlaysArr(1 << ((row * 5) + col));

     }

        //game.checkWinner();
      }

      for (let i = 0; i < 4; i++) {
        this.board.push(['', '', '']);
        for (let j = 0; j < 5; j++) {
          $(`#button_${i}${j}`).on('click', tileClickHandler);
        }
      }

    }
    // Remove the menu from DOM, display the gameboard and greet the player.
    displayBoard(message) {
      //$('.menu').css('display', 'none');
      
      if(player.getPlayerType() == P1){
        $('.top').css('background-color','#4CAF50');
        $('.bottom').css('background-color','#FF0000');
      } else{
        $('.bottom').css('background-color','#4CAF50');
        $('.top').css('background-color','#FF0000');

      }
      $('.setboard').css('display','block');
      //$('.gameBoard').css('display', 'block');
      $('#userHello').html(message);
      //this.createGameBoard();
    }

    startGame(){
    	
    	if(this.startflag>1){
      $('.setboard').css('display','none');
      
      if(player.getPlayerType() == P1){
        $('.opponent .tile').css('background-color','#4CAF50');
        //$('.opponent .tile').css('color','#4CAF50');
        $('.self .tile').css('background-color','#FF0000');
      } else{
        $('.self .tile').css('background-color','#4CAF50');
        //$('.opponent .tile').css('background-color','#FF0000');
        $('.opponent .tile').css('color','#FF0000');

      }
      $('.gameBoard').css('display', 'block');


        this.displayPieces();
    		this.createGameBoard();}
      else{
        $('#turn').text('Waiting for player to start game.');
      }
    }


    setSelfBoard(board){
    	var k=0;
    	for(let i =2; i<4;i++){
        this.board[i] = [];
    		for(let j= 0; j<5; j++){
          
    			this.board[i][j] = board[k];
          
    			k++;
    		}
    	}
    	this.startflag++;
    }

    setOppBoard(board){
    	var k=9;
    	for(let i =0; i<2;i++){
        this.board[i] = [];
    		for(let j= 0; j<5; j++){
    			this.board[i][j] = board[k];
    			k--;
    		}
    	}
    	this.startflag++;
    }

    displayPieces(){
      //var k =0;
      for (let i = 0; i < 4; i++) {
        //this.board.push(['', '', '']);
        for (let j = 0; j < 5; j++) {
          $(`#button_${i}${j}`).text(this.board[i][j]).prop('value',this.board[i][j]);
          //k++;
        }
      }
    }
  
    updateBoard(type, row, col, tile) {
      //$(`#${tile}`).text(type).prop('disabled', true);
      $(`#${tile}`).text(type).prop('value', type);
      this.board[row][col] = type;
      this.moves++;
    }

    getRoomId() {
      return this.roomId;
    }

    // Send an update to the opponent to update their UI's tile
    playTurn(tile) {
      const clickedTile = $(tile).attr('id');
      const p = $(tile).attr('value');

      if(!this.clickflag){
          this.movefrom= tile;
          this.clickflag = true;
        return;
      }
      else if(this.clickflag && this.isvalidMove(tile)){
      // Emit an event to update other player that you've played your turn.
        this.moveto = tile;

        var movefromid = $(this.movefrom).attr('id');
        var movefromtype= $(this.movefrom).attr('value');
        var movetoid = $(this.moveto).attr('id');
        var movetotype = $(this.moveto).attr('value');

        socket.emit('playTurn', {
          movefromid : movefromid,
          movefromtype: movefromtype,
          movetoid: movetoid,
          movetotype: movetotype,
          room: this.getRoomId()
        });

        this.movePiece(movefromid, movefromtype, movetoid, movetotype);
        
        this.clickflag = false;
      }

      
    }

    isvalidMove(tile){
      var from = parseInt($(this.movefrom).attr('id').split('_')[1]);
      var to = parseInt($(tile).attr('id').split('_')[1]);

      if(from>to)
        var step = from-to;
      else
        var step = to- from;

      if (step == 1 || step == 10){
        return true;
      }
      else 
        return false;
        

    }

    movePiece(movefromid, movefromtype, movetoid, movetotype){

      var m = this.moveType(movefromtype, movetotype);

      var trow= movetoid.split('_')[1][0];
      var tcol= movetoid.split('_')[1][1];
      var frow= movefromid.split('_')[1][0];
      var fcol= movefromid.split('_')[1][1];

      switch (m){
        case 0: break;
        case 1 : this.board[frow][fcol]='';
                 $(`#${movefromid}`).text('').prop('value', '');
                 this.board[trow][tcol] = movefromtype;
                 $(`#${movetoid}`).text(movefromtype).prop('value', movefromtype);
                 break;
        case 2 : this.board[frow][fcol] = ''; break;
                 $(`#${movefromid}`).text('').prop('value', '');
        case 3 : this.announceWinner(); break;
      }
    }

    moveType(movefromtype,movetotype){

        //var w = this.checkWinningPiece(movefromtype, movetotype);
        
        
        switch(movefromtype){
          case 'R' : {
            switch(movetotype){
              case 'R' : return 0; break;
              case 'S' : return 1; break;
              case 'P' : return 2; break;
              case 'T' : return 3; break;
              default: return 1; break;
            }; 
            break;
          }
          case 'S' : {
            switch(movetotype){
              case 'R' : return 2; break;
              case 'S' : return 0; break;
              case 'P' : return 1; break;
              case 'T' : return 3; break;
              default: return 1; break;
            }; 
            break;
          }
          case 'P' : {
            switch(movetotype){
              case 'R' : return 1; break;
              case 'S' : return 2; break;
              case 'P' : return 0; break;
              case 'T' : return 3; break;
              default: return 1; break;
            }; 
            break;
          }
          case 'T' :{
            return 2; break;
          }
          default: return 2; 
        }




    }
    
    checkWinner() {
      const currentPlayerPositions = player.getPlaysArr();

      Player.wins.forEach((winningPosition) => {
        if ((winningPosition & currentPlayerPositions) === winningPosition) {
          game.announceWinner();
        }
      });

      const tieMessage = 'Game Tied :(';
      if (this.checkTie()) {
        socket.emit('gameEnded', {
          room: this.getRoomId(),
          message: tieMessage,
        });
        alert(tieMessage);
        location.reload();
      }
    }

    
    // Announce the winner if the current client has won. 
    // Broadcast this on the room to let the opponent know.
    announceWinner() {
      const message = `${player.getPlayerName()} wins!`;
      socket.emit('gameEnded', {
        room: this.getRoomId(),
        message,
      });
      alert(message);
      location.reload();
    }

    // End the game if the other player won.
    endGame(message) {
      alert(message);
      location.reload();
    }

    getGame(room){
      if (this.roomID==room){
          return this;
      }
    }

    setX(bool){
      this.flagX=true;
    }

    refreshGame(board){

      for (let i = 0; i < 4; i++) {
        //this.board.push(['', '', '']);
        for (let j = 0; j < 5; j++) {
          this.board.push(board[i][j]);
        }
      }
    }
  }









  $('#new').on('click', () => {
    const name = $('#nameNew').val();
    if (!name) {
      alert('Please enter your name.');
      return;
    }
    const xo =$('input[name=XO]:radio:checked').val();
    if(!xo){
        alert('Please choose your color.');
      return;
    }
    socket.emit('createGame', { name });
    if(xo =='X')
      player = new Player(name, P1);

    else if (xo == "O")
      player = new Player(name, P2);

  });

  $('#start').on('click', () =>{
	var board=[];
  var room = getUrlVars()["game"];
    if(!room || room == "")
    	 room = getRoomid();
	var type = player.getPlayerType();
  	board.push( $('#20 option:selected' ).text());
  	board.push( $("#21 option:selected" ).text());
  	board.push( $("#22 option:selected" ).text());
  	board.push( $("#23 option:selected" ).text());
  	board.push( $("#24 option:selected" ).text());
  	board.push( $("#30 option:selected" ).text());
  	board.push( $("#31 option:selected" ).text());
  	board.push( $("#32 option:selected" ).text());
  	board.push( $("#33 option:selected" ).text());
  	board.push( $("#34 option:selected" ).text());

    
  	socket.emit('boardState',{board: board, type: type, room: room});
    game.setSelfBoard(board);
    game.startGame();
  });


  

 $('#join').on('click', () => {
    const name = $('#nameJoin').val();
    //const roomID = $('#room').val();
    const roomID = getUrlVars()["game"];
    const ptype = getUrlVars()["p"]
    if (!name) {
      alert('Please enter your name.');
      return;
    }
    socket.emit('joinGame', { name, room: roomID });
    //var g = new Game(roomID);

    if(ptype=='o'){
      player = new Player(name, P2);
      }
      else if(ptype == 'x')
        player= new Player(name, P1);
      else
        player = new Player (name, PS);
    
  });

  socket.on('newGame', (data) => {
    const message =
      `Hello, ${data.name}. Please ask your friend to join:${data.room}. Waiting for player ...`;
  const link = `http://localhost/ticnodek/trophy.php?name=guest&game=${data.room}`;
      $('#gameid').html(link);

    // Create game for player 1
	//Redirect_to_game(data.name, data.room);
    game = new Game(data.room);
    game.displayBoard(message);

  });


socket.on('startGame', (data) => {
   var p = player.getPlayerType();
   //if(p === data.type){}
   	//game.setSelfBoard(data.board);
   if(!(p===data.type))
    game.setOppBoard(data.board);

	 game.startGame();
  });

socket.on('player1', (data) => {
    const message = `Hello, ${player.getPlayerName()}. Game:${data.room}.`;
    $('#userHello').html(message);
    var p = player.getPlayerType();
    if(p=="P2")
    player.setCurrentTurn(false);
    else 
      player.setCurrentTurn(true);
     // game = Game.getGame(data.room);
      //game.setX(true);
    //}
  });


  /**
	 * Joined the game, so player is P2(O). 
	 * This event is received when P2 successfully joins the game room. 
	 */
  socket.on('player2', (data) => {
    const message = `Hello, ${data.name}`;
    const link = `http://localhost/trophy.php?name=guest&game=${data.room}`;
      $('#gameid').html(link);


    // Create game for player 2
    game = new Game(data.room);
    game.displayBoard(message);

    var p = player.getPlayerType();
    if(p=="P1")
    player.setCurrentTurn(true);
    else 
      player.setCurrentTurn(false);
  });


  socket.on('spectator', (data) => {
   // const message = `Hello, ${data.name}`;
    

    game = new Game(data.room);
     // $('.gameBoard').css('display', 'block');
      //$('#userHello').html(message);
     
     game.displayBoard(message);


     //window.setInterval(function(){ 
      //game.refreshGame();
         
      //}, 5000);

  });
  /**
	 * Opponent played his turn. Update UI.
	 * Allow the current player to play now. 
	 */
  socket.on('turnPlayed', (data) => {


   // const row = data.tile.split('_')[1][0];
    //const col = data.tile.split('_')[1][1];
    var frow = 3 - data.movefromid.split('_')[1][0];
    var fcol = 4 - data.movefromid.split('_')[1][1];
    var trow = 3 - data.movetoid.split('_')[1][0];
    var tcol = 4 - data.movetoid.split('_')[1][1];

    //var t_r= 
    //var fid = (((3-fcol) * 10) + (4 - frow));

    var movetoid = `button_${trow}${tcol}`; 
    var movefromid =  `button_${frow}${fcol}`; 

    var p = player.getPlayerType();

    var opponentType = p === P1 ? P2 : P1;

   // if(p== PS){
     // opponentType = data.type;
      //game.refreshGame(game.board);
      
    //}
    game.movePiece(movefromid, data.movefromtype, movetoid, data.movetotype);
   // game.updateBoard(opponentType, row, col, data.tile);
    player.setCurrentTurn(true);
  });

  // If the other player wins, this event is received. Notify user game has ended.
  socket.on('gameEnd', (data) => {
    game.endGame(data.message);

    socket.leave(data.room);
  });

  /**
	 * End the game on any err event. 
	 */
  socket.on('err', (data) => {
    var game = new Game(data.room);
    game.endGame(data.message);
  });
}());

