<!doctype html>
<html lang="en">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Bootstrap core CSS -->
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<!-- Custom fonts for this template -->
<link href="https://fonts.googleapis.com/css?family=Saira+Extra+Condensed:100,200,300,400,500,600,700,800,900" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
<link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="vendor/devicons/css/devicons.min.css" rel="stylesheet">
<link href="vendor/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="css/resume.min.css" rel="stylesheet">
  
       <link rel="stylesheet" href="css/trophy.css">
        <link rel="stylesheet" href="node_modules/skeleton-css/css/skeleton.css">



<title>GameZone!</title>
<script>
  function loadpage(){
      var str= getUrlVars()["name"];

      if (!str || str ==""){
        $('.newroom').css('display', 'block');
      }
      else if(str=="guest")
        $('.joinroom').css('display','block');

  }

   function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;

}
  </script>
</head>
<body id="page-top" onload="loadpage();">

<script>

 /*  var socket = io.connect('http://localhost:1501');;

      function getElement(name) {
         var $elems = $(name)
         if ($elems.length)
            return $elems[0];
         var $elem = $('<' + name + '>');
         $(body).append($elem)
         return $elem;
      }

      function getSpace(x, y) {
         var boardElem = getElement('board');
         var rowElem = boardElem.getElementsByTagName('row')[y];
         var cellElem = rowElem.getElementsByTagName('space')[x];
         return cellElem;
      }

      function getCoords(spaceElem) {
         var $board = $(getElement('board'));
         var $space = $board.find(spaceElem);
         if ($space.length) {
            var $row = $space.parent();
            var rowIndex = $board.children().index($row);
            var colIndex = $row.children().index($space);
            return { x: colIndex, y: rowIndex };
         }
      }

      function move(src, dst) {
         var cmd = 'MOVE ' + src.x + ' ' + src.y + ' ' + dst.x + ' ' + dst.y;
         socket.emit('commands', cmd);
      }

      function allowDrop(dragEvent) {
         dragEvent = dragEvent.originalEvent;
         if ($(this).hasClass('usable') && this.childNodes.length == 0) {
            dragEvent.preventDefault();
         }
      }

      var touchSrc;
      var origPiece;
      var dragPiece;

      function touchDrag(touchEvent) {
         touchEvent = touchEvent.originalEvent;
         touchEvent.preventDefault(); // Prevent scrolling
         var srcSpace = this.parentNode;
         if (touchEvent.changedTouches.length == 1
             && $(this).hasClass(player) && player == turn) {
            var touch = touchEvent.changedTouches[0];
            touchSrc = getCoords(srcSpace);
            dragPiece = this.cloneNode(true);
            origPiece = this;
            dragPiece.style.top = (touch.clientY - (this.offsetHeight / 2)) + 'px';
            dragPiece.style.left = (touch.clientX - (this.offsetWidth / 2)) + 'px';
            $(dragPiece).addClass('dragging');
            getElement('game').appendChild(dragPiece);
            $(this).addClass('hidden');
         } else 
            resetDrag();
         
      }

      function resetDrag() {
         touchSrc = origPiece = undefined;
      }

      function touchMove(touchEvent) {
         touchEvent = touchEvent.originalEvent;
         if (typeof dragPiece === 'object'  && touchEvent.targetTouches.length == 1) {
            var touch = touchEvent.targetTouches[0];
            dragPiece.style.top = (touch.clientY - (dragPiece.offsetHeight / 2)) + 'px';
            dragPiece.style.left = (touch.clientX - (dragPiece.offsetWidth / 2)) + 'px';
         }
      }

      function touchDrop(touchEvent) {
         touchEvent = touchEvent.originalEvent;
         touchEvent.preventDefault();
         if (dragPiece && touchSrc && touchEvent.changedTouches.length >= 1) {
            var touch = touchEvent.changedTouches[0];
            getElement('game').removeChild(dragPiece);
            dragPiece = undefined;
            var dropElem = document.elementFromPoint(touch.clientX, touch.clientY);
            if ($(dropElem).hasClass('usable') && dropElem.childNodes.length == 0) {
               var touchDst = getCoords(dropElem);
               move(touchSrc, touchDst);
            } else {
               unhidePieceAfterDrop();
            }
         }
      }

      function unhidePieceAfterDrop() {
         if (origPiece) {
            $(origPiece).removeClass('hidden');
            resetDrag();
         }
      }

      function dragPiece(dragEvent) {
         dragEvent = dragEvent.originalEvent;
         var dragSrc = getCoords(this.parentNode);
         dragEvent.dataTransfer.setData('text/plain', JSON.stringify(dragSrc));
      }

      function dropPiece(dropEvent) {
         dropEvent = dropEvent.originalEvent;
         dropEvent.preventDefault();
         var dropDst = getCoords(dropEvent.target);
         var dragSrc = JSON.parse(dropEvent.dataTransfer.getData('text/plain'));
         move(dragSrc, dropDst);
      }

      socket.on('BOARD', function(data) {

         var rows = data.split('|');
         var $board = $(getElement('board'));
         $board.html(''); // Clear existing board contents

         for (var i=0; i<rows.length; i++) {

            var row = rows[i];
            var pieces = row.split('');
            var $row = $('<row>');
            $board.append($row);

            for (var j=0; j<pieces.length; j++) {

               var piece = pieces[j];
               var $space = $('<space>');
               $row.append($space);

               if ((j + (i % 2)) % 2 != 0) {
                  $space.addClass('usable');
                  $space.on('dragover', allowDrop);
                  $space.on('drop', dropPiece);
               }

               var $piece = $('<piece>');

               $piece.on('dragstart', dragPiece);
               $piece.on('touchstart', touchDrag);
               $piece.on('touchmove', touchMove);
               $piece.on('touchend', touchDrop);

               if (piece.toLowerCase() == 'r') {
                  $piece.addClass('red');
               } else if (piece.toLowerCase() == 'b') {
                  $piece.addClass('black');
               } else {
                  continue;
               }
               $space.append($piece);
               if (piece >= 'A' && piece <= 'Z')
                  $piece.addClass('king');
            }
         }
         $(getElement('game')).css('visibility', 'visible');
         resizeGame();
      });

      function resizeGame() {
         var $window = $(window);
         var tableDim = Math.min($window.width(), $window.height());
         tableDim -= tableDim % 8 + (5*2);
         var cellDim = (tableDim - (8*2)) / 8;
         var pieceDim = (cellDim * .8);
         pieceDim -= (pieceDim % 2);
         var $board = $(getElement('board'));
         $board.css('width', tableDim);
         $board.css('height', tableDim);
         $board.find('space').each(function() {
            $(this).css('width', cellDim);
            $(this).css('height', cellDim);
         });
         $board.find('piece').each(function() {
            $(this).css('width', pieceDim);
            $(this).css('height', pieceDim);
            $(this).css('border-radius', pieceDim / 2);
            $(this).css('background-size', pieceDim * 0.75);
         });
         var $gameInfo = $(getElement('game-info'));
         if ($window.width() > $window.height()) {
            $gameInfo.css('left', $board.outerWidth() + 5);
            $gameInfo.css('top', 5);
         } else {
            $gameInfo.css('left', 5);
            $gameInfo.css('top', $board.outerHeight() + 5);
         }
      }
      $(window).resize(resizeGame);

      function enablePieces(turn) {
         var $board = $(getElement('board'));
         $board.find('piece').each(function() {
            var isPieceTurn = turn == player && $(this).hasClass(player);
            this.draggable = isPieceTurn;
            if (isPieceTurn) {
               $(this).addClass('is_turn');
            } else {
               $(this).removeClass('is_turn');
            }
         });
      }

      socket.on('MOVED', function(data) {
         var coords = data.split(' ');
         var srcx = coords[0], srcy = coords[1], dstx = coords[2], dsty = coords[3];
         var srcCellElem = getSpace(srcx, srcy), dstCellElem = getSpace(dstx, dsty);
         var pieceElem = srcCellElem.childNodes[0];
         srcCellElem.removeChild(pieceElem);
         dstCellElem.appendChild(pieceElem);
      });

      socket.on('CAPTURED', function(data) {
         var coords = data.split(' ');
         var x = coords[0], y = coords[1];
         var cellElem = getSpace(x, y);
         cellElem.removeChild(cellElem.childNodes[0]);
      });

      socket.on('KING', function(data) {
         var coords = data.split(' ');
         var x = coords[0], y = coords[1];
         var pieceElem = getSpace(x, y).childNodes[0];
         $(pieceElem).addClass('king');
      });

      var turn;
      socket.on('TURN', function(data) {
         var msg;
         turn = data;
         if (data == 'waiting') {
            msg = 'Waiting for players';
         } else if (player && player == turn) {
            msg = 'Your turn';
         }else {
            msg = data.charAt(0).toUpperCase() + data.slice(1) + "'s turn";
         }
         getElement('turn').innerHTML = msg;
         enablePieces(data);
      });

      socket.on('WINNER', function(data) {
         getElement('winner').innerHTML = 'Winner: ' + data;
      });

      socket.on('GAME_ID', function(data) {
         getElement('game-id').innerHTML = 'Game ' + data;
      });

      var player;
      socket.on('YOU_ARE', function(data) {
         player = data;
         getElement('player').innerHTML = 'Your pieces are ' + data;
      });

      function join(gameId) {
         socket.emit('commands', 'JOIN ' + gameId);
      }

      function newGame() {
         socket.emit('commands', 'NEW');
      }

      socket.on('LIST', function(data) {
         if (data) {
            var gameList = data.split(' ');
            join(gameList[0]);
         } else {
            newGame();
         }
      });

      socket.on('commands', function(data) {
         unhidePieceAfterDrop();
      });

      function gameList() {
         console.log('sending game list command');
         socket.emit('commands', 'LIST');
      }

      $(document).ready(gameList);*/
  </script>



  <?php include 'navbar.php';?>

<div class="container-fluid">

  <div class="row">
    <div class="container-fluid">

      
        <section class="resume-section p-3 p-lg-5 d-flex flex-column" id="tictactoe">
        <h1>Trophy</h1>
        <div class="newroom">
        <h4>Create a new Game</h4>
        <input type="text" name="name" id="nameNew" placeholder="Enter your name" required>
        <div >
        <h5>Choose</h5>
        <input type="radio" name="XO" id ="chooseX" value="X"> <label for="chooseX"><h5>Red</h5></label>
        <input type="radio" name="XO" id ="chooseO" value="O"> <label for="chooseO"><h5>Green</h5></label>
      </div>
        <button id="new">New Game</button>
      </div>
        <br><br>
        <div class="joinroom">
        <h4>Join an existing game</h4>
        <input type="text" name="name" id="nameJoin" placeholder="Enter your name" required>
        <!--<input type="text" name="room" id="room" placeholder="Enter Game ID" required>-->
        <button id="join">Join Game</button>
      </div>
        
        <h2 id="userHello"></h2>
  
  
      <div class="setboard"> 
     <table style="height: 70%; width: 70%; align-content: center;" >
  <tr class="top">
    <th> 
  <div class ="menudropdown">
  <select id="01">
<option>R</option>
<option>S</option>
<option>P</option>
<option>T</option>
  </select>
    </div>
  </th>
    <th><div class ="menudropdown">
  <select id="02">
<option>R</option>
<option>S</option>
<option>P</option>
<option>T</option>
  </select>
    </div></th>
    <th><div class ="menudropdown">
  <select>
<option>R</option>
<option>S</option>
<option>P</option>
<option>T</option>
  </select>
    </div></th>
  <th><div class ="menudropdown">
  <select>
<option>R</option>
<option>S</option>
<option>P</option>
<option>T</option>
  </select>
    </div></th>
  <th><div class ="menudropdown">
  <select>
<option>R</option>
<option>S</option>
<option>P</option>
<option>T</option>
  </select>
    </div></th>
  </tr>
  <tr class="top">
  <th><div class ="menudropdown">
  <select>
<option>R</option>
<option>S</option>
<option>P</option>
<option>T</option>
  </select>
    </div></th>
    <th><div class ="menudropdown">
  <select>
<option>R</option>
<option>S</option>
<option>P</option>
<option>T</option>
  </select>
    </div></th>
    <th><div class ="menudropdown">
  <select>
<option>R</option>
<option>S</option>
<option>P</option>
<option>T</option>
  </select>
    </div></th>
  <th><div class ="menudropdown">
  <select>
<option>R</option>
<option>S</option>
<option>P</option>
<option>T</option>
  </select>
    </div></th>
  <th><div class ="menudropdown">
  <select>
<option>R</option>
<option>S</option>
<option>P</option>
<option>T</option>
  </select>
    </div></th>
    
  </tr>
  <tr class="bottom">
  <th><div class ="menudropdown">
  <select id="20">
<option>R</option>
<option>S</option>
<option>P</option>
<option>T</option>
  </select>
    </div></th>
    <th><div class ="menudropdown">
  <select id="21">
<option>R</option>
<option>S</option>
<option>P</option>
<option>T</option>
  </select>
    </div></th>
    <th><div class ="menudropdown">
  <select id="22">
<option>R</option>
<option>S</option>
<option>P</option>
<option>T</option>
  </select>
    </div></th>
  <th><div class ="menudropdown">
  <select id="23">
<option>R</option>
<option>S</option>
<option>P</option>
<option>T</option>
  </select>
    </div></th>
  <th><div class ="menudropdown">
  <select id="24">
<option>R</option>
<option>S</option>
<option>P</option>
<option>T</option>
  </select>
    </div></th>
    
  </tr>
  
  
  <tr class="bottom">
  <th><div class ="menudropdown">
  <select id="30">
<option>R</option>
<option>S</option>
<option>P</option>
<option>T</option>
  </select>
    </div></th>
    <th><div class ="menudropdown">
  <select id="31">
<option>R</option>
<option>S</option>
<option>P</option>
<option>T</option>
  </select>
    </div></th>
    <th>
  <div class ="menudropdown">
  <select id="32">
<option>R</option>
<option>S</option>
<option>P</option>
<option>T</option>
  </select>
    </div></th>
  <th><div class ="menudropdown">
  <select id="33">
<option>R</option>
<option>S</option>
<option>P</option>
<option>T</option>
  </select>
    </div></th>
  <th><div class ="menudropdown">
  <select id="34">
<option>R</option>
<option>S</option>
<option>P</option>
<option>T</option>
  </select>
    </div></th>
    
  </tr>
  
  
</table>

<button class="startgame" type="submit" id="start"> Start Game</button>
 </div>


<div class ="gameBoard">

        <h3 id="turn"></h3>
        <table class="center">
          <tr class="opponent">
            <td><button class="tile" id="button_00"></button></td>
            <td><button class="tile" id="button_01"></button></td>
            <td><button class="tile" id="button_02"></button></td>
            <td><button class="tile" id="button_03"></button></td>
            <td><button class="tile" id="button_04"></button></td>
          </tr>
          <tr class="opponent">
            <td><button class="tile" id="button_10"></button></td>
            <td><button class="tile" id="button_11"></button></td>
            <td><button class="tile" id="button_12"></button></td>
            <td><button class="tile" id="button_13"></button></td>
            <td><button class="tile" id="button_14"></button></td>

          </tr>
          <tr class="self">
            <td><button class="tile" id="button_20"></button></td>
            <td><button class="tile" id="button_21"></button></td>
            <td><button class="tile" id="button_22"></button></td>
            <td><button class="tile" id="button_23"></button></td>
            <td><button class="tile" id="button_24"></button></td>
          </tr>
          <tr class="self">
            <td><button class="tile" id="button_30"></button></td>
            <td><button class="tile" id="button_31"></button></td>
            <td><button class="tile" id="button_32"></button></td>
            <td><button class="tile" id="button_33"></button></td>
            <td><button class="tile" id="button_34"></button></td>
          </tr>
        </table>
</div>

      <game-info>
         <game-id></game-id>
         <player></player>
         <turn></turn>
         <winner></winner>
      </game-info>
    </game>

      </div>
    

      </section>

      

      </div>
    </div>  
  </div>  

  <div class="container-fluid">
  </br>
  
</br>

  <div class = "invite" style="padding-left: 25%">
    <form class="form-inline mt-2 mt-md-0" method="post" action="sendmail.php" id="inviteform" target="votar">
      <input class="form-control mr-sm-2" name="email" id="emailID" type="text" placeholder="Email-Id" aria-label="Email-Id">
      <textarea type="text" name="gameid" id="gameid" hidden></textarea>
      <h6 id="name"> </h6>
      <button id="invite" class="btn btn-outline-success my-2 my-sm-0" type="submit">Invite</button>
    </form>

    <iframe name="votar" style="display:none;"></iframe>
  </div>
  

<div class="row">
  <img class="img-fluid" src="images/imgadd.jpg">
</div>
</div>
<footer class="footer">
<?php include 'footer.php';?>

</footer>



<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Plugin JavaScript -->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for this template -->
<script src="js/resume.min.js"></script>

<script src="node_modules/jquery/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.4/socket.io.js"></script>
    
    <script src="trophygame.js"></script>
   
   </body>
</html>

