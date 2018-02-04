var total;
var itemID;
var price;

   function setitem(item) {
       alert("Setting item");
    price = item.Price;
    itemID = item.ItemID;
    total += price;
       alert("item set");
       setcookie();
   }

   function getcookie() {
    if (total == undefined) {
        alert("There is nothing in the shopping cart!");
    } else {
        var cookiearray = document.cookie.split(';');
        var toprint = "";
        for (var i = 0; i < cookiearray.length; ++i) {
            var pairArray = cookiearray[i].split('=');
            alert(pairArray[0]);
        }
        alert(toprint);
    }
   }

   function setcookie() {
       
        document.cookie = itemID + "=" + price + "; ";
        alert(price + " Product(s) with id " + itemID +
            " has been added to shopping cart!");
    } 