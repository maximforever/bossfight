$(document).ready(main());

function main(){

    console.log("jQuery is up!");

    $(".active").click(function(){
        console.log("clicked!");
        $(".active").addClass("inactive");
        $(this).removeClass("inactive");
        $(this).addClass("selected");

    });
}

//---I'm not sure how to fix it, but the console keeps giving me an error when this function is active. I think it's missing a parenthesis or something.

//function refreshTeam(teamArray){
//    var comrades = teamArray.split(",");
//    if(comrades.length > 0){
//        for (var i = 0; i < comrades.length; i++){
//            $("#team-list").append('<li><' + comrades[i] '/li>');
//        }
//    }
//}

//---ajax---//
    //---initial load---//
        $(document).ready(function(){
            data = 'game='+ gameid; //How do we get this gameid from the front-end?

            $.ajax({
                url: "status.php",
                type: "POST",
                data: data,
                cache: false,
                success: function(result) {
                    var resultArray = result.split(";");
                    $("#name").empty();
                    $("#name").append(resultArray[0]);

                    $("#health").empty();
                    $("#health").append(resultArray[1]);
                    $("#strength").empty();
                    $("#strength").append(resultArray[2]);
                    $("#speed").empty();
                    $("#speed").append(resultArray[3]);

                    $("#state").empty();
                    $("#state").append(resultArray[4]);

                    $("#weather").empty();
                    $("#weather").append(resultArray[5]);

                    $("#playerids").empty();
                    $("#playerids").append(resultArray[6]);
                    $("#team-list").empty();
                    //refreshTeam(resultArray[7]);
                }
            });
        });

    //---count refresh---//
        setInterval(function(){ 
            data = 'game='+ gameid; //How do we get this gameid from the front-end?

            $.ajax({
                url: "status.php",
                type: "POST",
                data: data,
                cache: false, 
                success: function(result) {
                    var resultArray = result.split(";");
                    $("#name").empty();
                    $("#name").append(resultArray[0]);

                    $("#health").empty();
                    $("#health").append(resultArray[1]);
                    $("#strength").empty();
                    $("#strength").append(resultArray[2]);
                    $("#speed").empty();
                    $("#speed").append(resultArray[3]);

                    $("#state").empty();
                    $("#state").append(resultArray[4]);

                    $("#weather").empty();
                    $("#weather").append(resultArray[5]);

                    $("#playerids").empty();
                    $("#playerids").append(resultArray[6]);
                    $("#team-list").empty();
                    //refreshTeam(resultArray[7]);
            }});    
        }, 15000);

    //---playermove ajax---//
        function playermove(button) {
            data = 'playermove='+button; //how do we get the id of the button that was clicked?

            $.ajax({
                url: "playermove.php",
                type: "POST",
                data: data,
                cache: false,
                success: function(result) {
                    var resultArray = result.split(";");
                    $("#name").empty();
                    $("#name").append(resultArray[0]);

                    $("#health").empty();
                    $("#health").append(resultArray[1]);
                    $("#strength").empty();
                    $("#strength").append(resultArray[2]);
                    $("#speed").empty();
                    $("#speed").append(resultArray[3]);

                    $("#state").empty();
                    $("#state").append(resultArray[4]);

                    $("#weather").empty();
                    $("#weather").append(resultArray[5]);

                    $("#playerids").empty();
                    $("#playerids").append(resultArray[6]);
                    $("#team-list").empty();
                    //refreshTeam(resultArray[7]);
                }
            });
        }