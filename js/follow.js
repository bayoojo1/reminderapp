function followToggle(type, user, elem) {
    var conf = confirm("Press OK to confirm the '" + type + "' action for user <?php echo $firstname; ?>.");
    if (conf != true) {
        return false;
    }
    _(elem).innerHTML = 'please wait now ...';
    var ajax = ajaxObj("POST", "php_parsers/follow_system.php");
    ajax.onreadystatechange = function() {
        if (ajaxReturn(ajax) == true) {
            if (ajax.responseText == "follow_ok") {
                _(elem).innerHTML = '<button onclick="followToggle(\'unfollow\',\'<?php echo $u; ?>\',\'friendBtn\')">Unfollow</button>';
            } else if (ajax.responseText == "unfollow_ok") {
                _(elem).innerHTML = '<button onclick="followToggle(\'follow\',\'<?php echo $u; ?>\',\'friendBtn\')">Follow</button>';
            } else {
                alert(ajax.responseText);
                _(elem).innerHTML = 'Try again later';
            }
        }
    }
    ajax.send("type=" + type + "&user=" + user);
}