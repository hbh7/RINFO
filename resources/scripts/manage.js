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

            var searchAreas = JSON.stringify(["user"]);

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
