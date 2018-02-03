function getOrder() {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystate = function() {
    if (this.readystate == 4 && this.status == 200){
      console.log(this.responseText);
    }
    else if (this.readystate == 4 && this.status = 500 || this.status = 400){
      console.log("ERROR:" + this.status);
    }
  };
xhttp.open("POST", "GetCustomerOrder.php", true);
xhttp.sentRequestHeader±("Content-type", "applicaton/x-ww-form-urlencoded");
xhttp.send("OrderName=")
}
