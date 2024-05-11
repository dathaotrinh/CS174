function $(id) {
    return document.getElementById(id)
}

function isValidStudentId(studentId) {
    debugger
    let temp = Number(studentId);
    if(temp) {
        return temp = Math.abs(temp).toString().length === 9;
    }
    return false;
}

// email should not be empty
// should not start with a special character
// should have 1 @ and only 1 . after @
// should end with .{2to4chactacters} such as .com, .edu...
// should not end with .a, .b, .abcde
function isValidEmail(email) {
    return email.length > 0 && /^[a-zA-Z0-9]+[\w-\.]*@([\w-]+\.){1}[\w-]{2,4}$/.test(email);
}

// name should not be empty
function isValidName(name) {
    return name.length > 0;
}

// Password must be more than 6 characters, include at least 1 Upper case and at least 1 Lower case
function isValidPassword(password) {
    return password.length > 6 && /^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?!.* ).{6,}$/.test(password);
}

// validate singup inputs
function validateSignupInputs(form) {
    let errorMessage = "";
    if (!isValidName(form.name.value)) {
        errorMessage += "Name should not be empty";
    } else if (!isValidStudentId(form.studentIdSignupBox.value)) {
        errorMessage += "Student ID should be 9 digits and should not be negative";
    } else if (!isValidEmail(form.emailSignupBox.value)) {
        errorMessage += "Email should not be empty and should be a proper email";
    } else if (!isValidPassword(form.passwordSignupBox.value)) {
        errorMessage += "Password must be more than 6 characters, include at least 1 Upper case and at least 1 Lower case";
    }
    if (errorMessage !== "") {
        alert(errorMessage);
    }
}

// login fields should not be empty
function validateLoginInputs(form) {
    if (form.studentIdLoginBox.value.length === 0 || form.passwordLoginBox.value.length === 0) {
        alert("Login fields should not be empty");
    }
}

// Lookup fields should not be empty
function validateLookupInputs(form) {
    if (form.studentName.value.length === 0 || form.studentId.value.length === 0) {
        alert("Lookup fields should not be empty");
    }
}