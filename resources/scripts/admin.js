/* some functions for the admin page */

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~functions for admin messages tab~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

// this function removes the last 28 characters from a string (the exact length of "Edit Message Delete Message" +1 (for the newline))
function removeButtonText(str) {
    str = str.slice(0, -27);
    return str;
}

// adjust textarea height to fit content
function textAreaAdjust(elem) {
    elem.style.height = "1px";
    elem.style.height = (25+elem.scrollHeight)+"px";
}


//TODO: maybe add a transition? it looks kind of jarring imo
function makeEditMessage(elem) {
    var listElem = elem.parentElement;
    var listElemText = removeButtonText(listElem.innerText);
    listElem.innerHTML = "<textarea class='editAdminMessage' onfocus='textAreaAdjust(this)' style='overflow:hidden' name='message' maxlength='560' required>"+listElemText+"</textarea><br />\
                          <input class='submitButton editAdminMessageSubmit' type='button' name='submit' value='Submit Changes'>\
                          <input class='submitButton cancelEdit' type='button' name='submit' value='Cancel Edit'>";
    //TODO: write some php to accept this and post it to the database and then return what it just posted
    $(".editAdminMessageSubmit").on("click", function(event) {
        var listElem = event.target.parentElement;
        var textareatext = listElem.firstElementChild.value;
        $.ajax({
            url: 'somefile.php',
            type: 'POST',
            data: {'message': textareatext}
        }).done(function(result, status, xhr) {
            // I expect the server to throw back the string I just sent it, so that I can recreate the
            //  list element that was on the page before the editing started
            listElem.innerHTML = result + "<br /><button type='button' onclick='makeEditMessage(this);' class='submitButton' id='editMessage'>Edit Message</button><button type='button' onclick='deleteMessage(this);' class='submitButton' id='deleteMessage'>Delete Message</button>";
        }).fail(function(jqXHR) {
            alert("Something went wrong! Failed to edit message.\n" + jqXHR.status + ": " + jqXHR.statusText);
        });
    });
    $(".cancelEdit").on("click", function(event) {
        var listElem = event.target.parentElement;
        listElem.innerHTML = listElemText + "<br /><button type='button' onclick='makeEditMessage(this);' class='submitButton' id='editMessage'>Edit Message</button><button type='button' onclick='deleteMessage(this);' class='submitButton' id='deleteMessage'>Delete Message</button>";
    });
}

function deleteMessage(elem) {
    var listElem = elem.parentElement;
    var listElemText = removeButtonText(listElem.innerText);
    var proceedToDelete = confirm("Do you want to delete this message? (Can't be undone!)");
    if (proceedToDelete) {
        //delete the message from the database
        //TODO: make this actually work (redirect to a valid php file, write code to delete a message from the database)
        $.ajax({
            url: 'somefile.php',
            type: 'POST',
            data: {'key': 'value pair that matches an if statement in somefile.php to delete a message from db'}
        }).done(function(result, status, xhr) {
            listElem.innerHTML = "Message Deleted";
        }).fail(function(jqXHR) {
            alert("Something went wrong! Failed to delete message.\n" + jqXHR.status + ": " + jqXHR.statusText);
        });
    }
}


/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~End functions for admin messages~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
