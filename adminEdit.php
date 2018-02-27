<?php
if (!empty($_POST)) {
    include('php/validateEdit.php');
} else {
    $scriptList = array("js/jquery-3.1.0.min.js", "js/edit.js", "js/validateEdit.js");
    include("php/header.php");
    ?>
    <div id="content">
        <h2>Edit Matches</h2>
        <p>Here you can edit matches to change dates, teams, venues or scores.</p>
        <?php
        // Create edit elements
        $xmlExists = false;
        if (file_exists("xml/tournament.xml")) {
            $xml = simplexml_load_file('xml/tournament.xml');
            if (count($xml->xpath("match")) > 0) {
                $xmlExists = true;
                ?>
                <p>Click a row below to start editing that match.</p>
                <div id="previewTable">
                    <?php
                    include("createTable.php");
                    ?>
                </div>
                <p>Once you have clicked a row, you can edit and submit it here:</p>
                <div id='errors'></div>
                <form id="adminForm" novalidate>
                    <fieldset>
                        <legend>Edit Row:</legend>
                        <input type="hidden" name="number" id="number">
                        <p>
                            <label for="date">Date:</label>
                            <input type="date" name="date" id="date" autocomplete="off" required disabled>
                        </p>
                        <p>
                            <label for="venue">Venue:</label>
                            <select name="venue" id="venue" autocomplete="off" disabled>
                                <?php
                                // Create venue options
                                if (file_exists("xml/venues.xml")) {
                                    $xml = simplexml_load_file('xml/venues.xml');
                                    $venues = $xml->xpath("venue");
                                    foreach ($venues as $venue) {
                                        echo "<option value='$venue'>$venue</option>";
                                    }
                                }
                                ?>
                            </select>
                        </p>
                        <p>
                            <label for="playedCheck">Match played?:</label>
                            <input type="checkbox" name="playedCheck" id="playedCheck" disabled>
                        </p>
                        <p>
                            <label for="score1" id="scoreLabel1">Team 1's Score:</label>
                            <input type="text" name="score1" id="score1" autocomplete="off" disabled>
                        </p>
                        <p>
                            <label for="score2" id="scoreLabel2">Team 2's Score:</label>
                            <input type="text" name="score2" id="score2" autocomplete="off" disabled>
                        </p>
                        <input type="button" value="Submit" id="formSubmit" disabled>
                    </fieldset>
                </form>
                <?php
            }
        }
        if (!$xmlExists) {
            echo "<p>There is no data to edit.</p>";
        }
        ?>
    </div>
<?php include("php/footer.php"); } ?>