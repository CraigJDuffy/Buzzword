LOAD DATA LOCAL INFILE "MenuItemData.txt" INTO TABLE MenuItem IGNORE 1 LINES;

LOAD DATA LOCAL INFILE "MenuData.txt" INTO TABLE Menu IGNORE 1 LINES;

LOAD DATA LOCAL INFILE "OrderData.txt" INTO TABLE Orders IGNORE 1 LINES (OrderNumber, OrderName);

LOAD DATA LOCAL INFILE "OrderItemData.txt" INTO TABLE OrderItems IGNORE 1 LINES (OrderNumber, OrderName, ItemID, Amount, Details, GroupNumber);