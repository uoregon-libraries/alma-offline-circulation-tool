/*if (document.addEventListener) {
  document.addEventListener("DOMContentLoaded", init, false);
}*/

window.onload = init;

// This is a regular expression to pull the first set of numbers out of a patron barcode.
var PBCODE_REG_EXP = new RegExp(/\d+/);

// These are global so I can refer to the same thing across multiple functions, but are not meant to be constant. In the future, if I can figure out how to pass these along by reference, I will probably switch to doing that instead.
var form = null;
var formArray = null;
var formArray2 = null;



function init()
{
  // Keeps track of the form on the page.
  form = document.mainform;

  // These two arrays are just to let me do some for loops on the form elements. Since I was getting tired of adding a new if-else and looking at all of them, keeping these arrays here feels just a little bit easier.
  formArray = {
    library: {
      value: "",
      error: "---",
      item: form.library,
      string: "Please select your Library\n"
      },
    ibcode: {
      value: "",
      item: form.ibcode,
      error: "",
      string: "Please provide a valid Item Barcode in Checkout\n"
      },
    icallnum: {
      value: "",
      item: form.icallnum,
      error: "",
      string: "Please provide a valid Item Call Number in Checkout\n"
      },
    pbcode: {
      value: "",
      item: form.pbcode,
      error: "",
      string: "Please provide a valid Patron Barcode or ID in Checkout\n"
      },
    pname: {
      value: "",
      item: form.pname,
      error: "",
      string: "Please provide a valid Patron Name in Checkout\n"
      }
    };

  formArray2 = {
    library: {
      value: "",
      error: "---",
      item: form.library,
      string: "Please select your Library\n"
      },
    ibcode2: {
      value: "",
      item: form.ibcode2,
      error: "",
      string: "Please provide a valid Item Barcode in Checkin\n"
      }/*,
    icallnum2: {  // Not sure how to add this but not check this item, so I am omitting it until it proves necessary.
      value: "",
      item: form.icallnum2,
      error: "",
      string: "Please provide a valid Item Call Number in Checkin\n"
      },*/
    };

  // Checkout and Checkin have similar functions, but are different because of the form elements they interact with.
  form.checkoutbutton.addEventListener("click", checkout, false);
  form.checkinbutton.addEventListener("click", checkin, false);

  // This prevents the [Enter] key from submitting, because barcode scanners end their input with an [Enter] key equivalent.
  document.onkeypress = stopRKey;
}



function checkout()
{
  var now = rightNow();
  var string = "";
  var error = false;
  var errorText = "";

  for (var i in formArray)
  {
    formArray[i]["value"] = formArray[i]["item"].value.replace(",","");
  }

  if (PBCODE_REG_EXP.exec(formArray["pbcode"]["value"]))
    formArray["pbcode"]["value"] = PBCODE_REG_EXP.exec(formArray["pbcode"]["value"])[0];
  else
    formArray["pbcode"]["value"] = "";

  for (var i in formArray)
  {
    if (!checkValidity(formArray[i]["value"], formArray[i]["error"], formArray[i]["item"]))
    {
      errorText += formArray[i]["string"];
      error = true;
    }
  }

  if (error)
  {
    alert(errorText);
    return false;
  }

  string += formArray["library"]["value"] + "," + now + ',L,' + formArray["ibcode"]["value"] + "," + formArray["icallnum"]["value"] + "," + formArray["pbcode"]["value"] + "," + formArray["pname"]["value"] + "\n";

  form.textarea.value += string;

  form.ibcode.value = "";
  form.icallnum.value = "";

  return false;
}



function checkin()
{
  var now = rightNow();
  var string = "";
  var error = false;
  var errorText = "";

  var icallnum2 = form.icallnum2.value.replace(",","");

  for (var i in formArray2)
  {
    formArray2[i]["value"] = formArray2[i]["item"].value.replace(",","");
  }

  for (var i in formArray2)
  {
    if (!checkValidity(formArray2[i]["value"], formArray2[i]["error"], formArray2[i]["item"]))
    {
      errorText += formArray2[i]["string"];
      error = true;
    }
  }

  if (error)
  {
    alert(errorText);
    return false;
  }

  string += formArray2["library"]["value"] + "," + now + ',R,' + formArray2["ibcode2"]["value"] + "," + icallnum2 + ",,\n";

  form.textarea.value += string;

  form.ibcode2.value = "";
  form.icallnum2.value = "";

  return false;
}



function stopRKey(evt, focus)
{
  var evt = (evt) ? evt : ((event) ? event : null);
  var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);

  if ((evt.keyCode == 13) && (node.type=="text"))
  {
    /*var thisElement = node.id;
    var x = 0;

    for (key in formItems)
      if (formItems[key] == thisElement)
        x = key;

    x++;
    if (x > formItems.length)
      x = 0;

    document.getElementById(formItems[index]).focus();*/

    return false;
  }
}



function rightNow()
{
  var current = new Date();
  var year = "" + current.getFullYear(),
    month = ((current.getMonth() + 1) < 10) ? "0" + (current.getMonth() + 1) : "" + (current.getMonth() + 1),
    day = (current.getDate() < 10) ? "0" + current.getDate() : "" + current.getDate(),
    hour = (current.getHours() < 10) ? "0" + current.getHours() : "" + current.getHours(),
    minute = (current.getMinutes() < 10) ? "0" + current.getMinutes() : "" + current.getMinutes(),
    second = (current.getSeconds() < 10) ? "0" + current.getSeconds() : "" + current.getSeconds();

  return year + month + day + hour + minute + second;
}



function checkValidity(text, wrong, item)
{
  if (text == wrong)
  {
    item.style.background = "#FFDFDF";
    item.style.border = "solid #BF0000";
    item.style.boxShadow = "0 0 .5em #FF8080";
    item.style.fontWeight = "bold";
    return false;
  }
  else
  {
    item.style.background = "";
    item.style.border = "";
    item.style.boxShadow = "";
    item.style.fontWeight = "";
    return true;
  }
}
