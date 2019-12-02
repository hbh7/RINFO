$(document).ready(function () {
    var activity = document.getElementById("activity_tab");
    activity.getElementsByTagName("a")[0].classList.add("active");
    document.getElementById("my_groups").style.display = "none";
    document.getElementById("my_posts").style.display = "none";
});
function activity() {
    document.getElementById("activity_t").style.backgroundColor = "#E95151";
    document.getElementById("activity_t").style.color = "#FFFFFF";
    document.getElementById("group_t").style.backgroundColor = "#F3F3F3";
    document.getElementById("group_t").style.color = "#666666";
    document.getElementById("post_t").style.backgroundColor = "#F3F3F3";
    document.getElementById("post_t").style.color = "#666666";
    document.getElementById("hp_activities").style.display = "inherit";
    document.getElementById("my_groups").style.display = "none";
    document.getElementById("my_posts").style.display = "none";
    removeActive();
    var activity = document.getElementById("activity_tab");
    activity.getElementsByTagName("a")[0].classList.add("active");

}
function group() {
    document.getElementById("activity_t").style.backgroundColor = "#F3F3F3";
    document.getElementById("activity_t").style.color = "#666666";
    document.getElementById("group_t").style.backgroundColor = "#E95151";
    document.getElementById("group_t").style.color = "#FFFFFF";
    document.getElementById("post_t").style.backgroundColor = "#F3F3F3";
    document.getElementById("post_t").style.color = "#666666";
    document.getElementById("my_groups").style.display = "inherit";
    document.getElementById("hp_activities").style.display = "none";
    document.getElementById("my_posts").style.display = "none";
    removeActive();
    var group = document.getElementById("group_tab");
    group.getElementsByTagName("a")[0].classList.add("active");
}
function post() {
    document.getElementById("activity_t").style.backgroundColor = "F3F3F3";
    document.getElementById("activity_t").style.color = "#666666";
    document.getElementById("group_t").style.backgroundColor = "#F3F3F3";
    document.getElementById("group_t").style.color = "#666666";
    document.getElementById("post_t").style.backgroundColor = "#E95151";
    document.getElementById("post_t").style.color = "#FFFFFF";
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

