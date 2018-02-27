<?php
$scriptList = array();
include ("php/header.php");
?>
            <div id="content">
                <h2>Rankings</h2>
                <p>Here are the current team rankings for this tournament:</p>
                <div>
                <?php include("php/rankingTable.php"); ?>
                    </div>
            </div>
<?php include("php/footer.php") ?>