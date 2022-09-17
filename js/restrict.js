function restrict(elem) {
    var tf = _(elem);
    var rx = new RegExp;
    if (elem == "email") {
        rx = /[' "]/gi;
    }
    tf.value = tf.value.replace(rx, "");
}