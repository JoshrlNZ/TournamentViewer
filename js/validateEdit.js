/**
 * Edited by Joshrl on 26/08/2016.
 */

/**
 * Module pattern for ValidateAdmin functions
 */
var ValidateEdit = (function () {
    "use strict";
    var pub = {};

    /**
     * Checks if the argument date matches a date pattern.
     *
     * @param date Date to check if valid.
     * @param messages Array for holding error messages.
     */
    function checkDate(date, messages) {
        var pattern = /\d{1,2}-\d{1,2}-\d{4}/;
        if (!pattern.test(date)) {
            messages.push("Date must be in the format dd-mm-yyyy.");
        }
    }

    /**
     * Checks if a team is playing two games on the same day.
     *
     * @param date Date of the match being edited.
     * @param team Team name to be checked.
     * @param messages Array for holding error messages.
     */
    function checkDays(date, team, messages) {
        var timesFound = 0;
        $("#previewTable").find("tbody").find("tr").not(".selected").each(function () {
            if ($(this).find("td")[1].textContent == date) {
                if ($(this).find("td")[2].textContent == team || $(this).find("td")[3].textContent == team) {
                    timesFound++;
                }
            }
        });
        if (timesFound > 0) {
            messages.push(team + " cannot play two games on the same day.");
        }
    }

    /**
     * Checks if a venue is used more than one time on the same day.
     *
     * @param date Date of the match being edited.
     * @param venue Venue name to be checked.
     * @param messages Array for holding error messages.
     */
    function checkVenues(date, venue, messages) {
        $("#previewTable").find("tbody").find("tr").not(".selected").each(function () {
            if (($(this).find("td")[1].textContent == date) && ($(this).find("td")[4].textContent == venue)) {
                messages.push("Two games cannot be on the same day and at the same venue.");
            }
        });
    }

    /**
     * Checks the score argument to see if it is empty.
     *
     * @param score Value of team's score to be checked.
     * @param team String of team's name.
     * @param messages Array for holding error messages.
     */
    function checkScore(score, team, messages) {
        var pattern = /^[-+]?\d+$/;
        if (!pattern.test(score)) {
            messages.push("There must be a score for " + team +  " if 'Match Played?' is checked, and it must be an integer.");
        }
    }

    /**
     *  Validates the form to see if data has been inputted correctly.
     *
     * @returns {boolean} Returns false if errors occur and there are messages, otherwise true.
     */
    pub.validate = function() {
        var messages = [];

        // Date validation
        var dateField = $("#date").val();
        checkDate(dateField, messages);

        // Team 1 Name validation
        var teamRow = $("#previewTable").find("tbody").find("tr")[$("#number").attr('value')-1];
        var team1 = $(teamRow).find("td")[2].textContent;
        checkDays(dateField, team1, messages);

        // Team 2 Name validation
        var team2 = $(teamRow).find("td")[3].textContent;
        checkDays(dateField, team2, messages);

        // Venue validation
        var venue = $("#venue").val();
        checkVenues(dateField, venue, messages);

        // Score validation
        if ($("input:checked").length === 1) {
            var score1 = $("#score1").val();
            checkScore(score1, team1, messages);

            var score2 = $("#score2").val();
            checkScore(score2, team2, messages);
        }

        if (messages.length === 0) {
            return true;
        } else {
            // Report the error messages
            $("#errors").html("<p><strong>Please fix these errors before submitting:</strong></p>").append("<ul>");
            $(messages).each(function () {
                $("#errors").find("ul").append("<li>" + this);
            });
        }

        return false;
    };

    // Return public interface
    return pub;
}());
