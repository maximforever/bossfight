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



function refreshTeam(teamArray){

    $("#header ul").append('<li><a href="/user/messages"><span class="tab">Message Center</span></a></li>');
    var comrades = TeamArray.split(",");

    if(comrades.length > 0){
        for (var i = 0; i < comrades.length; i++){
            $("#team-list").append('<li><' + comrades[i] '/li>');
        }
    }


}