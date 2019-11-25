$(document).ready(function () {

    console.log(document.getElementById("searchtextbox"));
    document.getElementById("searchtextbox").addEventListener("input",  function () {
        console.log("Searching");
        var searchText = document.getElementById("searchtextbox").value;
        console.log(searchText);
        $.getJSON("db.php?search=true&category=groups&searchtext=" + searchText, function (data) {
            console.log(data);
        });
    });

});
