# GMHS
an simple to setup and easy to use online High Score system for GameMaker

## Whats needed?
- a web server that can run PHP scripts
- GameMaker Studio 2 (Easy to port to other engines - fork it and make a PR if you make something cool!) 

## Setup
**Server**
- Place the files inside of /PHP into the directory of your choice on your server
- Edit the "config.php" script to add your personal secret key

**GameMaker**
- Import the gmhs.gml file as a script into your project
- Call the gmhsConfig() function before using the other functions 
Here's an example of how to use gmhsConfig()

`gmhsConfig("www.example.com", "https://example.com/mygame/gmhs.php", "MyGame");`

## Submitting Scores

`gmhsSubmit("NAME", SCORE, "YOURSECRETKEY");`

## Reading Scores
The following example will send a request to the server for the top 70 scores (if there are less than 70, it will return the amount it has). 

`RequestScores = gmhsRequestScores(70, true);`

The second attribute set to true means that scores will be read as higher being better. 
If a lower score in your game is better, set it to false.

Once the scores are recieved, we can call the gmhsParseScores() function in the **Async - HTTP** event like this: 

    if (async_load[? "id"] == RequestScores) {
    	// Parses the HS list recieved
    	gmhsParseScores(async_load[? "result"]);
    }
	
This function then adds the recieved names and scores to two arrays that can easily be linked together. 

These arrays are global.gmhsNames and global.gmhsScores, which are both overwritten whenever you load scores from your server. 

You can easily loop through these arrays to display your High Score list... here's how it's done in the example file in the Draw event: 

    // Draws the loaded High Scores
    for(i = 0; i < array_length(global.gmhsNames); i++) { 
    	draw_text(0, 15 * i, string(i+1) + ". " + global.gmhsNames[i]);
    	draw_text(150, 15 * i, string(global.gmhsScores[i]));
    }

## You can also view scores from the web!
Visit your GMHS installation, and browse to the gmhs.php file. Append ?aid=YOURAPPNAME and you'll see the top scores in your browser. This can be used for embedding scores on your game's website. 

You can also add further attributes to the URL for sorting and expanding the number of scores shown. 

To change the number of scores shown:
&num=[numberofscorestodisplay]

To change scores ascending or decending (hl means High to Low): 
&hl=[trueORfalse]
