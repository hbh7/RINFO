$(document).ready(function () {

    console.log(document.getElementById("searchtextbox"));
    document.getElementById("searchtextbox").addEventListener("input",  function () {

        clearResults();

        console.log("Searching...");
        var searchText = document.getElementById("searchtextbox").value;
        console.log(searchText);

        if(searchText !== "") {

            // Group Name Search
            console.log("Searching for group names...");
            $.getJSON("db.php?search=true&category=group_name&searchtext=" + searchText, function (data) {
                var output = document.getElementById("results");
                console.log(data);
                if(data.length > 0) {
                    var header = document.createElement("h3");
                    header.innerText = "Group names matching query:";
                    output.appendChild(header);
                    data.forEach(function (d) {
                        var newelem = document.createElement("a");
                        newelem.href = "group.php?group_id=" + d["group_id"];
                        newelem.innerText = d["name"];
                        output.appendChild(newelem);
                        output.appendChild(document.createElement("br"));
                    });
                }
            });

            // Group Tagline Search
            console.log("Searching for group taglines...");
            $.getJSON("db.php?search=true&category=group_tagline&searchtext=" + searchText, function (data) {
                var output = document.getElementById("results");
                console.log(data);
                if(data.length > 0) {
                    var header = document.createElement("h3");
                    header.innerText = "Group taglines matching query:";
                    output.appendChild(header);
                    data.forEach(function (d) {
                        var newelem = document.createElement("a");
                        newelem.href = "group.php?group_id=" + d["group_id"];
                        newelem.innerText = d["name"];
                        output.appendChild(newelem);
                        output.appendChild(document.createElement("br"));
                    });
                }
            });

            // Post Title Search
            console.log("Searching for post titles...");
            $.getJSON("db.php?search=true&category=post_title&searchtext=" + searchText, function (data) {
                var output = document.getElementById("results");
                console.log(data);
                if(data.length > 0) {
                    var header = document.createElement("h3");
                    header.innerText = "Post titles matching query:";
                    output.appendChild(header);
                    data.forEach(function (d) {
                        var newelem = document.createElement("a");
                        newelem.href = "group.php?group_id=" + d["group_id"];
                        newelem.innerText = d["name"];
                        output.appendChild(newelem);
                        output.appendChild(document.createElement("br"));
                    });
                }
            });

            // Post Text Search
            console.log("Searching for post body...");
            $.getJSON("db.php?search=true&category=post_body&searchtext=" + searchText, function (data) {
                var output = document.getElementById("results");
                console.log(data);
                if(data.length > 0) {
                    var header = document.createElement("h3");
                    header.innerText = "Post bodies matching query:";
                    output.appendChild(header);
                    data.forEach(function (d) {
                        var newelem = document.createElement("a");
                        newelem.href = "group.php?group_id=" + d["group_id"];
                        newelem.innerText = d["name"];
                        output.appendChild(newelem);
                        output.appendChild(document.createElement("br"));
                    });
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
