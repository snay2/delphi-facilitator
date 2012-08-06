<?php
// Open the database connection
require_once('db.php');

$estimate_id = mysql_real_escape_string($_POST['estimate_id']);
$is_admin = mysql_real_escape_string($_POST['is_admin']);
?>

<html>
<head>
    <meta name="viewport" content="width=320,user-scalable=false" />
    <link rel="stylesheet" href="main.css" />
    <title>Delphi Estimation Facilitator</title>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
</head>
<body>
    <form action="index.php" method="POST">

<?php
if (isset($_POST['join_estimate'])) {
    // Join this estimate and show the form
    $estimate_id = mysql_real_escape_string($_POST['join_id']);
} else if (isset($_POST['new_estimate'])) {
    // Create a new estimate
    $estimate_id = rand(1000, 9999);
    $info = "Your estimate ID is $estimate_id.<br />";
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
    $info = 'Your estimate has been submitted.';
} else if (isset($_POST['show_results'])) {
    // Get the results for this estimate and show them
    $round = getCurrentRound($estimate_id);
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
    $info = "Now on round $round.";
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
 * Shows all the estimates for this round and all preceding rounds
 */
function showResults($estimate_id, $round) {
    if ($round < 1) return;
?>
    <h2>Results
        <span style="font-size: small"><a href="#" id="normalize" onclick="rotateNormalization();">Normalize (h)</a></span>
    </h2>
    <button type="submit" class="important" name="new_round">Start new round for this estimate</button>
    <hr />
    <div id="results">Loading results...</div>
    <script type="text/javascript" src="./results.js"></script>
    <script type="text/javascript">
        results = {};
        function gotResults(data) {
            results = data;
            displayResults(results, $("div#results"));
        }

        $(document).ready(function() {
            var script = document.createElement('script');
            script.type = 'text/javascript';
            script.src = "./results.php?estimateID=<?php echo $estimate_id; ?>&round=<?php echo $round; ?>&callback=gotResults";
            $("body").append(script);
        });
    </script>
<?php
} // showResults
?>

    <header><h2>Delphi Estimation</h2></header>

<?php
    if ($info) {
        echo '<div class="info">'.$info.'</div>';
    }
?>

        <button class="important" name="new_estimate">Create new estimate session</button><br />
        <hr />

        <label for="estimate_id">Estimate ID:</label>
        <input type="text" pattern="[0-9]*" class="num" name="estimate_id" id="estimate_id" value="<?php echo $estimate_id; ?>" /><br />

        <label for="low_bound">Low bound:</label>
        <input type="number" step="0.1" pattern="\d+(\.\d*)?" class="num" name="low_bound" id="low_bound"/>
        <select name="low_bound_units">
            <option value="h">H</option>
            <option value="d">D</option>
            <option value="w">W</option>
            <option value="m">M</option>
        </select><br />

        <label for="high_bound">High bound:</label>
        <input type="number" step="0.1" pattern="\d+(\.\d*)?" class="num" name="high_bound" id="high_bound" />
        <select name="high_bound_units">
            <option value="h">H</option>
            <option value="d">D</option>
            <option value="w">W</option>
            <option value="m">M</option>
        </select><br />

        <button type="submit" name="submit_estimate">Submit my estimate</button>
        <hr />
        <button name="show_results">Show all results</button>
    </form>
</body>
</html>

