<?php
// Returns a JSON list of all the results for a given estimate and round
header('Content-Type: application/json');

// Open the database connection
require_once('db.php');

// Pull out the parameters
$estimate_id = mysql_real_escape_string($_GET['estimateID']);
$round = mysql_real_escape_string($_GET['round']);
if (isset($_GET['callback'])) {
    $callback = mysql_real_escape_string($_GET['callback']);
}

if ($round < 1) die('{}');

if ($callback) echo "$callback(";
echo '{"rounds": [';

$multiple_rounds = false;
for ($i = 1; $i <= $round; $i++) {
    $query = "SELECT * FROM estimates WHERE estimate_id='$estimate_id' AND "
        ."round='$i' ORDER BY low DESC;";
    $result = mysql_query($query);
    if ($multiple_rounds) echo ',';

    echo '{"id": '.$i.',';
    echo '"estimates": [';
    $multiple_estimates = false;
    while ($row = mysql_fetch_assoc($result)) {
        if ($multiple_estimates) echo ',';
        echo '{"low": ' . $row['low'] . ', ';
        echo '"low_unit": "' . $row['low_unit'] . '", ';
        echo '"high": ' . $row['high'] . ', ';
        echo '"high_unit": "' . $row['high_unit'] . '"}';
        $multiple_estimates = true;
    }
    echo ']}';
    $multiple_rounds = true;
}
echo ']}';
if ($callback) echo ');';

?>

