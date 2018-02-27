<?php
if (isset($_REQUEST['teams']) && isset($_REQUEST['venues'])) {
    $teams = $_REQUEST['teams'];
    $venues = $_REQUEST['venues'];
    if ($teams >= 1 && $venues >= 2) {
        $tournament = new SimpleXMLElement('<tournament></tournament>');
        $venueXML = new SimpleXMLElement('<venues></venues>');
        $match = 0;
        $teamCount = 0;
        foreach ($teams as $team) {
            for ($i = $teamCount + 1; $i < count($teams); $i++) {
                $tournament->addChild("match");
                $tournament->match[$match]->addChild("date");
                $tournament->match[$match]->date->addChild("day", $match + 1);
                $tournament->match[$match]->date->addChild("month", "1");
                $tournament->match[$match]->date->addChild("year", "2016");
                $tournament->match[$match]->addChild("venue", htmlentities($venues[0]));
                $tournament->match[$match]->addChild("team", htmlentities($team));
                $tournament->match[$match]->addChild("team", htmlentities($teams[$i]));
                $match++;
            }
            $teamCount++;
        }
        foreach ($venues as $venue) {
            $venueXML->addChild("venue", htmlentities($venue));
        }
        $tournament->saveXML('xml/tournament.xml');
        $venueXML->saveXML('xml/venues.xml');
        echo "Your new tournament has been saved.";
    }
}
?>