const PASSWORD_ERROR = "Password must be more than 6 characters, include at least 1 Upper case and at least 1 Lower case";
const NAME_ERROR = "Name should not be empty";
const EMAIL_ERROR = "Email should not be empty and should be a proper email";
const STUDENTID_ERROR = "Student ID should be 9 digits and should not be negative";

function isValidStudentId(studentId) {
    let temp = Number(studentId);
    if(temp) {
        return Math.abs(temp).toString().length === 9;
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
    return password.length > 6 && /^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?!.* ).{7,}$/.test(password);
}

// validate singup inputs
function validateSignupInputs(form) {
    let errorMessage = "";
    if (!isValidName(form.name.value)) {
        errorMessage += NAME_ERROR;
    } else if (!isValidStudentId(form.studentIdSignupBox.value)) {
        errorMessage += STUDENTID_ERROR;
    } else if (!isValidEmail(form.emailSignupBox.value)) {
        errorMessage += EMAIL_ERROR;
    } else if (!isValidPassword(form.passwordSignupBox.value)) {
        errorMessage += PASSWORD_ERROR;
    }
    if (errorMessage !== "") {
        alert(errorMessage);
    }
}

// validate login inputs
function validateLoginInputs(form) {
    let errorMessage = "";
    if(!isValidStudentId(form.studentIdLoginBox.value)) {
        errorMessage += STUDENTID_ERROR;
    } else if(!isValidPassword(form.passwordLoginBox.value)) {
        errorMessage += PASSWORD_ERROR;
    }
    if (errorMessage !== "") {
        alert(errorMessage);
    }
}

// validate lookup inputs
function validateLookupInputs(form) {
    let errorMessage = "";
    if(!isValidName(form.studentName.value)) {
        errorMessage += NAME_ERROR;
    } else if(!isValidStudentId(form.studentId.value)) {
        errorMessage += STUDENTID_ERROR;
    } 
    if (errorMessage !== "") {
        alert(errorMessage);
    }
}