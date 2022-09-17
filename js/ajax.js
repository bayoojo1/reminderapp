function ajaxObj(meth, url) {
    var x = xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for older browsers
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    x.open(meth, url, true);
    x.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    return x;
}

function ajaxReturn(x) {
    if (x.readyState == 4 && x.status == 200) {
        return true;
    }
}