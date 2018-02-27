/**
 * Created by Joshrl on 10/5/2016.
 */

var Create = (function () {
    "use strict";
    var pub = {};
    var teamNum = 0;
    var venueNum = 0;
    var teams = [];
    var venues = [];

    function htmlEntities(str) {
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    pub.addTeam = function () {
        var team = htmlEntities($("#teamText").val());
        // Check for errors
        if (teams.indexOf(team) !== -1) {
            $("#error").html("<b>Error:</b> There cannot be duplicate teams in the tournament.");
            return false;
        }
        if (team.length == 0) {
            $("#error").html("<b>Error:</b> A team name must be entered.");
            return false;
        }
        // Add to table and array
        if (teamNum == 0) {
            $("#teamList").html("Here is a list of teams to be added: <ul></ul>");
        }
        $("#teamList").find("ul").append("<li>" + team + "</li>");
        $("#error").html("");
        teams.push(team);
        teamNum++;
        if (teamNum >= 2 && venueNum >= 1) {
            $("#tournamentCreate").prop("disabled", false);
        }
    };

    pub.addVenue = function () {
        var venue = htmlEntities($("#venueText").val());
        // Check for errors
        if (venues.indexOf(venue) !== -1) {
            $("#error").html("<b>Error:</b> There cannot be duplicate venues in the tournament.");
            return false;
        }
        if (venue.length == 0) {
            $("#error").html("<b>Error:</b> A venue name must be entered.");
            return false;
        }
        // Add to table and array
        if (venueNum == 0) {
            $("#venueList").html("Here is a list of venues to be added: <ul></ul>");
        }
        $("#venueList").find("ul").append("<li>" + venue + "</li>");
        $("#error").html("");
        venues.push(venue);
        venueNum++;
        if (teamNum >= 2 && venueNum >= 1) {
            $("#tournamentCreate").prop("disabled", false);
        }
    };

    pub.createTournament = function () {
        $.post("adminCreate.php", {"teams": teams, "venues": venues}, function(data){
            $("#message").html(data);
        });
    };

    pub.setup = function () {
        // Get table for preview
        $("#teamSubmit").click(Create.addTeam);
        $("#venueSubmit").click(Create.addVenue);
        $("#tournamentCreate").click(Create.createTournament);
    };

    // Return public interface
    return pub;
}());
// Run setup when document is ready.
$(document).ready(Create.setup);