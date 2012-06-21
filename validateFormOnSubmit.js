<script type="text/javascript">

/*function validateUsername(val) {
    var illegalChars = /[^a-zA-Z0-9_]/;
    ...
}

function validatePhone(val) {
    var illegalChars = /[^0-9().-]/; // No spaces allowed
    // Or
    var illegalChars = /[^0-9]/; // Only numbers no spaces or dashes or (area code)
    ...
}*/

/***********************/
/*function test() {
    throw new Error("Illegal Characters");
}

try
{
    test();
}
catch (e)
{
    alert(e); // Displays: "Error: Illegal Characters"
}*/


/***********************/




function validateFormOnSubmit(theForm) {

var reason = "";

  reason += validateUsername(theForm.first_name);
  reason += validateLastname(theForm.last_name);
  reason += validateEmail(theForm.email);
  reason += validatePhone(theForm.phone);      

  if (reason != "") {
    alert("Some fields need correction:\n" + reason);
    return false;
  }

}

function validateUsername(fld) {
    var error = "";
    var illegalChars = /\d/; // allow letters, numbers, and underscores

    if (fld.value == "") {
        fld.style.background = 'Yellow'; 
        error = "You didn't enter a first name.\n";
    } else if (illegalChars.test(fld.value)) {
        fld.style.background = 'Yellow'; 
        error = "The first name contains illegal characters.\n";
    } else {
        fld.style.background = 'White';
    }
    return error;
}
function validatePhone(fld) {
    var error = ""; 

    if (fld.value == "") {
        fld.style.background = 'Yellow'; 
        error = "You didn't enter a phone number.\n";
    } else {
        fld.style.background = 'White';
    }
    return error;
}
function validateLastname(fld) {
    var error = "";
    var illegalChars = /\d/; // allow letters, numbers, and underscores

    if (fld.value == "") {
        fld.style.background = 'Yellow'; 
        error = "You didn't enter a last name.\n";
    } else if (illegalChars.test(fld.value)) {
        fld.style.background = 'Yellow'; 
        error = "The last name contains illegal characters.\n";
    } else {
        fld.style.background = 'White';
    }
    return error;
}
function trim(s)
