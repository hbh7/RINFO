$(document).ready(function () {
    document.getElementById("my_groups").style.display = "none";
    document.getElementById("my_posts").style.display = "none";
});
function activity() {
    document.getElementById("hp_activities").style.display = "inherit";
    document.getElementById("my_groups").style.display = "none";
    document.getElementById("my_posts").style.display = "none";
}
function group() {
    document.getElementById("my_groups").style.display = "inherit";
    document.getElementById("hp_activities").style.display = "none";
    document.getElementById("my_posts").style.display = "none";
}
function post() {
    document.getElementById("my_posts").style.display = "inherit";
    document.getElementById("my_groups").style.display = "none";
    document.getElementById("hp_activities").style.display = "none";
}
