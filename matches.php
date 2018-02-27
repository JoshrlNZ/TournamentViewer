<?php
$scriptList = array();
include("php/header.php");
?>
    <div id="content">
        <h2>Matches</h2>
        <?php
        $playedGames = array();
        $unplayedGames = array();
        if (file_exists("xml/tournament.xml")) {
            $number = 1;
            $xml = simplexml_load_file('xml/tournament.xml');
            $matches = $xml->xpath("match");
            if (count($matches) > 0) {
                foreach ($matches as $match) {
                    if (isset($match->team->attributes()['score'])) {
                        $date = $match->date->day . "-" . $match->date->month . "-" . $match->date->year;
                        $team1 = $match->team[0];
                        $team2 = $match->team[1];
                        $score1 = intval($match->team[0]->attributes()['score']);
                        $score2 = intval($match->team[1]->attributes()['score']);
                        $gameString = "<tr><td class='seperator'>$number</td><td class='seperator'>$date</td><td>$team1 vs. $team2</td><td>$match->venue</td><td class='seperator'>$score1-$score2</td><td>";
                        if ($score1 > $score2) {
                            $gameString .= "$team1 Won";
                        } else if ($score2 > $score1) {
                            $gameString .= "$team2 Won";
                        } else {
                            $gameString .= "Draw";
                        }
                        $gameString .= "</td></tr>";
                        $playedGames[] = $gameString;
                        $number++;
                    } else {
                        $date = $match->date->day . "-" . $match->date->month . "-" . $match->date->year;
                        $team1 = $match->team[0];
                        $team2 = $match->team[1];
                        $unplayedGames[] = "<tr><td class='seperator'>$number</td><td class='seperator'>$date</td><td>$team1 vs. $team2</td><td>$match->venue</td></tr>";
                        $number++;
                    }
                }

            }
        }
        if (count($playedGames) > 0) {
            echo "<p id='currentMatches'>Here are the played matches and their scores:</p>";
            echo "<table><thead><tr><th class='seperator'>No.</th><th class='seperator'>Date</th><th>Teams</th><th>Venue</th><th class='seperator'>Scores</th><th>Result</th></tr></thead><tbody>";
            foreach ($playedGames as $gameInfo) {
                echo $gameInfo;
            }
            echo "</tbody></table>";
        } else {
            echo "<p>There are no played matches.</p>";
        }
        if (count($unplayedGames) > 0) {
            echo "<p id='currentMatches'>Here are the upcoming matches:</p>";
            echo "<table><thead><tr><th class='seperator'>No.</th><th class='seperator'>Date</th><th>Teams</th><th>Venue</th></tr></thead><tbody>";
            foreach ($unplayedGames as $gameInfo) {
                echo $gameInfo;
            }
            echo "</tbody></table>";
        } else {
            echo "<p>There are no upcoming matches.</p>";
        }
        ?>
    </div>
<?php include("php/footer.php") ?>