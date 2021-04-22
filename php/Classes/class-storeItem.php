<?php

namespace mos_curios\StoreItem;

use Exception;

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


    public static function getItemById($db, string $id)
    {
        // Check db connection
        if (pg_connection_status($db) !== PGSQL_CONNECTION_OK) {
            throw new Exception("There was a problem connecting to the database...");
        }
        $query = "SELECT * FROM store_items WHERE store_items.item_id = '$id'";
        pg_send_query($db, $query);

        $result = pg_get_result($db);

        var_dump(pg_fetch_row($result));
    }

    /**
     * 
     */
    public function __construct($itemId, string $itemName, string $itemDescription, float $itemPrice, $itemImage, ?bool $onSale)
    {
        /*
        $this->setItemId($itemId);
        $this->setItemName($itemName);
        $this->setItemDescription($itemDescription);
        $this->setItemPrice($itemPrice);
        $this->setItemImage($itemImage);
        $this->setItemSaleState($onSale);
        */
        $this->itemId = $itemId;
        $this->itemName = $itemName;
        $this->itemDescription = $itemDescription;
        $this->itemPrice = $itemPrice;
        $this->itemImage = $itemImage;
        $this->onSale = $onSale;
    }
}
