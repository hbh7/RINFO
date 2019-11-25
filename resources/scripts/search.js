$(document).ready(function () {

    console.log(document.getElementById("searchtextbox"));
    document.getElementById("searchtextbox").addEventListener("input",  function () {
        console.log("Searching");
        var searchText = document.getElementById("searchtextbox").value;
        console.log(searchText);

        if(searchText !== "") {
            $.getJSON("db.php?search=true&category=groups&searchtext=" + searchText, function (data) {
                console.log(data);
                var output = document.getElementById("results");
                while (output.firstChild) {
                    output.removeChild(output.firstChild);
                }
                data.forEach(function (d) {
                    var newelem = document.createElement("a");
                    newelem.href = "group.php?group_id=" + d["group_id"];
                    newelem.innerText = d["name"];
                    output.appendChild(newelem);
                    output.appendChild(document.createElement("br"));

                });
            });
        } else {
            var output = document.getElementById("results");
            while (output.firstChild) {
                output.removeChild(output.firstChild);
            }
        }
    });

});
