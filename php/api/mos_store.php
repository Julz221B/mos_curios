<?php
require_once(dirname(__DIR__, 1) . '/vendor/autoload.php');
require_once(dirname(__DIR__, 1) . '/Classes/class-storeItem.php');

use Ramsey\Uuid\Uuid;
use mos_curios\StoreItem\StoreItem;


header("Content-type: application/json");

// Create empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

// Connect to database
$pgConnection = pg_connect("host=localhost dbname=mos_curios user=postgres password=1234");

// TODO: Test database connection.

// If request method is set, get method type
if (isset($_SERVER["REQUEST_METHOD"])) {
    $httpMethod = $_SERVER["REQUEST_METHOD"];

    switch ($httpMethod) {
        case "GET":
            // Handle GET method
            StoreItem::getItemById($pgConnection, "e3ad9857-430c-4873-9ef5-2d1dc1e20148");

            break;
        case "POST":
            // Handle POST method
            $filename = $_FILES["itemImage"]["name"];
            $tempname = $_FILES["itemImage"]["tmp_name"];
            $folder = "img/" . $filename;
            $item = new StoreItem(Uuid::uuid4()->toString(), $_POST["itemName"], $_POST["itemDescription"], $_POST["itemPrice"], $filename, false);

            addItem($pgConnection, $item, $reply);

            move_uploaded_file($tempname, $folder);

            break;
        case "PUT":
            // Handle PUT method
            echo ("PUT");
            break;
        case "DELETE":
            // Handle DELETE method
            echo ("DELETE");
        case "OPTIONS":
            // Handle OPTIONS method
            break;
        default:
            throw new Exception("Unknown method " . $httpMethod);
    }
}

function addItem($db, StoreItem $item, $reply)
{
    // Check connection status
    if (pg_connection_busy($db)) {
        throw new Exception("Connection Busy!");
    }
    // DON'T FORGET THE SINGLE QUOTES!!!!!
    // Forgetting the single quotes will result in a SQL syntax error
    $query = "INSERT INTO store_items VALUES ('$item->itemId', '$item->itemName', '$item->itemDescription', '$item->itemPrice', '$item->onSale');";

    // Check if query ran successfully
    $dbq = pg_query($db, $query);
    if (! $dbq) {
        throw new Exception("Unable to execute query");
    }

    $reply->status = 200;
    $reply->data = "Store Item Added!";
}

echo (json_encode($reply));
