var coordX = 0;     // evaluates X-coordinates
var coordY = 0;     // evaluates Y-coordinates
var xmax = 0;       // evaluates maximum value for X-coordinates
var ymax = 0;       // evaluates maximum value for Y-coordinates
var offsetX = 10;   // moves 10px with each move
var offsetY = 10;   // moves 10px with each move
var strikes = 0;    // counter to maintain how many times ball hits paddle
var maxScore = 0;   // counter to maintain score for number of times ball hits paddle
var start = false;  // counter for start of game
var end = false;    // counter for end of game
var i = 0;          // counter to maintain speed
var ballSpeed = -1;  // counter to maintain speed of ball
var boundary = new Object();
var paddleBoundary = new Object();
var ballRadius = new Object();

setInterval(function() { i = i + 1; update(i);}, 60 );

// function to start the game
function startGame(e)
{
    document.getElementById("messages").innerHTML = " The game starts now ";
    end = false;
    start = true;
}

// function to initialize counters and set initial values for ball and boundary conditions
function initialize()
{
    coordX = 0;
    i = 0;
    strikes = 0;
    start = false;
    if(ballSpeed == -1)
        ballSpeed = 0;

   coordY =  Math.floor((Math.random() * 400) + 1);
   document.getElementById("score").innerHTML = maxScore;
    // intializing position of ball on the left side of boundary
    document.getElementById("ball").style.top = coordY + "px";
    document.getElementById("ball").style.left = '0px';

    // initializing boundary conditions
    var boundary = document.getElementById("court").getBoundingClientRect();
    xmax = boundary.width - 10;
    ymax = boundary.height - 95;
    document.getElementById("messages").innerHTML = " Click on start to begin game ";
}

// function that is used to update counter 'i' very 60 ms 
function update(i)
{
    if(!end && start)
    {
        speed(i);
    }
}

// function to evaluate speed of ball to slow, medium and fast
function speed(i)
{
    if(ballSpeed == 0 && i%3 == 0)
    {
        moveBall();
    }
    else if(ballSpeed == 1 && i%2 == 0)
    {
        moveBall();
    }
    else if(ballSpeed == 2)
    {
        moveBall();
    }

}

// function to set te spped of ball in HTML file
function setSpeed(s)
{
    
    ballSpeed = s;
}

// function that is used to evaluate the movement of ball within the court boundary
function moveBall()
{
    // evaluating boundary conditions for court, paddle and ball
    var court = document.getElementById("court");
    boundary = court.getBoundingClientRect();
    var paddle = document.getElementById("paddle");
    paddleBoundary = paddle.getBoundingClientRect();
    var ball = document.getElementById("ball");
    ballRadius = ball.getBoundingClientRect();

    // detecting collision of ball with court boundary
    collision();


}
// function to evaluate the collision of ball with court boundary and its movement in the court
function collision()
{
    var ball = document.getElementById("ball");
    var collide = !((ballRadius.right < paddleBoundary.left)|| (ballRadius.left > paddleBoundary.right) || (ballRadius.top > paddleBoundary.bottom) || (ballRadius.bottom < paddleBoundary.top));
    if (ballRadius.left > paddleBoundary.right)
    {
        // Player is out and we need to display score and initialize strikes counter
        score();
        document.getElementById("messages").innerHTML = " ********** OOPS! GAME OVER ********** ";
        resetGame();
    }
    else if(collide)
    {
        // the ball hit paddle and we increase the counter for strikes
        strikes = strikes + 1;
        document.getElementById("strikes").innerHTML = strikes;

        // Moving ball to new location after hit
        offsetX = offsetX * -1;
        coordX = coordX + offsetX;
        coordY = coordY + offsetY;
        ball.style.left = coordX + 'px';
        ball.style.top = coordY + 'px';

        collide = false;
    }
    else
    {
        // the ball hits court boundary and we find new ball loaction
        coordX = coordX + offsetX;
        coordY = coordY + offsetY;
        ball.style.left = coordX + 'px';
        ball.style.top = coordY + 'px';

        // Reset offset boundary vectors
        if ((coordY + offsetY > ymax) || (coordY + offsetY < -90))
            offsetY = offsetY * -1;
        if ((coordX + offsetX > xmax) || (coordX + offsetX < 0 ))
            offsetX = offsetX * -1;
    }
}

// function to move paddle along the cursor movement
function movePaddle(e)
{     
    if (e.offsetY >= 0 && e.offsetY <= 400)
    {
        document.getElementById("paddle").style.position = "relative";
        document.getElementById("paddle").style.top = e.offsetY + "px";
    }
}

// function to evaluate the Max Score after the player is out
function score()
{
    if(maxScore < strikes)
    {
        maxScore = strikes;
        document.getElementById("score").innerHTML = maxScore;
    }
    document.getElementById("strikes").innerHTML = 0
}

// function to reset the game and to initialize the counters
function resetGame()
{
    initialize();
    end = true;
}
