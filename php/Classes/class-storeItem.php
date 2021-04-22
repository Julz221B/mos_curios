<?php
namespace mos_curios\StoreItem;


/**
 * @param uuid $itemId
 * @param string $itemName
 * @param string $itemDescription
 * @param float $itemPrice
 * @param string $itemImage
 * @param bool $onSale
 */
class StoreItem {
    public $itemId;
    public $itemName;
    public $itemDescription;
    public $itemPrice;
    public $itemImage;
    public $onSale;

    /**
     * 
     */
    function __construct($itemId, string $itemName, string $itemDescription, float $itemPrice, $itemImage, ?bool $onSale) {
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
