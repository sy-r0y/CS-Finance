/* The function below checks if the user entered anything at all in the username field. If it’s not blank, we check the length of the string and permit only usernames that are between 5 and 15 characters. Next, we use the JavaScript regular expression /\W/ to forbid illegal characters from appearing in usernames. We want to allow only letters, numbers and underscopes.
*/


/***********/


function validateUsername(fld) {
    var error = "";
    var illegalChars = /\W/; // allow letters, numbers, and underscores
 
    if (fld.value == "") {
        fld.style.background = 'Yellow'; 
        error = "You didn't enter a username.\n";
    } else if ((fld.value.length < 5) || (fld.value.length > 15)) {
        fld.style.background = 'Yellow'; 
        error = "The username is the wrong length.\n";
    } else if (illegalChars.test(fld.value)) {
        fld.style.background = 'Yellow'; 
        error = "The username contains illegal characters.\n";
    } else {
        fld.style.background = 'White';
    }
    return error;
}