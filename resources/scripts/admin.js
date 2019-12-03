/* some functions for the admin page */

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~functions for Top button~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
// check if viewport is more than 300 pixels away from the top

var toTopBtn = document.getElementById("toTop");
window.onscroll = function() {checkLocation()};

function checkLocation() {
    //TODO: fade animations not currently working, but that's last on my list of things to do
    if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
        /*toTopBtn.style.opacity = "0";*/
        toTopBtn.style.display = "block";
        /*$("#toTop").fadeIn(200);*/
    } else {
        /*$("#toTop").fadeOut(200, function() {*/
            toTopBtn.style.display = "none";
        /*});*/
    }
}

// go to the top of the page when the button is clicked
function toTop() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~end functions for Top button~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/


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


//TODO: make the ajax request work
function submitMessage(elem) {
    //var divParentElem = elem.parentElement;
    var textareatext = document.getElementById("postAdminMessage").firstElementChild.value;
    if (textareatext.length <= 0 || textareatext.length > 560) {
        alert("Please keep messages between 1 and 560 characters");
        return;
    }
    $.ajax({
        url: 'somefile.php',
        type: 'POST',
        data: {'message': textareatext}
    }).always(function(result, status, xhr) {
        var newMessage = "<li>" + textareatext + "</li>";
        $("#admin_messages").prepend(newMessage);
        document.getElementById("postAdminMessage").firstElementChild.value = "";
    }).fail(function(jqXHR) {
        alert("Something went wrong! Failed to submit message.\n" + jqXHR.status + ": " + jqXHR.statusText);
    });
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
            // I expect the server to throw back the string I just sent it (sanitized please!!!), so that I can recreate the
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




/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~Functions for user permissions~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

/* this is a simplified/slightly modified version of the code that powers search.php */

$(document).ready(function () {

    document.getElementById("searchtextbox").addEventListener("input",  function () {

        clearResults();

        //console.log("Searching...");
        var searchText = document.getElementById("searchtextbox").value;
        //console.log(searchText);

        if(searchText !== "") {

            var searchAreas = JSON.stringify(["group_name", "group_tagline", "post_title", "post_body", "user"]);

            $.getJSON("db.php?searchtext=" + searchText + "&searchareas=" + searchAreas, function (data) {

                var output = document.getElementById("results");
                console.log(data);

                if(data["user"].length > 0) {
                    var header = document.createElement("h3");
                    header.innerText = "Users matching query:";
                    output.appendChild(header);
                    data["user"].forEach(function (d) {
                        var container = document.createElement("div");
                        container.classList.add("userOptions");
                        var newelem = document.createElement("h6");
                        newelem.innerText = d["firstname"] + " " + d["lastname"] + " - " + d["username"];
                        container.appendChild(newelem);

                        /* create buttons for actions */
                        newelem = document.createElement("button");
                        newelem.type = "button";
                        newelem.class = "userPermissionBtn";
                        newelem.innerText = "Promote User";
                        newelem.onclick = "";
                        container.appendChild(newelem);

                        newelem = document.createElement("button");
                        newelem.type = "button";
                        newelem.class = "userPermissionBtn";
                        newelem.innerText = "Demote User";
                        newelem.onclick = "";
                        container.appendChild(newelem);

                        newelem = document.createElement("button");
                        newelem.type = "button";
                        newelem.class = "userPermissionBtn";
                        newelem.innerText = "Ban User";
                        newelem.onclick = "";
                        container.appendChild(newelem);

                        newelem = document.createElement("button");
                        newelem.type = "button";
                        newelem.class = "userPermissionBtn";
                        newelem.innerText = "Delete User";
                        newelem.onclick = "";
                        container.appendChild(newelem);

                        output.appendChild(container);
                    });
                }

                if(output.childElementCount === 0) {
                    var header = document.createElement("h3");
                    header.innerText = "No results found";
                    output.appendChild(header);
                }
            });
        }
    });

});

function clearResults() {
    var output = document.getElementById("results");
    while (output.firstChild) {
        output.removeChild(output.firstChild);
    }
}

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~End functions for user permissions~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
