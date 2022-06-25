<?php

require './app/bootstrap.php';

use DevCoder\DotEnv;
use DevCoder\SpreadsheetSnippets;

if (php_sapi_name() != 'cli') {
    throw new Exception('This application must be run on the command line.');
}

(new DotEnv(__DIR__ . '/.env'))->load();

$servername = getenv('DB_HOST');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$dbname = getenv('DB_DATABASE');

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // sql to create table cron_job
    $sql = "CREATE TABLE IF NOT EXISTS cron_job (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        last_working_cron_job TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)";

    // use exec() because no results are returned
    $conn->exec($sql);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$sqlCronJob = $conn->prepare("SELECT * FROM cron_job LIMIT 1");
$sqlCronJob->execute();
$sqlCronJob->setFetchMode(PDO::FETCH_ASSOC);
$oldCronJob = $sqlCronJob->fetch();

$resultColumns = [];
// Get data from users
if ($oldCronJob) {
    $dataCron = $oldCronJob['last_working_cron_job'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE updated_at > '" . $dataCron . "'");
} else {
    $stmt = $conn->prepare("SELECT * FROM users");
    $stm = $conn->prepare("SELECT * FROM users LIMIT 1");
    $stm->execute();
    $resultColumns = array_keys($stm->fetch(PDO::FETCH_ASSOC));
}

$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_NUM);

// if users table exist and $oldCronJob ka update anel isk ete chka insert anel
if ($oldCronJob) {
    $id = $oldCronJob['id'];
    $sqlCron = "UPDATE cron_job SET last_working_cron_job = CURRENT_TIMESTAMP WHERE id = $id";
} else {
    $sqlCron = "INSERT INTO cron_job VALUES ()";
}

// update or insert cron_job
$sqlCronData = $conn->prepare("$sqlCron");
$sqlCronData->execute();

// Google Sheet connect
$client = new Google\Client();
$client->setApplicationName('Google Sheets API PHP');
$client->setScopes('https://www.googleapis.com/auth/spreadsheets');
$client->setAuthConfig(__DIR__ . '/credentials.json');
$client->setAccessType('offline');
$client->setPrompt('select_account consent');

$service = new Google\Service\Sheets($client);
$spreadsheetId = getenv('SPREAD_SHEET_ID');
$valueInputOption = 'RAW';
$valuesUsers = $result;

$serviceSheet = new SpreadsheetSnippets($service);

if ($oldCronJob) { // update
    foreach ($valuesUsers as $key => $value) {
        $range = 'A' . $value[0] + 1;
        $serviceSheet->batchUpdateValues($spreadsheetId, $range, $valueInputOption, [$value]);
    }
} else { // append
    $range = "A1";
    $columnsUsers = [$resultColumns];
    $serviceSheet->appendValues($spreadsheetId, $range, $valueInputOption, $columnsUsers);
    $serviceSheet->appendValues($spreadsheetId, $range, $valueInputOption, $valuesUsers);
}

//$serviceSheet->appendValues($spreadsheetId, "A1:B1", $valueInputOption, $valuesUsers);

//$getColumns = $serviceSheet->getValues($spreadsheetId, 'A1:C1');