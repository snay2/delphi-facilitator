<?php
$conn = mysql_connect('localhost', 'snay2_delphi', 'M8mu.5t8^hG)');
mysql_select_db('snay2_delphi');

$estimate_id = mysql_real_escape_string($_POST['estimate_id']);
$is_admin = mysql_real_escape_string($_POST['is_admin']);

if (isset($_POST['join_estimate'])) {
    // Join this estimate and show the form
    $estimate_id = mysql_real_escape_string($_POST['join_id']);
} else if (isset($_POST['new_estimate'])) {
    // Create a new estimate
    $estimate_id = rand(1000, 9999);
    echo "Your estimate ID is $estimate_id.<br />";
    createRound($estimate_id);
} else if (isset($_POST['new_round'])) {
    // Create a new round
    incrementRound($estimate_id);
} else if (isset($_POST['submit_estimate'])) {
    // Record the results for this estimate
    $low = mysql_real_escape_string($_POST['low_bound']);
    $low_unit = mysql_real_escape_string($_POST['low_bound_units']);
    $high = mysql_real_escape_string($_POST['high_bound']);
    $high_unit = mysql_real_escape_string($_POST['high_bound_units']);
    $round = getCurrentRound($estimate_id);
    addEstimate($estimate_id, $round, $low, $low_unit, $high, $high_unit);
    echo 'Your estimate has been submitted.';
} else if (isset($_POST['show_results'])) {
    // Get the results for this estimate and show them
    if ($_POST['other_round'] != '') {
        $round = mysql_real_escape_string($_POST['other_round']);
    } else {
        $round = getCurrentRound($estimate_id);
    }
    showResults($estimate_id, $round);
}

/**
 * Get the current round for the given estimate
 */
function getCurrentRound($estimate_id) {
    $query = "SELECT * FROM rounds WHERE estimate_id='$estimate_id' LIMIT 1;";
    $result = mysql_fetch_assoc(mysql_query($query));
    return $result['round'];
}

/**
 * Increment the round number for the given estimate
 */
function incrementRound($estimate_id) {
    $round = getCurrentRound($estimate_id) + 1;
    $query = "UPDATE rounds SET round='$round' WHERE estimate_id='$estimate_id';";
    mysql_query($query);
    echo "Now on round $round.";
}

/**
 * Creates a new estimate in the database
 */
function createRound($estimate_id) {
    $query = "INSERT INTO rounds (estimate_id) VALUES ('$estimate_id')";
    mysql_query($query);
}

/**
 * Submits an invidivual estimate for a given round
 */
function addEstimate($estimate_id, $round, $low, $low_unit, $high, $high_unit) {
    $query = "INSERT INTO estimates (estimate_id, round, low, low_unit, "
        ."high, high_unit) VALUES ('$estimate_id', '$round', '$low', "
        ."'$low_unit', '$high', '$high_unit');";
    mysql_query($query);
}

/**
 * Shows all the estimates for this round
 */
function showResults($estimate_id, $round) {
    $query = "SELECT * FROM estimates WHERE estimate_id='$estimate_id' AND "
        ."round='$round' ORDER BY low DESC;";
    $result = mysql_query($query);
    echo "<strong>Results for round $round:</strong><br />";
    while ($row = mysql_fetch_assoc($result)) {
        echo $row['low'].' '.$row['low_unit'].' -- '
            .$row['high'].' '.$row['high_unit'].'<br />';
    }
    echo '<hr />';
}
?>
<html>
<head>
    <meta name="viewport" content="width=320,user-scalable=false" />
</head>
<body>
    <header><h1>Delphi estimation</h1></header>
    <form action="index.php" method="POST">
        <button name="new_estimate">Create new estimate</button><br />
        <hr />
        Estimate ID: <input type="tel" name="estimate_id" value="<?php echo $estimate_id; ?>" /><br />
        Low bound: <input type="number" name="low_bound" />
        <select name="low_bound_units">
            <option value="h">H</option>
            <option value="d">D</option>
            <option value="w">W</option>
            <option value="m">M</option>
        </select><br />
        High bound: <input type="number" name="high_bound" />
        <select name="high_bound_units">
            <option value="h">H</option>
            <option value="d">D</option>
            <option value="w">W</option>
            <option value="m">M</option>
        </select><br />
        <input type="submit" value="Submit estimate" name="submit_estimate" />
        <hr />
        <button name="new_round">New round on this estimate</button><br />
        <button name="show_results">Show results for this round</button>
        or a different round: <input name="other_round" />
    </form>
</body>
</html>

