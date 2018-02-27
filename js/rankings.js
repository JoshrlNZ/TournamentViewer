/**
 * Edited by Joshrl on 25/08/2016.
 */

/**
 * Module pattern for Rankings functions
 */
var Rankings = (function() {
    "use strict";
    var pub = {};

    /**
     * Team object for storing name and points from matches.
     *
     * @param name_ Team name used for checking uniqueness.
     */
    function Team(name_) {
        this.name = name_;
        this.won = 0;
        this.drawn = 0;
        this.lost = 0;
        this.forhere = 0;
        this.foraway = 0;
        this.getPoints = function () {
            return 2 * this.won + this.drawn;
        };
        this.getDiff = function () {
            return (this.forhere - this.foraway);
        };
        this.getPlayed = function () {
            return (this.won + this.drawn + this.lost);
        };
        this.giveResult = function (here, away) {
            if (here > away) {
                this.won++;
            } else if (away > here) {
                this.lost++;
            } else {
                this.drawn++;
            }
            this.forhere += Number(here);
            this.foraway += Number(away);
        };
    }

    /**
     * Processes data from XML file and builds it into a table.
     *
     * @param data XML object retrieved from XML file.
     * @param target ID of div to place table in.
     */
    function parseData (data, target) {
        // Create Teams from XML data and feed results
        var teams = [];
        var teamnames = [];
        $(data).find("team").each(function () {
            if ($(this).attr("score")) {
                var check = jQuery.inArray($(this)[0].textContent, teamnames);
                if (check != -1) {
                    teams[check].giveResult($(this).attr("score"), $(this).parent().find("team").not(this).attr("score"));
                } else {
                    teamnames.push($(this)[0].textContent);
                    teams.push(new Team($(this)[0].textContent));
                    teams[teams.length - 1].giveResult($(this).attr("score"), $(this).parent().find("team").not(this).attr("score"));
                }
            }
        });
        // Sort the array.
        teams.sort(function (a, b) {
            return (a.getPoints() > b.getPoints()) ? -1 : (a.getPoints() < b.getPoints()) ? 1 : (a.getDiff() < b.getDiff()) ? 1 : (a.getDiff() < b.getDiff()) ? -1 : 0;
        });
        // Process Team data for table
        $(target).append("<table><thead><tr><th>Rank</th><th class='seperator'>Team</th><th>Played</th><th>Won</th><th class='seperator'>Drawn</th><th>Lost</th><th>For</th><th class='seperator'>Against</th><th>Diff.</th><th>Points</th>");
        var rank = 1;
        $(teams).each(function () {
            $(target).find("table").append("<tbody><td>"+rank+"</td><td class='seperator'>"+$(this)[0].name+"</td><td>"+$(this)[0].getPlayed()+"</td><td>"+$(this)[0].won+"</td><td class='seperator'>" +$(this)[0].drawn+"</td><td>"+$(this)[0].lost+"</td><td>"+$(this)[0].forhere+"</td><td class='seperator'>"+$(this)[0].foraway+"</td><td>"+$(this)[0].getDiff()+"</td><td>"+$(this)[0].getPoints()+"</td>");
            rank++;
        });
        if (rank == 1) {
            $("#rankingTable").hide();
            $("#content").find("p").html("There are no rankings for this tournament.");
        }
    }

    /**
     * Setup function for Rankings.
     *
     * Gets XML data and div IDs to pass to parseData.
     */
    pub.setup = function() {
        $.ajax({
            type: "GET",
            url: "xml/tournament.xml",
            cache: false,
            success: function(data) {
                parseData(data, $("#rankingTable"));
            },
            error: function() {
                $("#rankingTable").hide();
                $("#content").find("p").html("There are no rankings for this tournament.");
            }
        });
    };
    return pub;
}());
// Run setup when document is ready.
$(document).ready(Rankings.setup);
