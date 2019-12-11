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

                if(data["group_name"].length > 0) {
                    var header = document.createElement("h3");
                    header.innerText = "Group names matching query:";
                    output.appendChild(header);
                    data["group_name"].forEach(function (d) {
                        var newelem = document.createElement("a");
                        newelem.href = "group.php?group_id=" + d["group_id"]; 
                        newelem.innerText = d["name"];
                        output.appendChild(newelem);
                        output.appendChild(document.createElement("br"));
                    });
                }

                if(data["group_tagline"].length > 0) {
                    var header = document.createElement("h3");
                    header.innerText = "Group taglines matching query:";
                    output.appendChild(header);
                    data["group_tagline"].forEach(function (d) {
                        var newelem = document.createElement("a");
                        newelem.href = "group.php?group_id=" + d["group_id"];
                        newelem.innerText = d["name"];
                        output.appendChild(newelem);
                        output.appendChild(document.createElement("br"));
                        var newelem2 = document.createElement("p");
                        newelem2.innerText = d["tagline"];
                        output.appendChild(newelem2);
                        output.appendChild(document.createElement("br"));
                    });
                }

                if(data["post_title"].length > 0) {
                    var header = document.createElement("h3");
                    header.innerText = "Post titles matching query:";
                    output.appendChild(header);
                    data["post_title"].forEach(function (d) {
                        var newelem = document.createElement("a");
                        if(d["group_id"] === 0) {
                            newelem.href = "user.php?user_id=" + d["user_id"];
                        } else {
                            newelem.href = "group.php?group_id=" + d["group_id"];
                        }
                        newelem.innerText = d["title"];
                        output.appendChild(newelem);
                        output.appendChild(document.createElement("br"));
                    });
                }

                if(data["post_body"].length > 0) {
                    var header = document.createElement("h3");
                    header.innerText = "Post bodies matching query:";
                    output.appendChild(header);
                    data["post_body"].forEach(function (d) {
                        var newelem = document.createElement("a");
                        if(d["group_id"] === 0) {
                            newelem.href = "user.php?user_id=" + d["user_id"];
                        } else {
                            newelem.href = "group.php?group_id=" + d["group_id"];
                        }
                        newelem.innerText = d["title"];
                        output.appendChild(newelem);
                        output.appendChild(document.createElement("br"));
                        var newelem2 = document.createElement("p");
                        newelem2.innerText = d["body"];
                        output.appendChild(newelem2);
                        output.appendChild(document.createElement("br"));
                    });
                }

                if(data["user"].length > 0) {
                    var header = document.createElement("h3");
                    header.innerText = "Users matching query:";
                    output.appendChild(header);
                    data["user"].forEach(function (d) {
                        var newelem = document.createElement("a");
                        newelem.href = "user.php?user_id=" + d["user_id"];
                        newelem.innerText = d["firstname"] + " " + d["lastname"] + " - " + d["username"];
                        output.appendChild(newelem);
                        output.appendChild(document.createElement("br"));
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
