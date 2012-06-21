/* The function below checks the password field for blankness and allow only letters and numbers - no underscopes this time. So we should use a new regular expression to forbid underscopes. This one /[\W_]/ allow only letters and numbers. Next, we want to permit only passwords that contain letters and at least one numeral. For that we use the seacrh() method and two more regular expressions: /(a-z)+/ and /(0-9)/.
*/

/**************/

function validatePassword(fld) {
    var error = "";
    var illegalChars = /[\W_]/; // allow only letters and numbers 
 
    if (fld.value == "") {
        fld.style.background = 'Yellow';
        error = "You didn't enter a password.\n";
    } else if ((fld.value.length < 7) || (fld.value.length > 15)) {
        error = "The password is the wrong length. \n";
        fld.style.background = 'Yellow';
    } else if (illegalChars.test(fld.value)) {
        error = "The password contains illegal characters.\n";
        fld.style.background = 'Yellow';
    } else if (!((fld.value.search(/(a-z)+/)) && (fld.value.search(/(0-9)+/)))) {
        error = "The password must contain at least one numeral.\n";
        fld.style.background = 'Yellow';
    } else {
        fld.style.background = 'White';
    }
   return error;
}   