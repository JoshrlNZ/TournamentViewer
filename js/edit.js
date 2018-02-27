/**
 * Edited by Joshrl on 26/08/2016.
 */

/**
 * Module pattern for Admin functions
 */
var Edit = (function () {
    "use strict";
    var pub = {};

    /**
     * Toggles the disabled property when checkbox is checked.
     */
    function changeScores() {
        if ($("input:checked").length === 1) {
            $("#score1").prop("disabled", false);
            $("#score2").prop("disabled", false);
        } else {
            $("#score1").prop("disabled", true);
            $("#score2").prop("disabled", true);
        }
    }

    /**
     * Applies style and enables form elements for row editing.
     */
    function selectRow() {
        /* jshint -W040 */
        $(".selected").css({backgroundColor: "#e9e9e9"}).removeAttr("class");
        $(this).css({backgroundColor: "#cfffbc"}).attr("class", "selected");
        $("#number").val($(this).find("td")[0].textContent);
        $("#date").val($(this).find("td")[1].textContent).prop("disabled", false);
        $("#venue").val($(this).find("td")[4].textContent).prop("disabled", false);
        $("#formSubmit").prop("disabled", false);
        if ($(this).find("td")[4].textContent !== "" && $(this).find("td")[5].textContent !== "") {
            $("#playedCheck").prop("checked", true).prop("disabled", false);
            $("#score1").val($(this).find("td")[5].textContent).prop("disabled", false);
            $("#score2").val($(this).find("td")[6].textContent).prop("disabled", false);
        } else {
            $("#playedCheck").prop("checked", false).prop("disabled", false);
            $("#score1").val($(this).find("td")[5].textContent).prop("disabled", true);
            $("#score2").val($(this).find("td")[6].textContent).prop("disabled", true);
        }
        $("#scoreLabel1").html($(this).find("td")[2].textContent + "'s Score:");
        $("#scoreLabel2").html($(this).find("td")[3].textContent + "'s Score:");
        /* jshint +W040 */
    }

    pub.editMatch = function () {
        if (ValidateEdit.validate()) {
            $.post("adminEdit.php", {
                "number": $("#number").val(),
                "date": $("#date").val(),
                "venue": $("#venue").val(),
                "playedCheck": $("#playedCheck").is(":checked") ? "on" : "off",
                "score1": $("#score1").val(),
                "score2": $("#score2").val(),
            }, function (data) {
                $("#errors").html(data);
                $.post("createTable.php", function (data2) {
                    $("#previewTable").html(data2);
                    $("#previewTable").find("table").find("tbody").find("tr").click(selectRow);
                    $("#date").prop("disabled", true);
                    $("#venue").prop("disabled", true);
                    $("#playedCheck").prop("disabled", true);
                    $("#score1").prop("disabled", true);
                    $("#score2").prop("disabled", true);
                });
            });
        }
    };

    /**
     * Setup function for ValidateAdmin.
     *
     * Sets up events for using the table.
     */
    pub.setup = function () {
        // Get table for preview
        $("#previewTable").find("table").find("tbody").find("tr").click(selectRow);
        $("#playedCheck").change(changeScores);
        $("#formSubmit").click(Edit.editMatch);
    };

    // Return public interface
    return pub;
}());
// Run setup when document is ready.
$(document).ready(Edit.setup);
