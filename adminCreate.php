<?php
if (!empty($_POST)) {
    include("php/validateCreate.php");
} else {
    $scriptList = array("js/jquery-3.1.0.min.js", "js/create.js");
    include("php/header.php");
    ?>
    <div id="content">
        <h2>Create Tournament</h2>
        <p>Here you can create a new tournament by providing teams and venues.</p>
        <p id="message"></p>
        <fieldset>
            <legend>Add Teams and Venues:</legend>
            <p>There must be a minimum of two teams and one venue to create a tournament.</p>
            <p id="error"></p>
            <p>
                <label for="teamText">Team:</label>
                <input type="text" id="teamText">
                <input type="button" value="Add Team" id="teamSubmit">
            </p>

            <p>
                <label for="venueText">Venue:</label>
                <input type="text" id="venueText">
                <input type="button" value="Add Venue" id="venueSubmit">
            </p>

            <p id="teamList">There are currently no teams to be added.</p>
            <p id="venueList">There are currently no venues to be added.</p>
            <input type='button' value='Create New Tournament' id='tournamentCreate' disabled>
        </fieldset>
    </div>
<?php }
include("php/footer.php"); ?>
