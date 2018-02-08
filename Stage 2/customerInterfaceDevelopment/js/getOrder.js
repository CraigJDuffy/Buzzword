function getOrder() {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readystate == 4 && this.status == 200){
      console.log(this.responseText);
    }
    else if (this.readystate == 4 && (this.status == 500 || this.status == 400)){
      console.log("ERROR:" + this.status);
    }
  };

  var name = document.getElementById('orderName').value;
  var number = document.getElementById('tableNo').value;
xhttp.open("POST", "GetCustomerOrder.php", true);
xhttp.setRequestHeader("Content-type", "applicaton/x-ww-form-urlencoded");
xhttp.send("OrderName="+name + "&" + "OrderNumber=" + number);
var Data = xhttp.responseText;
console.log(Data);
}
