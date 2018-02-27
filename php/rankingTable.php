<?php
class Team {
    public $name;
    public $won = 0;
    public $drawn = 0;
    public $lost = 0;
    public $forHere = 0;
    public $forAway = 0;

    function __construct($name_) {
        $this->name = $name_;
    }

    function getPoints() {
        return ((2 * $this->won) + $this->drawn);
    }

    function getDiff() {
        return ($this->forHere - $this->forAway);
    }

    function getPlayed() {
        return ($this->won + $this->drawn + $this->lost);
    }

    function giveResult($here, $away) {
        if ($here > $away) {
            $this->won++;
        } else if ($away > $here) {
            $this->lost++;
        } else {
            $this->drawn++;
        }
        $this->forHere += $here;
        $this->forAway += $away;
    }
}

if (file_exists("xml/tournament.xml")) {
    $tournamentXML = simplexml_load_file('xml/tournament.xml');
    $matches = $tournamentXML->xpath("//match");
    if (count($matches) > 0) {
        $teamNames = array();
        $teams = array();
        foreach ($matches as $match) {
            $teamName1 = (string) $match->team[0];
            if (!(in_array($teamName1, $teamNames))) {
                $teams[$teamName1] = new Team($teamName1);
                $teamNames[] = $teamName1;
            }
            $teamName2 = (string) $match->team[1];
            if (!(in_array($teamName2, $teamNames))) {
                $teams[$teamName2] = new Team($teamName2);
                $teamNames[] = $teamName2;
            }
        }

        foreach ($matches as $match) {
            if (isset($match->team[0]->attributes()[0]) && isset($match->team[1]->attributes()[0])) {
                $teams[(string) $match->team[0]]->giveResult(intval($match->team[0]->attributes()[0]), intval($match->team[1]->attributes()[0]));
                $teams[(string) $match->team[1]]->giveResult(intval($match->team[1]->attributes()[0]), intval($match->team[0]->attributes()[0]));
            }
        }

        uasort($teams, function($a, $b) {
            if ($a->getPoints() == $b->getPoints()) {
                if ($a->getDiff() > $b->getDiff()) {
                    return -1;
                } else {
                    return 1;
                }
            } else if ($a->getPoints() > $b->getPoints()) {
                return -1;
            } else {
                return 1;
            }
        });

        $rank = 1;
        echo "<table><thead><tr><th>Rank</th><th class='seperator'>Team</th><th>Played</th><th>Won</th><th class='seperator'>Drawn</th><th>Lost</th><th>For</th><th class='seperator'>Against</th><th>Diff.</th><th>Points</th></tr></thead><tbody>";
        foreach ($teams as $team_key=>$team) {
            echo "<tr><td>$rank</td><td class='seperator'>$team->name</td><td>".$team->getPlayed()."</td><td>$team->won</td><td class='seperator'>$team->drawn</td><td>$team->lost</td><td>$team->forHere</td><td class='seperator'>$team->forAway</td><td>".$team->getDiff()."</td><td>".$team->getPoints()."</td>";
            $rank++;
        }
        echo "</tbody></table>";
    } else {
        echo "<p>There are currently no rankings for this tournament.</p>";
    }
} else {
    echo "<p>There are currently no rankings for this tournament.</p>";
}

?>