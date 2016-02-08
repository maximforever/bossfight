$(document).ready(main());

currentRound = 0;

function main(){

    console.log("jQuery is up!");

    $(".active").click(function(){
        console.log("clicked!");
        $(".active").addClass("inactive");
        $(".active").removeClass("selected");
        $(this).removeClass("inactive");
        $(this).addClass("selected");
    });
}

var this_round = 0;

function unclickAllButtons() {
    $(".inactive").removeClass("inactive");
    $(".active").removeClass("selected");
};

function updateStory(array){
    if (array == ""){
        array = "The sun shines overhead";
    }
    $("#story").empty();
    story = array.split(",");
    for(var i = 0 ; i < story.length; i++){
        $('#story').append("<p>"+story[i] +"</p>");
    }
};

function refresh(result) {
    var resultArray = result.split(";");
    console.log("REFRESHING. Current round: " + resultArray[5]);

    if(resultArray[8] <= 0){
        resultArray[8] = 0; //this is a temporary fix. for some reason, health keeps decreasing below 0;
        console.log("aw, sheet, you dead.");
        $(window).attr("location","http://localhost/bossfight/lose.html");      //this redirects us to the lose page.
    }


    $("#gameid").empty();
    $("#gameid").append(resultArray[0]);
    $("#boss").empty();
    $("#boss").append(resultArray[1]);
    $("#bosshealth").empty();
    $("#bosshealth").append(resultArray[2]);
    $("#weather").empty();
    $("#weather").append(resultArray[3]);;
    $("#gamestate").empty();
    $("#gamestate").append(resultArray[4]);
    $("#roundcount").empty();
    $("#roundcount").append(resultArray[5]);
    $("#story").empty();
    //refreshStory(resultArray[6]);

    $("#name").empty();
    $("#name").append(resultArray[7]);
    $("#health").empty();
    $("#health").append(resultArray[8]);
    $("#strength").empty();
    $("#strength").append(resultArray[9]);
    $("#speed").empty();
    $("#speed").append(resultArray[10]);
    $("#playermove").empty();
    $("#playermove").append(resultArray[11]);
    $("#playerstate").empty();
    $("#playerstate").append(resultArray[12]);

    $("#team-list").empty();
    //refreshTeam(resultArray[13]);

    //refresh buttons:
    updateStory(resultArray[6]);

    //redirect to "dead" screen if the player is dead:


}



//---ajax---//
    //---get---// //this function turns url parameters into GET('variables')
        var GET = function GET(index) {
            var urlquery = decodeURIComponent(window.location.search.substring(1)),
                urlvariables = urlquery.split('&'),
                index,
                i;

            for (i = 0; i < urlvariables.length; i++) {
                urlparameter = urlvariables[i].split('=');

                if (urlparameter[0] === index) {
                    return urlparameter[1] === undefined ? true : urlparameter[1];
                }
            }
        };

    //---initial load---// //on load, this function GETs the game id (if set) and returns status items
        $(document).ready(function(){
            data = "game=" + GET("game");

            $.ajax({
                url: "status.php",
                type: "POST",
                data: data,
                cache: false,
                success: function(result) {
                    console.log("initial load");
                    refresh(result);
                }
            });
        });

    //---count refresh---//
        setInterval(function(){ //every 15 seconds, this function GETs the game id (if set) and returns status items
            data = "game=" + GET("game");

            $.ajax({
                url: "status.php",
                type: "POST",
                data: data,
                cache: false, 
                success: function(result) {
                    refresh(result);
            }});    
        }, 3000);          //changed do 3 secs for ease of testing

    //---playermove ajax---// //this function POSTs the id of button presses and return status items
        function playermove(button) {
            data = "playermove=" + button; //how do we get the id of the button that was clicked?

            $.ajax({
                url: "playermove.php",
                type: "POST",
                data: data,
                cache: false,
                success: function(result) {
                    refresh(result);
                }
            });
        }