var total = 0;
var itemID;
var price;


function product(code,price,desc,quan)
{ this.price = 0
  this.code = code
  this.price = price
  this.desc = desc
  this.quan = quan
 return this;
}

function additem(codes,prices,descrip)
{
// adds another item to a variable length array
// remember to do it via new product()
// line 61
loc = check_if_in(codes)
// present_item = item_num
//last_item = item_num;
//alert('item_num = ' + item_num)

if (loc != -1){
  // update existing item
  olditem =  itemlist[loc].quan
  //alert(' loc is before oldvalue ' + loc);
  //alert('olditem is ' + olditem);
  itemlist[loc] = new product(codes,prices,descrip,olditem + 1)}
    else // new item
    {olditem =  itemlist[item_num].quan
    itemlist[item_num] = new product(codes,prices,descrip,olditem + 1);
    items_ordered = item_num
    item_num = item_num + 1
     }
    remove_nil_items(itemlist)
     
   
 }

function write_to_field(code)
{
 var found = false;
 var i =0;
 while ((found == false) && (i < document.form1.elements.length))
  {i = i + 1
   if (document.form1.elements[i].name == code)
     { 
      found = true;
      document.form1.elements[i].value = parent.item_quan(code);
     }
  }
}

function Loc_additem(code, price)
{
     alert("Setting item " + code + " price " + price);
    document.cookie = "ItemID" + total + "=" + code + ";";
    document.cookie = "Price" + total + "=" + price + ";";
    total++;
    updateNumber();
 //self.parent.additem(code,price,desc);
   // alert("item set");
// write_to_field(code);
  //  alert("item set again");
}


   function setitem(item) {
       alert("Setting item");
    price = item.Price;
    itemID = item.ItemID;
    total += price;
       alert("item set");
       setcookie();
   }

   function getcookie() {
    /*if (total == undefined) {
        alert("There is nothing in the shopping cart!");
    } else {*/
        var cookiearray = document.cookie.split(';');
        var toprint = "";
       alert("cookies: " + cookiearray.length);
        for (var i = 0; i < cookiearray.length; ++i) {
            var pairArray = cookiearray[i].split('=');
            alert(pairArray[1]);
        }
        alert(toprint);
   // }
   }

function getCookieByName(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function deletecookie() {
    alert("Deleting cookies");
    //for (var i = 0; i < total; i++) {
        document.cookie = "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        //document.cookie = "Price" + i + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    //}
    total = 0;
}

function deleteAllCookies() {
    var cookies = document.cookie.split(";");

    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        var eqPos = cookie.indexOf("=");
        var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
    }
}

function getOrder() {
    var ord = [];
    var cookiearray = document.cookie.split(';');
        for (var i = 0; i < cookiearray.length; i = i + 2) {
            var pairArray = cookiearray[i].split('=');
            ord = ord + "<tr><td>" + pairArray[1] + "</td></tr>";
        }
    return ord;
}

function updateTotal() {
   var tot = 0;
    var cookiearray = document.cookie.split(';');
        for (var i = 1; i < cookiearray.length; i = i + 2) {
            var pairArray = cookiearray[i].split('=');
            tot = tot + parseFloat(pairArray[1]);
        }
    return tot.toFixed(2);
}

function updateNumber() {
    document.getElementById("number").innerHTML = "No. of Items: " + total;
}

   function setcookie() {
       
        document.cookie = itemID + "=" + price + "; ";
        alert(price + " Product(s) with id " + itemID +
            " has been added to shopping cart!");
    } 