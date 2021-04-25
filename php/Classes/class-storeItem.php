<?php

namespace mos_curios\StoreItem;

use Exception;
use Ramsey\Collection\Map\AssociativeArrayMap;
use DateTime;


class StoreItem
{
    public string $itemId;
    public string $itemName;
    public string $itemDescription;
    public float $itemPrice;
    public string $itemImage;
    public DateTime $itemDateAdded;

    /**
     * @param string $itemId
     * @param string $itemName
     * @param string $itemDescription
     * @param float $itemPrice
     * @param string $itemImage
     */
    public function __construct($itemId, string $itemName, string $itemDescription, float $itemPrice, string $itemImage, DateTime $itemDateAdded)
    {
        //TODO: Should I put the data sanitization in here? Or somewhere else?

        $this->itemId = $itemId;
        $this->itemName = $itemName;
        $this->itemDescription = $itemDescription;
        $this->itemPrice = $itemPrice;
        $this->itemImage = $itemImage;
        $this->$itemDateAdded = $itemDateAdded;
    }

    public function insertItem($db): void
    {
        // Check db connection and check if its busy
        if (!StoreItem::isConnected($db) && !pg_connection_busy($db)) {
            throw new Exception("There was a problem connecting to the database.");
        }

        // Create query
        $query = "INSERT INTO store_items VALUES('$this->itemId', '$this->itemName', '$this->itemDescription', '$this->itemPrice', '$this->itemImage');";

        // Send query
        pg_send_query($db, $query) or die("Couldn't insert item into database.");
    }

    /**
     * Deletes a store item based on its ID
     * 
     * @param $db A connection to the Postgres DB
     * @return void
     */
    public function delete($db): void
    {
        // Check db connection and check if its busy
        if (!StoreItem::isConnected($db) && !pg_connection_busy($db)) {
            throw new Exception("There was a problem connecting to the database.");
        }

        // Create query
        $query = "DELETE FROM store_items WHERE 'item_id' = $this->itemId";

        // Send query
        pg_send_query($db, $query) or die("There was a problem deleting the item with the id of: $this->itemId");
    }
    /**
     * Returns the item based on item name passed
     * 
     * @param $db PostgreSQL connection
     * @param string $name the name of the item
     */
    public static function getItemByName($db, string $name): StoreItem
    {
        // Check db connection
        if (!StoreItem::isConnected($db)) {
            throw new Exception("There was a problem connecting to the database.");
        }

        // Define the query
        $query = "SELECT * FROM store_items WHERE store_items.item_name = '$name'";
        pg_send_query($db, $query);

        // Get the results
        $result = pg_get_result($db);
        $rows = pg_fetch_row($result);

        // Parse results//

        // Change price to float
        $price = floatval($rows[3]);
        $item = new StoreItem($rows[0], $rows[1], $rows[2], $price, $rows[4], $rows[5]);

        return $item;
    }

    public static function getItemById($db, string $id): StoreItem
    {
        // Check db connection
        if (!StoreItem::isConnected($db)) {
            throw new Exception("There was a problem connecting to the database...");
        }

        $query = "SELECT * FROM store_items WHERE store_items.itemid = '$id'";
        pg_send_query($db, $query);

        $result = pg_get_result($db);

        $rows = pg_fetch_row($result);
        // Convert price string to float
        $price = floatval($rows[3]);
        $item = new StoreItem($rows[0], $rows[1], $rows[2], $rows[3], $rows[4], $rows[5]);

        return $item;
    }

    public static function getAllItems($db): array
    {
        // Check db connection and check if its busy
        if (!StoreItem::isConnected($db) && !pg_connection_busy($db)) {
            throw new Exception("There was a problem connecting to the database.");
        }

        // Define query
        $query = "SELECT * FROM store_items;";

        pg_send_query($db, $query);

        // Get all the items from store_items table
        $result = pg_get_result($db);
        $rows = pg_fetch_all($result);

        //Create array to store item objects
        $items = array();

        // Sort through array returned from Postgres
        foreach ($rows as $row) {
            // Create new store item objects for each store item in the database
            $item = new StoreItem($row['item_id'], $row['item_name'], $row['item_description'], $row['item_price'], $row['item_image'], $row['item_date_added']);

            // Add store items to array
            array_push($items, $item);
        }
        // Return store item array
        return $items;
    }

    /**
     * A function that returns true if a connection is detected or false if not
     * 
     * @param $db posgres db connection
     */
    public static function isConnected($db): bool
    {
        // Check db connection
        if (pg_connection_status($db) !== PGSQL_CONNECTION_OK) {
            return false;
        }

        // Return false if connection failed
        return true;
    }
}
