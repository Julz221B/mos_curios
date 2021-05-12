<?php
require_once(dirname(__DIR__, 1) . '/vendor/autoload.php');
require_once(dirname(__DIR__, 1) . '/Classes/class-storeItem.php');

use Ramsey\Uuid\Uuid;
use mos_curios\StoreItem\StoreItem;
use Pear\Validate;

// Create empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

// Start PHP session
session_start();

// Connect to database
$pgConnection = pg_connect("host=localhost dbname=mos_curios user=postgres password=1234");

// TODO: Test database connection.
if (! StoreItem::isConnected($pgConnection)) {
    throw new Exception("Couldn't connect to the database...");
}

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

            // Check file upload type
            if ($_FILES['itemImage']['type'] !== 'image/jpeg' && $_FILES['itemImage']['type'] !== 'image/png') {
                $reply->data = trim($_FILES['itemImage']['name']) . " is not a JPEG or PNG.";
                $reply->status = 500;

                // Break due to bad upload
                break;
            }

            // Get image file name
            $imgFileName = $_FILES["itemImage"]["name"];

            // Remove spaces from filename
            $imgFileName = preg_replace("/\s+/", "", $imgFileName);

            // Define upload path
            $imgFilePath = dirname(__DIR__, 2).'/assets/images';

            // Concat path and filename to make full path
            $imgFullPath = $imgFilePath.'/'.$imgFileName;
            move_uploaded_file($_FILES["itemImage"]["tmp_name"], $imgFullPath);

            $itemToAdd = new StoreItem(Uuid::uuid4(), trim($_POST["itemName"]), trim($_POST["itemDescription"]), $_POST["itemPrice"], $imgFullPath);

            $itemToAdd->insertItem($pgConnection);

            // Set reply
            $reply->data = "Store Item Added!";
            $reply->status = 200;

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
