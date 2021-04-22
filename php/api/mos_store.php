<?php
require_once(dirname(__DIR__, 1) . '/vendor/autoload.php');
require_once(dirname(__DIR__, 1) . '/Classes/class-storeItem.php');

use Ramsey\Uuid\Uuid;
use mos_curios\StoreItem\StoreItem;

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
            // Handl GET method
            echo (json_encode('{data: GET}'));
            break;
        case "POST":
            // Handle POST method
            $filename = $_FILES["itemImage"]["name"];
            $tempname = $_FILES["itemImage"]["tmp_name"];
            $folder = "img/" . $filename;
            $item = new StoreItem(Uuid::uuid4()->toString(), $_POST["itemName"], $_POST["itemDescription"], $_POST["itemPrice"], $filename, false);

            addItem($pgConnection, $item);

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

function addItem($db, StoreItem $item)
{
    // DON'T FORGET THE SINGLE QUOTES!!!!!
    // Forgetting the single quotes will result in a SQL syntax error
    $query = "INSERT INTO store_items VALUES ('$item->itemId', '$item->itemName', '$item->itemDescription', '$item->itemPrice', '$item->onSale');";

    $dataThing = pg_query($db, $query);
}
