CS440: War Game
=============
The goal of this part of the assignment is to implement an agent to play a simple "warfare" game. Adapted from www.cool-ai.com with help from Michael Sittig

Rules of the game
=============
The game board is a 6x6 grid representing a city.
Each square has a fixed point value between 1 and 99.
There are two players, "blue" and "green". Each player takes turns: blue moves first, then green, then blue, etc.
The object of the game is to be the player in the end with the largest total value of squares in their possession. That is, one wants to capture the squares worth the most points.
The game ends when all the squares are occupied by all players since no more moves are left.
Movement is always vertical and horizontal but never diagonal.
Pieces can be conquered in the vertical and horizontal direction, but never the diagonal direction.
The values of the squares can be changed for each game, but remain constant within a game.
In each turn, a player can make one of two moves:
1.Commando Para Drop: You can take any open space on the board with a Para Drop. This will create a new piece on the board. This move can be made as many times as one wants to during the game, but only once per turn. A Commando Para Drop cannot conquer any pieces. It simply allows one to arbitrarily place a piece on any unoccupied square on the board. Once you have done a Para Drop, your turn is complete.The image below illustrates a Commando Para Drop. In this case, green drops a new piece on square [C,3]. This square is worth 39, which is a higher number, meaning that it contains some juicy oil wells or other important resources. After that, the score is green 39 : blue 3. A Commando Para Drop could have been carried out on any squares except for [D,4] since blue already occupies it.
2.M1 Death Blitz: From any space you occupy on the board, you can take the one next to it (up, down, left, right, but not diagonally) if it is unoccupied. The space you originally held is still occupied. Thus, you get to create a new piece in the blitzed square. Any enemy touching the square you have taken is conquered and that square is turned to your side (you turn its piece to your side). An M1 Death Blitz can be done even if it will not conquer another piece. Once you have made this move, your turn is over.The image below illustrates an M1 Death Blitz. Green blitzes the piece in [D,4] to [D,3]. This conquers the blue piece in [D,2] since it is touching the new green piece in [D,3]. A blitz always creates a new piece and always moves one square, but it does not conquer another piece unless it is touching it. Thus, another valid move might have been for [D,4] to have blitzed [E,4]. Then the green player would own [D,4] and [E,4] but would have conquered none of blue's pieces. Note, the score before the blitz was green 46 : blue 157 but afterwards is green 113 : blue 149.

Our Contributions
=============
It is a php code. Algorithm part shows the MinMax and Alpha-Beta pruning algorithms.
App part is an interactive code online application which can both work on web and mobile well.

Team & Copyright
=============
COPYRIGHT CS440 Group @ University of Illinois at Urbana-Champaign
By Haoran Yu, Le Wang and Tao Feng