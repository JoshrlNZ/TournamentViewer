<?php
// Validation Functions
function checkMatchDate($date)
{
    $pattern = '/\d{1,2}-\d{1,2}-\d{4}/';
    return !preg_match($pattern, $date);
}

function isEmpty($str)
{
    return strlen(trim($str)) == 0;
}

function checkDays($team, $matches)
{
    $timesFound = 0;
    $numSearched = 0;
    foreach ($matches as $match) {
        if (isset($_POST['number']) && !($_POST['number'] - 1 == $numSearched)) {
            $dateString = $match->date->day . "-" . $match->date->month . "-" . $match->date->year;
            if (strcmp($dateString, $_POST['date']) == 0 && (strcmp($team, $match->team[0]) == 0 || strcmp($team, $match->team[1]) == 0)) {
                $timesFound++;
            }
        }
        $numSearched++;
    }
    return $timesFound == 0;
}

function checkVenue($venue, $matches)
{
    if (file_exists("xml/venues.xml")) {
        $venuesXML = simplexml_load_file('xml/venues.xml');
        $venues = $venuesXML->xpath("//venue");
        if (!in_array($venue, $venues)) {
            return false;
        }
        $timesFound = 0;
        $numSearched = 0;
        foreach ($matches as $match) {
            if (isset($_POST['number']) && !($_POST['number'] - 1 == $numSearched)) {
                $dateString = $match->date->day . "-" . $match->date->month . "-" . $match->date->year;
                if (strcmp($dateString, $_POST['date']) == 0 && strcmp($venue, $match->venue) == 0) {
                    $timesFound++;
                }
            }
            $numSearched++;
        }
        return $timesFound == 0;
    } else {
        return false;
    }
}

function checkScore($score)
{
    $pattern = '/^[-+]?\d+$/';
    return !preg_match($pattern, $score);
}

// Load XML and process POSTed elements
if ($_SERVER['REQUEST_METHOD'] === 'POST' && file_exists("xml/tournament.xml")) {
    $xml = simplexml_load_file('xml/tournament.xml');
    $matches = $xml->xpath("match");
    if (count($matches) > 0 &&
        isset($_POST['date']) &&
        isset($_POST['number']) &&
        isset($_POST['venue']) &&
        isset($_POST['playedCheck']) &&
        isset($_POST['score1']) &&
        isset($_POST['score2']) &&
        intval($_POST['number']) < $matches
    ) {
        $errors = 0;
        // Generating errors
        $dateCorrect = true;

        // Check date
        if (checkMatchDate($_POST['date'])) {
            $errors++;
            $dateCorrect = false;
        }

        // Check team names
        if ($dateCorrect && !checkDays($matches[intval($_POST['number']) - 1]->team[0], $matches)) {
            $errors++;
        }
        if ($dateCorrect && !checkDays($matches[intval($_POST['number']) - 1]->team[1], $matches)) {
            $errors++;
        }

        // Check venue
        if ($dateCorrect && !checkVenue($_POST['venue'], $matches)) {
            $errors++;
        }

        // Check scores
        if (strcmp($_POST['playedCheck'], "on") == 0) {
            if (checkScore($_POST['score1'])) {
                $errors++;
            }
            if (checkScore($_POST['score2'])) {
                $errors++;
            }
        }

        // Submit to XML if all is okay
        if ($errors == 0) {
            $editMatch = $xml->match[intval($_POST['number']) - 1];
            $newDate = array();
            preg_match('/(\d{1,2})-(\d{1,2})-(\d{4})/', $_POST['date'], $newDate);
            $editMatch->date->day = $newDate[1];
            $editMatch->date->month = $newDate[2];
            $editMatch->date->year = $newDate[3];
            $editMatch->venue = $_POST['venue'];
            if (isset($_POST['playedCheck']) && strcmp($_POST['playedCheck'], "on") == 0) {
                if (isset($editMatch->team[0]->attributes()['score'])) {
                    $editMatch->team[0]->attributes()['score'] = $_POST['score1'];
                    $editMatch->team[1]->attributes()['score'] = $_POST['score2'];
                } else {
                    $editMatch->team[0]->addAttribute('score', $_POST['score1']);
                    $editMatch->team[1]->addAttribute('score', $_POST['score2']);
                }
            } else {
                $teams = $editMatch->xpath('./team/@score');
                foreach ($teams as $team) {
                    unset($team[0]);
                }
            }
            // Sort the matches by date
            $xmlMatches = array();
            foreach ($xml->match as $arrayMatch) {
                $xmlMatches[] = $arrayMatch;
            }
            usort($xmlMatches, function ($a, $b) {
                $aYear = intval($a->date->year);
                $bYear = intval($b->date->year);
                $aMonth = intval($a->date->month);
                $bMonth = intval($b->date->month);
                $aDay = intval($a->date->day);
                $bDay = intval($b->date->day);
                return ($aYear > $bYear) ? 1 : ($aMonth > $bMonth) ? 1 : ($aDay > $bDay) ? 1 : 0;
            });
            $sortedXML = new SimpleXMLElement("<tournament></tournament>");
            foreach ($xmlMatches as $xmlMatch) {
                $sortMatch = $sortedXML->addChild('match');
                $sortMatch->addChild('date');
                $sortMatch->date->addChild('day', $xmlMatch->date->day);
                $sortMatch->date->addChild('month', $xmlMatch->date->month);
                $sortMatch->date->addChild('year', $xmlMatch->date->year);
                $sortMatch->addChild('venue', $xmlMatch->venue);
                $sortMatch->addChild('team', htmlentities($xmlMatch->team[0]));
                if (isset($xmlMatch->team[0]->attributes()['score'])) {
                    $sortMatch->team[0]->addAttribute('score', $xmlMatch->team[0]->attributes()['score']);
                }
                $sortMatch->addChild('team', htmlentities($xmlMatch->team[1]));
                if (isset($xmlMatch->team[1]->attributes()['score'])) {
                    $sortMatch->team[1]->addAttribute('score', $xmlMatch->team[1]->attributes()['score']);
                }
            }
            $sortedXML->saveXML('xml/tournament.xml');
            $xml = $sortedXML;
            echo "Your changes have been saved.";
        }
    }
}
?>
