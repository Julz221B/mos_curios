<?php

namespace mos_curios\StoreItem;

use Exception;
use PDO;

/**
 * @param uuid $itemId
 * @param string $itemName
 * @param string $itemDescription
 * @param float $itemPrice
 * @param string $itemImage
 * @param bool $onSale
 */
class StoreItem
{
    public $itemId;
    public $itemName;
    public $itemDescription;
    public $itemPrice;
    public $itemImage;
    public $onSale;

    public static function getItemByName($db, string $name) {
        // Check db connection
        if (! StoreItem::isConnected($db)) {
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
        if (! StoreItem::isConnected($db)) {
            throw new Exception("There was a problem connecting to the database...");
        }
        $query = "SELECT * FROM store_items WHERE store_items.item_id = '$id'";
        pg_send_query($db, $query);

        $result = pg_get_result($db);

        $rows = pg_fetch_row($result);
        // Convert price string to float
        $price = floatval($rows[3]);
        $item = new StoreItem($rows[0], $rows[1], $rows[2], $rows[3], $rows[4], $rows[5]);

        return $item;
    }

    public static function isConnected($db): bool {
        // Check db connection
        if (pg_connection_status($db) !== PGSQL_CONNECTION_OK) {
            return false;
        }

        // Return false if connection failed
        return true;
    }

    /**
     * 
     */
    public function __construct($itemId, string $itemName, string $itemDescription, float $itemPrice, $itemImage, ?bool $onSale)
    {
        $this->itemId = $itemId;
        $this->itemName = $itemName;
        $this->itemDescription = $itemDescription;
        $this->itemPrice = $itemPrice;
        $this->itemImage = $itemImage;
        $this->onSale = $onSale;
    }
}
