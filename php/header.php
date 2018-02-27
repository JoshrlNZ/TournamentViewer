<!DOCTYPE html>
<html lang="en">
<head>
    <!--jlieshout index.html-->
    <meta charset="UTF-8">
    <title>Live Tournaments - Admin Tools</title>
    <link rel="stylesheet" href="style.css">
    <?php
    $currentPage = basename($_SERVER['PHP_SELF']);
    foreach ($scriptList as $script) {
        echo "<script src='$script'></script>";
    }
    ?>
</head>
<body>
<div id="maincontent">
    <h1>Live Tournaments</h1>
    <nav>
        <p>Welcome <b>admin</b>. ⚬ <a href="adminEdit.php">Edit Matches</a> ⚬ <a href="adminCreate.php">Create Tournament</a></p>
        <ul>
            <li><a <?php echo $currentPage === 'index.php' ? "class='current-page'" : ""?> href="index.php">Home</a></li>
            <li><a <?php echo $currentPage === 'rankings.php' ? "class='current-page'" : ""?> href="rankings.php">Rankings</a></li>
            <li><a <?php echo $currentPage === 'matches.php' ? "class='current-page'" : ""?> href="matches.php">Matches</a></li>
        </ul>
    </nav>