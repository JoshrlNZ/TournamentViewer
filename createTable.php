<?php
// Generate and display table
if (file_exists("xml/tournament.xml")) {
    $xml = simplexml_load_file('xml/tournament.xml');
    if (count($xml->xpath("match")) > 0) {
        $matches = $xml->xpath("match");
        $number = 1;
        echo "<table><thead><tr><th class='seperator'>No.</th><th class='seperator'>Date</th><th>Team 1</th><th>Team 2</th><th>Venue</th><th>Score 1</th><th>Score 2</th></tr></thead><tbody>";
        foreach ($matches as $match) {
            $date = $match->date->day . "-" . $match->date->month . "-" . $match->date->year;
            $team1 = $match->team[0];
            $team2 = $match->team[1];
            $score1 = isset($match->team[0]->attributes()['score']) ? intval($match->team[0]->attributes()['score']) : "";
            $score2 = isset($match->team[1]->attributes()['score']) ? intval($match->team[1]->attributes()['score']) : "";
            echo "<tr><td class='seperator'>$number</td><td class='seperator'>$date</td><td>$team1</td><td>$team2</td><td>$match->venue</td><td>$score1</td><td>$score2</td></tr>";
            $number++;
        }
        echo "</tbody></table>";
    }
}
?>