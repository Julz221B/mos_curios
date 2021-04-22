DROP TABLE IF EXISTS store_items;

CREATE TABLE store_items (
    item_id uuid UNIQUE NOT NULL,
    item_name text NOT NULL,
    item_description text NOT NULL,
    item_price float NOT NULL,
    item_image bytea NOT NULL,
    is_onsale bool
);