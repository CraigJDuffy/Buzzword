
Create Table Menu (
MenuID int(2) primary key,
DisplayName varchar(64) not null,
JSON TEXT not null
)ENGINE=INNODB;

Create Table MenuItem (
ItemID int(3) primary key,
SectionID int(3) not null,
LongName varchar(144) not null
ShortName varchar(32) not null,
Description TEXT not null,
Price Decimal(5,2) not null --An expensive bottle of wine, perhaps?
)ENGINE=INNODB;

Create Table Orders (
TableNumber int(2) not null,
OrderName varchar(32) not null,
TimeReceived TIME not null DEFAULT NOW(),
OrderChanged TIME not null DEFAULT NOW(),
EstimateChanged TIME,
ETA TIME,
Complete BOOLEAN not null DEFAULT FALSE,
primary key (TableNumber, OrderName)
)ENGINE=INNODB;

Create Table OrderItems (
OrderNumber int(2) not null,
OrderName varchar(32) not null,
ItemID int(3)not null,
Amount int(3) not null,
Details varchar(144) not null, --can be the empty string, but not null
GroupNumber int(2) not null DEFAULT 1,
ETA TIME,
Primary Key (TableNumber, OrderName, ItemID, Details),
foreign key (TableNumber, OrderName) references Orders (TableNumber, OrderName) ON DELETE CASCADE,
foreign key (ItemID) references MenuItem (ItemID)
)ENGINE=INNODB;