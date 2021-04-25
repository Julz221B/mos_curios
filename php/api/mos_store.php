<?php
require_once(dirname(__DIR__, 1) . '/vendor/autoload.php');
require_once(dirname(__DIR__, 1) . '/Classes/class-storeItem.php');

use Ramsey\Uuid\Uuid;
use mos_curios\StoreItem\StoreItem;

// Create empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

// Start PHP session
session_start();

// Connect to database
$pgConnection = pg_connect("host=localhost dbname=mos_curios user=postgres password=1234");

// TODO: Test database connection.

// If request method is set, get method type
if (isset($_SERVER["REQUEST_METHOD"])) {
    $httpMethod = $_SERVER["REQUEST_METHOD"];

    switch ($httpMethod) {
        case "GET":
            if (sizeof($_GET) > 0) {
                if (array_key_exists("itemId", $_GET)) {
                    $item = StoreItem::getItemById($pgConnection, $_GET["itemId"]);
                    $reply->data = $item;
                    break;
                } else if (array_key_exists("itemName", $_GET)) {
                    $item = StoreItem::getItemByName($pgConnection, $_GET["itemName"]);
                    $reply->data = $item;
                    break;
                }
            }
            $items = StoreItem::getAllItems($pgConnection);
            $reply->data = $items;
            break;
        case "POST":
            if (! isset($_FILES["itemImage"])) {
                throw new Exception("No image found for new Item, an Item image is required");
            }

            // Get image file name
            $imgFileName = $_FILES["itemImage"]["name"];

            // Define upload path
            $imgFilePath = dirname(__DIR__, 2).'/assets/images';

            // Concat path and filename to make full path
            $imgFullPath = $imgFilePath.'/'.$imgFileName;
            move_uploaded_file($_FILES["itemImage"]["tmp_name"], $imgFullPath);

            // Create a DateTime object to store date this store item was created
            $dateCreated = new DateTime();

            $itemToAdd = new StoreItem(Uuid::uuid4()->toString(), $_POST["itemName"], $_POST["itemDescription"], $_POST["itemPrice"], $imgFullPath, $dateCreated);

            $itemToAdd->insertItem($pgConnection);

            $reply->data = "Store Item Added!";

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

header("Content-type: application/json");
echo (json_encode($reply));
