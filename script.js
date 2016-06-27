$(document).ready(main());

currentRound = 0;

function main(){

    console.log("jQuery is up!");
    resetButtons();

    
}

   $("#undo").click(function(){
    console.log("Undoing!");
        resetButtons();
   });

// ------------------


// ------------------


var this_round = 0;


function resetButtons() {                                          // this function sets up all the main action-banner actions
    $(".action-holder").css('margin-left', '-8%');
    $("#attack").attr('src', 'assets/banner-red.png');
    $("#dodge").attr('src', 'assets/banner-yellow.png');
    $("#heal").attr('src', 'assets/banner-green.png');

    $(".action-holder").hover(                              // this gives the banner buttons sexy styling
        function(){
            console.log("entering");
            new Audio("assets/swoosh.mp3").play();          // this plays our swoosh sound
            $(this).css('margin-left', '0%');

        }, function(){
            console.log("leaving");
            $(this).css('margin-left', '-8%');
        }
    ); 

    $(".action-banner").click(function(){                   // on click, we select one 
        console.log("clicked!");
        $(".action-banner").not(this).attr('src', 'assets/banner-inactive.png');
        $(".action-holder").off('click mouseenter mouseleave');          // this unbinds all hover and click effects        
        
    });
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


function updateBossHealth(boss_health, full_boss_health){
    console.log("updating boss health");
    console.log(boss_health + "/" + full_boss_health);
    $("#boss-alive").width(100 * (boss_health/full_boss_health) + "%");
    $("#boss-dead").width(100 - (100 * boss_health/full_boss_health) + "%");
    $("#full_boss_health").empty().append(full_boss_health);

}

function updateUserHealth(user_health, full_user_health){
    console.log("updating user health");
    console.log(user_health + "/" + full_user_health);

    $("#full_user_health").empty().append(full_user_health);

    /*$("#user-alive").css("width", user_health/full_user_health * $( window ).width());
    $("#user-dead").css("width", (1-user_health/full_user_health) * $( window ).width() );*/

    $("#user-alive").width(100 * (user_health/full_user_health) + "%");
    $("#user-dead").width(100 - (100 * user_health/full_user_health) + "%");
}



function updateTeam(array){

    var teamSize = $("#team-list li").length;

    console.log("Actual Teammates:" + array.length);
    $("#team-list").empty();
    team = array.split(",");
    for(var i = 0 ; i < team.length; i++){
        console.log(team[i]);
        $('#team-list').append("<li class = 'team-mate'>" + team[i] +"</li>");
    }
};


function refresh(result) {

    var resultArray = result.split(";");
    console.log("REFRESHING. Current round: " + resultArray[5]);

    console.log("this round is " + this_round);
    if (resultArray[5] > this_round) {
        resetButtons();
        this_round = resultArray[5];
        $("body").hide();
        setTimeout(function() {
            $("body").show();
        }, 100);
    }

    if(resultArray[8] <= 0){
        resultArray[8] = 0; //this is a temporary fix. for some reason, health keeps decreasing below 0;
        console.log("aw, sheet, you dead.");
        $(window).attr("location","http://localhost/bossfight/lose.html");      //this redirects us to the lose page.
    }


    $("#gameid").empty();
    $("#gameid").append(resultArray[0]);
    $("#boss").empty();
    $("#boss").append(resultArray[1]);
    $("#boss_health").empty();
    $("#boss_health").append(resultArray[2]);
    $("#weather").empty();
    $("#weather").append(resultArray[3]);;
    $("#gamestate").empty();
    $("#gamestate").append(resultArray[4]);
    //$("#roundcount").empty();
    //$("#roundcount").append(resultArray[5]);
    $("#story").empty();
    //refreshStory(resultArray[6]);

    $("#name").empty();
    $("#name").append(resultArray[7]);
    $("#user_health").empty();
    $("#user_health").append(resultArray[8]);
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

    $("#boss_max_health").empty();
    $("#boss_max_health").empty(resultArray[14]);
    $("#player_max_health").empty();
    $("#player_max_health").empty(resultArray[15]);

    //refresh buttons:
    updateStory(resultArray[6]);
    updateTeam(resultArray[13]);
    console.log("Boss:" + resultArray[1]);
    updateBossHealth(resultArray[2], resultArray[14]);
    updateUserHealth(resultArray[8], resultArray[15], resultArray[14]);

    //redirect to "dead" screen if the player is dead:

    //redirect from participant.html to start game:
    if (( String( $(window).attr("location") ).indexOf("participant.html") > 0 ) && (resultArray[4] !== "setting up")) {
        console.log("redirecting to main page");
        $(window).attr("location","http://localhost/bossfight/main.html");
    }


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
        setInterval(function(){ //every 15 (changed to 3) seconds, this function GETs the game id (if set) and returns status items
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
