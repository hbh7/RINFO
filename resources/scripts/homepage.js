$(document).ready(function () {
    var activity = document.getElementById("activity_tab");
    activity.getElementsByTagName("a")[0].classList.add("active");
    document.getElementById("my_groups").style.display = "none";
    document.getElementById("my_posts").style.display = "none";
});
function activity() {
    document.getElementById("hp_activities").style.display = "inherit";
    document.getElementById("my_groups").style.display = "none";
    document.getElementById("my_posts").style.display = "none";
    removeActive();
    var activity = document.getElementById("activity_tab");
    activity.getElementsByTagName("a")[0].classList.add("active");

}
function group() {
    document.getElementById("my_groups").style.display = "inherit";
    document.getElementById("hp_activities").style.display = "none";
    document.getElementById("my_posts").style.display = "none";
    removeActive();
    var group = document.getElementById("group_tab");
    group.getElementsByTagName("a")[0].classList.add("active");
}
function post() {
    document.getElementById("my_posts").style.display = "inherit";
    document.getElementById("my_groups").style.display = "none";
    document.getElementById("hp_activities").style.display = "none";
    removeActive();
    var post = document.getElementById("post_tab");
    post.getElementsByTagName("a")[0].classList.add("active");
}
function removeActive() {
    var display = document.getElementsByClassName("display")[0];
    display.getElementsByClassName("active")[0].classList.remove("active");
}

