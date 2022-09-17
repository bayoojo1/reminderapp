function sendCall() {
    //var e = _("email").value;
    var t = _("message").value;
    var d = _("date").value;
    var k = _("time").value;
    var m = _("mobile").value;
    var r = _("recurrent").value;
    var status = _("status");
    if (t == "" || d == "" || k == "" || m == "") {
        //if(e == "" || t == "" || m == ""){
        status.innerHTML = "Fill out all of the form data";
    } else {
        _("signupbtn").style.display = "none";
        status.innerHTML = 'please wait ...';
        var ajax = ajaxObj("POST", "../functions/app.php");
        ajax.onreadystatechange = function() {
            if (ajaxReturn(ajax) == true) {
                if (ajax.responseText != "OK") {
                    status.innerHTML = ajax.responseText;
                    _("signupbtn").style.display = "block";
                } else {
                    window.scrollTo(0, 0);
                    //_("signupform").innerHTML = "OK "+u+", check your email inbox and junk mail box at <u>"+e+"</u> in a moment to complete the sign up process by activating your account. You will not be able to do anything on the site until you successfully activate your account.";
                }
            }
        }
        ajax.send("t=" + t + "&d=" + d + "&k=" + k + "&m=" + m + "&r=" + r);
        //ajax.send("e="+e+"&t="+t+"&m="+m);
    }
}