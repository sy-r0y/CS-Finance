/* Next we want to see if the email address the user entered is real. This means that the input data must contain at least an @ sign and a dot (.). Also, the @ must not be the first character of the email address, and the last dot must at least be one character after the @ sign. 

At first we check if the user entered anything at all in the email field. Next, we use regular expression and the test() method to check the field for a compliance. Also we will use trim() function that will trim leading whitespace off the string. This won’t be perfect validation — it is possible to slip not compliant addresses by it — but it's normally good enough.
*/


/*************/




function trim(s)
{
  return s.replace(/^\s+|\s+$/, '');
}

function validateEmail(fld) {
    var error="";
    var tfld = trim(fld.value);                        // value of field with whitespace trimmed off
    var emailFilter = /^[^@]+@[^@.]+\.[^@]*\w\w$/ ;
    var illegalChars= /[\(\)\<\>\,\;\:\\\"\[\]]/ ;
   
    if (fld.value == "") {
        fld.style.background = 'Yellow';
        error = "You didn't enter an email address.\n";
    } else if (!emailFilter.test(tfld)) {              //test email for illegal characters
        fld.style.background = 'Yellow';
        error = "Please enter a valid email address.\n";
    } else if (fld.value.match(illegalChars)) {
        fld.style.background = 'Yellow';
        error = "The email address contains illegal characters.\n";
    } else {
        fld.style.background = 'White';
    }
    return error;
}