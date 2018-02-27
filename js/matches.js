/**
 * Edited by Joshrl on 26/08/2016.
 */

/**
 * Module pattern for Matches functions
 */
var Matches = (function() {
    "use strict";
    var pub = {};

    /**
     * Builds and applies the two tables for current and upcoming matches.
     *
     * @param data XML data to be processes and displayed in the table.
     * @param current ID for the div where the current matches table will be build.
     * @param upcoming ID for the div where the upcoming matches table will be build.
     */
    function makeTables (data, current, upcoming) {
        var currentCount = 0;
        var upcomingCount = 0;
        $(current).append("<table><thead><tr><th class='seperator'>Date</th><th>Teams</th><th>Venue</th><th class='seperator'>Scores</th><th>Result</th><tbody>");
        $(upcoming).append("<table><thead><tr><th class='seperator'>Date</th><th>Teams</th><th>Venue</th></thead><tbody>");
        $(data).find("match").each(function () {
            if ($(this).find("team").attr("score")) {
                var team1Score = $($(this).find("team")[0]).attr("score");
                var team2Score = $($(this).find("team")[1]).attr("score");
                var row = $("<tr>").append("<td class='seperator'>" + $(this).find("day")[0].textContent + "-" + $(this).find("month")[0].textContent + "-" + $(this).find("year")[0].textContent + "</td><td>"+$(this).find("team")[0].textContent+" vs. "+$(this).find("team")[1].textContent+"</td><td>"+$(this).find("venue")[0].textContent+"</td><td class='seperator'>"+team1Score+"-"+team2Score+"</td>");
                if (team1Score > team2Score) {
                    $(row).append("<td>"+$(this).find("team")[0].textContent+" Won</td>");
                } else if (team2Score > team1Score) {
                    $(row).append("<td>"+$(this).find("team")[0].textContent+" Won</td>");
                } else {
                    $(row).append("<td>Draw</td>");
                }
                $(current).find("tbody").append(row);
                currentCount++;
            } else {
                $(upcoming).find("table").append("<tbody><tr><td class='seperator'>" + $(this).find("day")[0].textContent + "-" + $(this).find("month")[0].textContent + "-" + $(this).find("year")[0].textContent + "</td><td>"+$(this).find("team")[0].textContent+" vs. "+$(this).find("team")[1].textContent+"</td><td>"+$(this).find("venue")[0].textContent);
                upcomingCount++;
            }
        });
        if (currentCount === 0) {
            $(current).hide();
            $("#currentMatches").html("<p>There are no played matches.");
        }
        if (upcomingCount === 0) {
            $(upcoming).hide();
            $("#upcomingMatches").html("<p>There are no upcoming matches.");
        }
    }

    /**
     * Setup function for Matches.
     *
     * Gets XML data and div IDs to pass to makeTables.
     */
    pub.setup = function() {
        $.ajax({
            type: "GET",
            url: "xml/tournament.xml",
            cache: false,
            success: function(data) {
                makeTables(data, $("#currentTable"), $("#upcomingTable"));
            },
            error: function() {
                $("#currentTable").hide();
                $("#currentMatches").html("<p>There are no played matches.");
                $("#upcomingTable").hide();
                $("#upcomingMatches").html("<p>There are no upcoming matches.");
            }
        });
    };
    return pub;
}());
// Run setup when document is ready.
$(document).ready(Matches.setup);
