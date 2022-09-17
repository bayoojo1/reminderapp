function followToggle(type, user, elem) {
    if (type === 'follow') {
        var conf = confirm("By following this user, you've agreed to receiving audio broadcast/notification shared by him/her. Should you decide later against receiving voice calls from him/her, go to the person's profile and Unfollow him/her. Please don't for any reason tag any call from the user as a SPAM call. You are the one that decided to follow the user. If you still want to continue, confirm by pressing OK.")
        if (conf != true) {
            exit();
        }
    } else if (type === 'unfollow') {
        var conf = confirm('By unfollowing this user, you will not receive any message, either audio or newsfeed from him/her anymore')
        if (conf != true) {
            exit();
        }
    }
    _(elem).innerHTML = 'please wait...'
    var xhttp
    if (window.XMLHttpRequest) {
        // code for modern browsers
        xhttp = new XMLHttpRequest()
    } else {
        // code for IE6, IE5
        xhttp = new ActiveXObject('Microsoft.XMLHTTP')
    }
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText == 'follow_ok') {
                _(elem).innerHTML = '<button onclick="followToggle(\'unfollow\',\'<?php echo $u; ?>\',\'followBtn\')">Unfollow</button>'
            } else if (this.responseText == 'unfollow_ok') {
                _(elem).innerHTML = '<button onclick="followToggle(\'follow\',\'<?php echo $u; ?>\',\'followBtn\')">Follow</button>'
            } else {
                alert(this.responseText)
                _(elem).innerHTML = 'Try again later'
            }
        }
    }
    xhttp.open('POST', 'php_parsers/follow_system.php', true)
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
    xhttp.send('type=' + type + '&user=' + user)
}

function followUsernameToggle(type, user, elem) {
    if (type === 'follow') {
        var conf = confirm("By following this user, you've agreed to receiving audio broadcast/notification shared by him/her. Should you decide later against receiving voice calls from him/her, go to the person's profile and Unfollow him/her. Please don't for any reason tag any call from the user as a SPAM call. You are the one that decided to follow the user. If you still want to continue, confirm by pressing OK.")
        if (conf != true) {
            exit();
        }
    } else if (type === 'unfollow') {
        var conf = confirm('By unfollowing this user, you will not receive any message, either audio or newsfeed from him/her anymore')
        if (conf != true) {
            exit();
        }
    }
    // _(elem).innerHTML = 'please wait...';
    var xhttp
    if (window.XMLHttpRequest) {
        // code for modern browsers
        xhttp = new XMLHttpRequest()
    } else {
        // code for IE6, IE5
        xhttp = new ActiveXObject('Microsoft.XMLHTTP')
    }
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText == 'follow_ok') {
                _(elem).innerHTML = '<button onclick="followUsernameToggle(\'unfollow\',\'<?php echo $followUsername; ?>\',\'<?php echo $userid; ?>\')">Unfollow</button>'
            } else if (this.responseText == 'unfollow_ok') {
                _(elem).innerHTML = '<button onclick="followUsernameToggle(\'follow\',\'<?php echo $followUsername; ?>\',\'<?php echo $userid; ?>\')">Follow</button>'
            } else {
                alert(this.responseText)
                _(elem).innerHTML = 'Try again later'
            }
        }
    }
    xhttp.open('POST', 'php_parsers/follow_system.php', true)
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
    xhttp.send('type=' + type + '&user=' + user)
}

function followingUsernameToggle(type, user, elem) {
    if (type === 'follow') {
        var conf = confirm("By following this user, you've agreed to receiving audio broadcast/notification shared by him/her. Should you decide later against receiving voice calls from him/her, go to the person's profile and Unfollow him/her. Please don't for any reason tag any call from the user as a SPAM call. You are the one that decided to follow the user. If you still want to continue, confirm by pressing OK.")
        if (conf != true) {
            exit();
        }
    } else if (type === 'unfollow') {
        var conf = confirm('By unfollowing this user, you will not receive any message, either audio or newsfeed from him/her anymore')
        if (conf != true) {
            exit();
        }
    }
    // _(elem).innerHTML = 'please wait...';
    var xhttp
    if (window.XMLHttpRequest) {
        // code for modern browsers
        xhttp = new XMLHttpRequest()
    } else {
        // code for IE6, IE5
        xhttp = new ActiveXObject('Microsoft.XMLHTTP')
    }
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText == 'follow_ok') {
                _(elem).innerHTML = '<button onclick="followingUsernameToggle(\'unfollow\',\'<?php echo $followingUsername; ?>\',\'<?php echo $userid; ?>\')">Unfollow</button>'
            } else if (this.responseText == 'unfollow_ok') {
                _(elem).innerHTML = '<button onclick="followingUsernameToggle(\'follow\',\'<?php echo $followingUsername; ?>\',\'<?php echo $userid; ?>\')">Follow</button>'
            } else {
                alert(this.responseText)
                _(elem).innerHTML = 'Try again later'
            }
        }
    }
    xhttp.open('POST', 'php_parsers/follow_system.php', true)
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
    xhttp.send('type=' + type + '&user=' + user)
}

function searchUsernameToggle(type, user, elem) {
    if (type === 'follow') {
        var conf = confirm("By following this user, you've agreed to receiving audio broadcast/notification shared by him/her. Should you decide later against receiving voice calls from him/her, go to the person's profile and Unfollow him/her. Please don't for any reason tag any call from the user as a SPAM call. You are the one that decided to follow the user. If you still want to continue, confirm by pressing OK.")
        if (conf != true) {
            exit();
        }
    } else if (type === 'unfollow') {
        var conf = confirm('By unfollowing this user, you will not receive any message, either audio or newsfeed from him/her anymore')
        if (conf != true) {
            exit();
        }
    }
    // _(elem).innerHTML = 'please wait...';
    var xhttp
    if (window.XMLHttpRequest) {
        // code for modern browsers
        xhttp = new XMLHttpRequest()
    } else {
        // code for IE6, IE5
        xhttp = new ActiveXObject('Microsoft.XMLHTTP')
    }
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText == 'follow_ok') {
                _(elem).innerHTML = '<button onclick="searchUsernameToggle(\'unfollow\',\'<?php echo $searchUsername; ?>\',\'<?php echo $userid; ?>\')">Unfollow</button>'
            } else if (this.responseText == 'unfollow_ok') {
                _(elem).innerHTML = '<button onclick="searchUsernameToggle(\'follow\',\'<?php echo $searchUsername; ?>\',\'<?php echo $userid; ?>\')">Follow</button>'
            } else {
                alert(this.responseText)
                _(elem).innerHTML = 'Try again later'
            }
        }
    }
    xhttp.open('POST', 'php_parsers/follow_system.php', true)
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
    xhttp.send('type=' + type + '&user=' + user)
}

function subscriptionToggle(type, user, elem) {
    if (type === 'subscribe') {
        var conf = confirm('Subscribing to this provider cost 3cent per broadcast. If you still want to continue, confirm by pressing OK.')
        if (conf != true) {
            exit();
        }
    } else if (type === 'unsubscribe') {
        var conf = confirm('By unsubscribing from this provider, you will not receive any message, either audio or newsfeed from him/her anymore')
        if (conf != true) {
            exit();
        }
    }
    // _(elem).innerHTML = 'please wait...';
    var xhttp
    if (window.XMLHttpRequest) {
        // code for modern browsers
        xhttp = new XMLHttpRequest()
    } else {
        // code for IE6, IE5
        xhttp = new ActiveXObject('Microsoft.XMLHTTP')
    }
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText == 'subscribe_ok') {
                _(elem).innerHTML = '<button onclick="subscriptionToggle(\'unsubscribe\',\'<?php echo $provider; ?>\',\'<?php echo $providerid; ?>\')">Unsubscribe</button>'
            } else if (this.responseText == 'unsubscribe_ok') {
                _(elem).innerHTML = '<button onclick="subscriptionToggle(\'subscribe\',\'<?php echo $provider; ?>\',\'<?php echo $providerid; ?>\')">Subscribe</button>'
            } else {
                alert(this.responseText)
                _(elem).innerHTML = 'Try again later'
            }
        }
    }
    xhttp.open('POST', 'php_parsers/subscription_system.php', true)
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
    xhttp.send('type=' + type + '&user=' + user)
}

function restrict(elem) {
    var tf = _(elem)
    var rx = new RegExp()
    if (elem == 'date') {
        rx = /[^0-9-]/gi
    } else if (elem == 'time') {
        rx = /[^0-9:]/gi
    } else if (elem == 'mobile') {
        rx = /[^0-9,]/gi
    } else if (elem == 'sub_description') {
        rx = /[^a-z,0-9;.\n -]/gi
    }
    tf.value = tf.value.replace(rx, '')
}

function emptyElement(x) {
    _(x).innerHTML = ''
}

// Script to load more feed in home page
var i = 1;
$(document).ready(function() {
$("#homeFeed").on("click", ".load-feed", function(evt) {
  evt.preventDefault();
        var rows = Number($('#row').val())
        var allfeeds = Number($('#allfeeds').val())
        var naatcast = $('#naatcast').val()
        var Followers = $('#Followers').val()
        var Subscribers = $('#Subscribers').val()
        var OnlyMe = $('#OnlyMe').val()
        var rowperpage = 10
        var count = document.getElementById('inc').value = ++i;
        row = rows + rowperpage

        if (row <= allfeeds) {
            $('#row').val(row)
            $('.load-feed').html('Loading...')
            $.ajax({
                url: 'functions/getMoreFeed.php',
                type: 'POST',
                data: { row: row, naatcast: naatcast, Followers: Followers, Subscribers: Subscribers, OnlyMe: OnlyMe, count: count },
                success: function(response) {
                    // appending posts after last post
                    if(response != '') {
                    $('.load-feed').remove()
                    $('#homeFeed').append(response)
                  }
                }
            })
        }
    });
  });

// Ends Here........

// Script to load more notifications
var j = 1;
$(document).ready(function() {
$("#PageMiddle").on("click", ".load-notifications", function(evt) {
  evt.preventDefault();
        var rows = Number($('#rowNotification').val())
        var allrows = Number($('#allNotification').val())
        var naatcast = $('#naatcast').val()
        var rowperpage = 10
        var count = document.getElementById('inc').value = ++j;
        row = rows + rowperpage

        if (row <= allrows) {
            $('#rowNotification').val(row)
            $('.load-notifications').html('Loading...')
            $.ajax({
                url: 'functions/getMoreNotifications.php',
                type: 'POST',
                data: { row: row, naatcast: naatcast, count: count },
                success: function(response) {
                    // appending posts after last post
                    if(response != '') {
                    $('.load-notifications').remove()
                    $('#PageMiddle').append(response)
                  }
                }
            })
        }
    });
  });

// Ends Here........

function feedUpdate() {
    $(document).ready(function() {
        document.getElementById('homeFeed').innerHTML = '<div style="text-align:center; margin-top:20px;"><img src="images/loading2.gif" height="30", width="30"></div>'
        $.ajax({
            type: 'POST',
            url: 'functions/feedUpdate_New.php',
            success: function(data) {
                if (data == 'connection_failure') {
                    document.getElementById('homeFeed').innerHTML = '<div style="text-align:center; background-color:black; color:white; border-radius:40px; margin-top:80px; margin-right:50px; margin-left:50px;" onclick="feedUpdate()"><img src="images/disconnected.png" height="40", width="50">Please, we are not able to get your feed right now, try again later...</div>'
                } else {
                    document.getElementById('homeFeed').innerHTML = data
                }
            }
        })
    })
}
feedUpdate()

$(document).ready(function() {
    $('#btnSubmit').click(function(event) {
        // stop submit the form, we will post it manually.
        event.preventDefault()
            // Get form
        var form = $('#signupform')[0]
            // Create an FormData object
        var data = new FormData(form)
            // disabled the submit button
        $('#btnSubmit').prop('disabled', true)
          _('status').innerHTML = 'Uploading, please wait...'
        $.ajax({
            type: 'POST',
            enctype: 'multipart/form-data',
            url: 'functions/app_audiofile.php',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function(data) {
              //output = $.parseHTML(data),
                $('#status').text(data)
                $('#btnSubmit').prop('disabled', false)
                //$('#homeFeed').prepend(data)

            },
            error: function(e) {
                $('#status').text(e.responseText)
                    // console.log("ERROR : ", e);
                $('#btnSubmit').prop('disabled', false)
            }
        })
        feedUpdate()
        var form = document.getElementById('signupform')
        form.reset()
    })
})

// This function post data submitted for content provider registration //
$(document).ready(function() {
    $('#btnSend').click(function(event) {
        // stop submit the form, we will post it manually.
        event.preventDefault()
            // Get form
        var form = $('#sendform')[0]
            // Create an FormData object
        var data = new FormData(form)
            // disabled the submit button
        $('#btnSend').prop('disabled', true)
        _('status').innerHTML = 'Please wait...<img src="images/loading2.gif" height="30", width="30">'
        $.ajax({
            type: 'POST',
            enctype: 'multipart/form-data',
            url: 'functions/provider_registration.php',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function(data) {
                $('#status').text(data).fadeOut(10000)
                $('#btnSend').prop('disabled', false)
            },
            error: function(e) {
                $('#status').text(e.responseText)
                $('#btnSend').prop('disabled', false)
            }
        })
        var form = document.getElementById('sendform')
        form.reset()
    })
})

// ajax for deleting post

function deletePost(id) {
    var conf = confirm('Are you sure you want to delete this post?')
    if (conf != true) {
        exit();
    }
    // var del_id = del.parentNode.firstChild;
    del_tag = id.split('_')[0]
    delete_id = id.split('_')[1]
    var xhttp
    if (window.XMLHttpRequest) {
        // code for modern browsers
        xhttp = new XMLHttpRequest()
    } else {
        // code for IE6, IE5
        xhttp = new ActiveXObject('Microsoft.XMLHTTP')
    }
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            feedUpdate()
        }
    }
    xhttp.open('GET', 'functions/deletePost.php?id=' + delete_id + '&tag=' + del_tag + '&status=delete', true)
    xhttp.send(null)
}

// FUnction for pausing a campaign
function pausePost(id) {
    var conf = confirm('Are you sure you want to pause this broadcast? If this broadcast still has list of numbers to call, it will stop placing calls to the numbers in the list until you resume the broadcast. Do you want to continue?')
    if (conf != true) {
        exit();
    }
    // var del_id = del.parentNode.firstChild;
    pause_tag = id.split('_')[0]
    pause_id = id.split('_')[1]
    var xhttp
    if (window.XMLHttpRequest) {
        // code for modern browsers
        xhttp = new XMLHttpRequest()
    } else {
        // code for IE6, IE5
        xhttp = new ActiveXObject('Microsoft.XMLHTTP')
    }
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            $('#' + id).attr('src','images/playimg.png')
            $('#' + id).attr('title','Resume')
        }
    }
    xhttp.open('GET', 'functions/pausePost.php?id=' + pause_id + '&tag=' + pause_tag + '&status=pause', true)
    xhttp.send(null)
}

// FUnction for resume a campaign
function playPost(id) {
    var conf = confirm('Are you sure you want to resume this broadcast? If this broadcast still has list of numbers to call, it will start placing calls until the list is exhausted. Do you want to continue?')
    if (conf != true) {
        exit();
    }
    // var del_id = del.parentNode.firstChild;
    play_tag = id.split('_')[0]
    play_id = id.split('_')[1]
    var xhttp
    if (window.XMLHttpRequest) {
        // code for modern browsers
        xhttp = new XMLHttpRequest()
    } else {
        // code for IE6, IE5
        xhttp = new ActiveXObject('Microsoft.XMLHTTP')
    }
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            $('#' + id).attr('src','images/pauseimg.png')
            $('#' + id).attr('title','Pause')
        }
    }
    xhttp.open('GET', 'functions/playPost.php?id=' + play_id + '&tag=' + play_tag + '&status=play', true)
    xhttp.send(null)
}

// Function for deleting provider request
function deleteProvider(id) {
    var conf = confirm('Are you sure you want to delete this provider?')
    if (conf != true) {
        exit();
    }
    var xhttp
    if (window.XMLHttpRequest) {
        // code for modern browsers
        xhttp = new XMLHttpRequest()
    } else {
        // code for IE6, IE5
        xhttp = new ActiveXObject('Microsoft.XMLHTTP')
    }
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('PageMiddle').innerHTML = this.responseText
        }
    }
    xhttp.open('GET', 'functions/deleteProvider.php?id=' + id + '&status=delete', true)
    xhttp.send(null)
}

// Function for searching users
function key_down(e) {
    if (e.keyCode === 13) {
        e.preventDefault()
        searchUsers()
    }
}

// This function sends search query to the search page
function searchUsers() {
    var searchquery = document.getElementById('searchquery').value
    window.location = 'http://localhost:8080/reminderapp/search_page.php?searchquery=' + searchquery
}

// Function for searching providers
function key_down_providers(e) {
    if (e.keyCode === 13) {
        e.preventDefault()
        searchProviders()
    }
}

// This function sends search query to the provider search page
function searchProviders() {
    var searchquery = document.getElementById('searchprovider').value
    window.location = 'http://localhost:8080/reminderapp/search_provider_page.php?searchquery=' + searchquery
}

$(document).ready(function() {
    $('#hide').hide()

    $('#message').click(function() {
        $('#hide').toggle(1000)
    })
})

$(document).ready(function() {
    $('#mobile').click(function() {
        $('#follower').fadeOut(500)
        $('#subscriber').fadeOut(500)
        $('#followers').fadeOut(500)
        $('#subscribers').fadeOut(500)
    })
})

$(document).ready(function() {
    $('#followers').change(function() {
        if ($(this).find('option:selected').val() == 'Yes') {
            $('#mobile').hide()
            $('#subscriber').hide()
            $('#subscribers').hide()
        }
    })
})

$(document).ready(function() {
    $('#subscribers').change(function() {
        if ($(this).find('option:selected').val() == 'Yes') {
            $('#mobile').hide()
            $('#follower').hide()
            $('#followers').hide()
        }
    })
})

$(document).ready(function() {
    $('#followers').change(function() {
        if ($(this).find('option:selected').val() == 'No') {
            $('#mobile').show()
            $('#subscriber').show()
            $('#subscribers').show()
        }
    })
})

$(document).ready(function() {
    $('#subscribers').change(function() {
        if ($(this).find('option:selected').val() == 'No') {
            $('#mobile').show()
            $('#follower').show()
            $('#followers').show()
        }
    })
})

$(document).ready(function() {
    $('#shared').change(function() {
        if ($(this).find('option:selected').val() == 'Subscribers') {
            $('#sub_description').show()
        } else {
            $('#sub_description').hide()
        }
    })
})

$(document).ready(function() {
    $('#message').on('input', function() {
        $('#audio_upload').fadeOut()
    })
})

$(document).ready(function() {
    $('#upload_img').click(function() {
        $('#message').prop('disabled', true)
        $('#hide').show(1000)
    })
})

$(document).ready(function() {
    $('#upload_img').click(function() {
        $('#audio').click()
        $('#audio').change(function() {
            var file = this.files[0]
            var name = file.name
            $('#audioStatus').html(name)
        })
    })
})

$(document).ready(function() {
    $('div[id*="name"]').on('keyup', 'input[id*="txtname"]', function() {
        if ($('input[id*="txtname"]').val() == '') {
            $('.saveBtn').prop('disabled', true)
        } else if ($('input[id*="txtname"]').val() != '') {
            $('.saveBtn').prop('disabled', false)
        }
    })
})

$(document).ready(function() {
    $('div[id*="name1"]').on('keyup', 'input[id*="txtname"]', function() {
        if ($('input[id*="txtname"]').val() == '') {
            $('.saveBtn').prop('disabled', true)
        } else if ($('input[id*="txtname"]').val() != '') {
            $('.saveBtn').prop('disabled', false)
        }
    })
})

function checkEmpty() {
    $(document).ready(function() {
        if ($('textarea[id*="txtname"]').val() == '') {
            $('.saveBtn').prop('disabled', true)
        }
    })
}

$(document).ready(function() {
    $('div[id*="name2"]').on('keyup', 'textarea[id*="txtname"]', function() {
        if ($('textarea[id*="txtname"]').val() != '') {
            $('.saveBtn').prop('disabled', false)
        }
    })
})

$(document).ready(function() {
    $('#howitworks').on('click', function() {
        $('#howitworksdisplay').show()
    })
})

function showDelete() {
    $(document).ready(function() {
        $('#deleteimg').on('click', function() {
            $('#deleteDropDown').toggle(1000)
        })
    })
}

$(document).ready(function() {
    $('.accordion').click(function() {
        $('#PageLeft').toggle(1000)
    })
})

function charLimit(limitField, limitNum) {
    if (limitField.value.length > limitNum) {
        limitField.value = limitField.value.substring(0, limitNum)
    }
}

function edit(but) {
    // get parent and then first child <div>
    var div0 = but.parentNode.previousSibling.firstChild
    var ih = div0.innerHTML // record the text of div
    div0.innerHTML = "<input type='text' />" // insert an input
    div0.firstElementChild.value = ih // set input value

    // now get buttons and change visibility
    but.style.visibility = 'hidden' // edit button
    but.parentNode.nextSibling.firstChild.style.visibility = 'visible'
}

function edit3(but) {
    // get parent and then first child <div>
    var div0 = but.parentNode.previousSibling.firstChild
    var ih = div0.innerHTML // record the text of div
    div0.innerHTML = "<textarea row='4' />" // insert an input
    div0.firstElementChild.value = ih // set input value

    // now get buttons and change visibility
    but.style.visibility = 'hidden' // edit button
    but.parentNode.nextSibling.firstChild.style.visibility = 'visible'
}

function edit1(but) {
    // get parent and then first child <div>
    var div0 = but.parentNode.previousSibling.firstChild
    var ih = div0.innerHTML // record the text of div
    div0.innerHTML = "<input type='text' />" // insert an input
    div0.firstElementChild.value = ih // set input value

    // now get buttons and change visibility
    but.style.visibility = 'hidden' // edit button
    but.parentNode.nextSibling.firstChild.style.visibility = 'visible'
    but.parentNode.nextSibling.nextSibling.firstChild.style.visibility = 'visible'
}

function editphone(but) {
    // get parent and then first child <div>
    var div0 = but.parentNode.previousSibling.firstChild
    var ih = div0.innerHTML // record the text of div
    div0.innerHTML = "<input type='text' disabled='disabled' />" // insert an input
    div0.firstElementChild.value = ih // set input value

    // now get buttons and change visibility
    but.style.visibility = 'hidden' // edit button
    but.parentNode.nextSibling.firstChild.style.visibility = 'visible'
    but.parentNode.nextSibling.nextSibling.firstChild.style.visibility = 'visible'
}

function edit2(but) {
    // get parent and then first child <div>
    var div0 = but.parentNode.previousSibling.firstChild
    var ih = div0.innerHTML // record the text of div
    div0.innerHTML = "<textarea row='4' />" // insert an input
    div0.firstElementChild.value = ih // set input value

    // now get buttons and change visibility
    but.style.visibility = 'hidden' // edit button
    but.parentNode.nextSibling.firstChild.style.visibility = 'visible'
    but.parentNode.nextSibling.nextSibling.firstChild.style.visibility = 'visible'
}

function save(but) {
    // get parent and then first child <div>
    var div0 = but.parentNode.previousSibling.previousSibling.firstElementChild

    update_value(div0.id, div0.firstElementChild.value) // send id and new text to ajax function

    // now Restore back to normal mode
    div0.innerHTML = div0.firstElementChild.value
    but.parentNode.previousSibling.firstElementChild.style.visibility = 'visible'
    but.style.visibility = 'hidden'
}

function update_value(id, value) {
    var xhttp
    if (window.XMLHttpRequest) {
        // code for modern browsers
        xhttp = new XMLHttpRequest()
    } else {
        // code for IE6, IE5
        xhttp = new ActiveXObject('Microsoft.XMLHTTP')
    }
    xhttp.open('GET', 'functions/profile_page_func.php?id=' + id + '&value=' + value + '&status=Save', true)
    xhttp.send(null)
        // console.log(status);
}

function save1(but) {
    // get parent and then first child <div>
    var div0 = but.parentNode.previousSibling.previousSibling.firstElementChild
        // alert("ID is= " + div0.id + ", Input Value is= " + div0.firstElementChild.value); // see what you got
    update_value1(div0.id, div0.firstElementChild.value) // send id and new text to ajax function

    // now Restore back to normal mode
    div0.innerHTML = div0.firstElementChild.value
    but.parentNode.previousSibling.firstElementChild.style.visibility = 'visible'
    but.style.visibility = 'hidden'
    but.parentNode.nextSibling.firstChild.style.visibility = 'hidden'
}

function update_value1(id, value) {
    var xhttp
    if (window.XMLHttpRequest) {
        // code for modern browsers
        xhttp = new XMLHttpRequest()
    } else {
        // code for IE6, IE5
        xhttp = new ActiveXObject('Microsoft.XMLHTTP')
    }
    xhttp.open('GET', 'functions/profile_page_func.php?id=' + id + '&value=' + value + '&status=Save', true)
    xhttp.send(null)
        // console.log(status);
}

function aliasCheckboxState() {
    $.ajax({
        type: 'POST',
        url: 'functions/aliascheck.php',
        data: { 'checked': (($('input[id*="aliascheck"]').prop('checked')) ? 0 : 1) }
    })
}

$(function() { /* to make sure the script runs after page load */
    $('.About').each(function(event) { /* select all divs with the item class */
        var max_length = 255 /* set the max content length before a read more link will be added */
        if ($(this).html().length > max_length) { /* check for content length */
            var short_content = $(this).html().substr(0, max_length) /* split the content in two parts */
            var long_content = $(this).html().substr(max_length)
            $(this).html(short_content +
                '<a href="#" class="read_more" style="font-style:italic;"><br/>Read More</a>' +
                '<span class="more_text" style="display:none;">' + long_content + '</span>') /* Alter the html to allow the read more functionality */
            $(this).find('a.read_more').click(function(event) { /* find the a.read_more element within the new html and bind the following code to it */
                event.preventDefault() /* prevent the a from changing the url */
                $(this).hide() /* hide the read more button */
                $(this).parents('.About').find('.more_text').show() /* show the .more_text span */
            })
        }
    })
})

function mobileCheckboxState() {
    // execute AJAX request here
    $.ajax({
        type: 'POST',
        url: 'functions/mobilecheck.php',
        data: { 'checked': (($('input[id*="mobilecheck"]').prop('checked')) ? 1 : 0) }
    })
}

function websiteCheckboxState() {
    // execute AJAX request here
    $.ajax({
        type: 'POST',
        url: 'functions/websitecheck.php',
        data: { 'checked': (($('input[id*="websitecheck"]').prop('checked')) ? 0 : 1) }
    })
}

function aboutCheckboxState() {
    // execute AJAX request here
    $.ajax({
        type: 'POST',
        url: 'functions/aboutcheck.php',
        data: { 'checked': (($('input[id*="about"]').prop('checked')) ? 0 : 1) }
    })
}

function forgotpass() {
    var e = _('email').value
    if (e == '') {
        _('status').innerHTML = 'Type in your email address'
    } else {
        _('forgotpassbtn').style.display = 'none'
        _('status').innerHTML = 'Please wait...<img src="images/loading2.gif" height="30", width="30">'
        var xhttp
        if (window.XMLHttpRequest) {
            // code for modern browsers
            xhttp = new XMLHttpRequest()
        } else {
            // code for IE6, IE5
            xhttp = new ActiveXObject('Microsoft.XMLHTTP')
        }
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseText == 'success') {
                    _('forgotpassform').innerHTML = '<h3>Step 2. Check your email inbox in a few minutes</h3><p>You can close this window or tab if you like.</p>'
                } else if (this.responseText == 'no_exist') {
                    _('status').innerHTML = 'Sorry that email address is not in our system'
                } else if (this.responseText == 'email_send_failed') {
                    _('status').innerHTML = 'Mail function failed to execute'
                } else {
                    _('status').innerHTML = 'An unknown error occurred'
                }
            }
        }
        xhttp.open('POST', 'forgot_pass.php', true)
        xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
        xhttp.send('e=' + e)
    }
}

function emptyElement(x) {
    _(x).innerHTML = ''
}

function login() {
    var e = _('email').value
    var p = _('password').value
    if (e == '' || p == '') {
        _('status').innerHTML = 'Fill out all of the form data'
    } else {
        _('loginbtn').style.display = 'none'
            // _("status").innerHTML = 'please wait ...';
        _('status').innerHTML = '<span style="color:#004080;">Please wait...</span><img src="images/loading2.gif" height="30", width="30">'
        var xhttp
        if (window.XMLHttpRequest) {
            // code for modern browsers
            xhttp = new XMLHttpRequest()
        } else {
            // code for IE6, IE5
            xhttp = new ActiveXObject('Microsoft.XMLHTTP')
        }
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseText == 'login_failed') {
                    _('status').innerHTML = 'Login unsuccessful, please try again.'
                    _('loginbtn').style.display = 'block'
                } else {
                    window.location = 'user_audio.php?u=' + this.responseText
                }
            }
        }
        xhttp.open('POST', 'login.php', true)
        xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
        xhttp.send('e=' + e + '&p=' + p)
    }
}

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=')

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1]
        }
    }
}

function checkaudio() {
    var checked = $('#noaudio1').prop('checked') ? 1 : 0
        var u = getUrlParameter('u')
    //var url = window.location.href;
    //var u = url.split('/')[0];
    dataString = 'checked=' + checked + '&u=' + u
    $.ajax({
        type: 'POST',
        url: 'functions/noaudiocheck.php',
        data: dataString
    })
}

function approved(id) {
    var checked = $('#' + id).prop('checked') ? 1 : 0
    dataString = 'checked=' + checked + '&id=' + id
    $.ajax({
        type: 'POST',
        url: 'functions/approveProvider.php',
        data: dataString
    })
}

// Password update
$(document).ready(function() {
    $('#passsubmit').click(function(event) {
        event.preventDefault()
        var cp = $('#current').val()
        var np = $('#newpwd').val()
        var cf = $('#confirmpwd').val()
        dataString = 'cp=' + cp + '&np=' + np + '&cf=' + cf
        _('passsubmit').style.display = 'none'
        _('pmessage').innerHTML = 'Please wait...<img src="images/loading2.gif" height="30", width="30">'

        $.ajax({
            type: 'post',
            url: 'functions/changepwd.php',
            data: dataString,
            success: function(response) {
                if (response == 'empty_field') {
                    _('pmessage').innerHTML = '<span style="color:red; font-weight:800;">Please fill in all the fields</span>'
                } else if (response == 'not_thesame_1') {
                    _('pmessage').innerHTML = '<span style="color:red; font-weight:800;">Your current password does not match what we have in the database</span>'
                } else if (response == 'not_thesame_2') {
                    _('pmessage').innerHTML = '<span style="color:red; font-weight:800;">Your new password and confirmed password are not thesame</span>'
                } else if (response == 'successful') {
                    _('pmessage').innerHTML = '<span style="color:green; font-weight:800;">You have successfully changed your password</span>'
                }
            }
        })
    })
})

$(document).ready(function() {
    $('#passsubmit').click(function() {
        $('#pmessage').fadeOut(8000)
    })
})

$(document).ready(function() {
    $('input:file').change(function() {
        if ($(this).val()) {
            $('input:submit').attr('disabled', false)
        }
    })
})

$(document).ready(function() {
    $('#pix').sticky({ topSpacing: 45 })
})

$(document).ready(function() {
    $('#big-ads').sticky({ topSpacing: 45 })
})

$(document).ready(function() {
    $('#from_date').datepicker({
        dateFormat: 'yy-mm-dd',
        prevText: 'Prev << ',
        nextText: ' >> Next',
        autoSize: true
    })
    $('#to_date').datepicker({
        dateFormat: 'yy-mm-dd',
        prevText: 'Prev << ',
        nextText: ' >> Next',
        autoSize: true
    })
    $('#filter').click(function() {
        var from_date = $('#from_date').val()
        var to_date = $('#to_date').val()
        var call_status = $('#call_status').val()
        if (from_date != '' && to_date != '' && (call_status == 'answered' || call_status == 'busy' || call_status == 'noanswer' || call_status == 'canceled' || call_status == 'congestion' || call_status == 'chanunavail')) {
            window.location = 'http://localhost:8080/reminderapp/filter.php?from=' + from_date + '&to=' + to_date + '&status=' + call_status
        } else {
            alert('Please Select All Fields')
        }
    })
})

//* *******Functions for Subscription call request*********//
function requestAudio(butt) {
    $(document).ready(function() {
        var conf = confirm('You will soon receive a call on your mobile, and the audio you requested would be played back to you. The call will cost you 3cent per minute. Do you want to proceed?')
        if (conf != true) {
            exit();
        }
        var sub_id = butt.parentNode.firstElementChild
        feed_tag = sub_id.id.split('.')[0]
        feed_id = sub_id.id.split('.')[1]
        $.ajax({
            url: 'functions/requestAudio.php',
            type: 'POST',
            data: { feed_tag: feed_tag, feed_id: feed_id },
            success: function(response) {
                if (response == 'no_credit') {
                    alert("You don't have enough credit to complete this request, please recharge")
                } else if (response == 'success') {
                    alert('You will soon receive a call and the requested audio would be played back to you')
                } else if (response == 'error') {
                    alert('Sorry, your request is not successful. Try again later')
                }
            }
        })
    })
}
//* *********************Ends Here**************************//
//* *****Functions for displaying provider call stats******//
/*
function callstats(stats) {
  $(document).ready(function() {
    var stats_id = stats.parentNode.firstElementChild
    tag = stats_id.id.split('.')[0]
    id = stats_id.id.split('.')[1]
        //alert(id);
    window.location = 'http://localhost:8080/reminderapp/callstats.php?tag=' + tag + '&id=' + id
  })
}
*/
//* *********************Ends Here**************************//
//* ****************Functions for post Like*****************//
function like(like) {
    $(document).ready(function() {
        var like_id = like.parentNode.firstElementChild
        tag = like_id.id.split('.')[0]
        id = like_id.id.split('.')[1]
        $.ajax({
            url: 'functions/like.php',
            type: 'POST',
            data: { tag: tag, id: id },
            success: function(response) {
                if (response == 'not_ok') {
                    alert('You cannot like a post twice and you cannot like and unlike a post at thesame time!')
                } else {
                    $('#likecnt' + id + tag).html(response)
                    $('#likebtn' + id + tag).css('color', '#004080')
                }
            }
        })
    })
}
//* *********************Ends Here**************************//
//* ****************Functions for post Unlike*****************//
function unlike(unlike) {
    $(document).ready(function() {
        var unlike_id = unlike.parentNode.firstElementChild
        tag = unlike_id.id.split('.')[0]
        id = unlike_id.id.split('.')[1]
        $.ajax({
            url: 'functions/unlike.php',
            type: 'POST',
            data: { tag: tag, id: id },
            success: function(response) {
                if (response == 'not_ok') {
                    alert('You cannot like a post twice and you cannot like and unlike a post at thesame time!')
                } else {
                    $('#unlikecnt' + id + tag).html(response)
                    $('#unlikebtn' + id + tag).css('color', '#004080')
                }
            }
        })
    })
}

//* *********************Ends Here**************************//
//* *********Function for notification drop-down menu*******//
// SAVE REFERENCE TO OPEN TAB

$(document).ready(function() {
        $('#notify').click(function() {
            $('#notecontainer').toggle()
            $.ajax({
                type: 'POST',
                url: 'functions/zero-Counter.php',
                success: function(data) {
                    $('#totalcount').html(data)
                    $('#totalcount').css('display', 'none')
                }
            })
        })
    })
/*
function realtimeNotify() {
        $.ajax({
            type: 'POST',
            url: 'functions/notifications_refresh.php',
            success: function(response) {
                $('#totalcount').html(response)
            },
            complete: function() {
                setTimeout(realtimeNotify, 50000)
            }
        })
}
$(document).ready(function() {
        // run the first time; all subsequent calls will take care of themselves
        setTimeout(realtimeNotify, 50000)
    })*/
    // Close the dropdown menu if the user clicks outside of it
    /*
    $(document).ready(function() {
        window.addEventListener('mouseup', function(event) {
            var box = document.getElementById('notecontainer');
            var openLink = document.getElementById('note_still');

            // EXCLUDE CLICKS ON OPEN TAB
            if (event.target != box && event.target.parentNode != box && event.target != openLink) {
                box.style.display = 'none';
                event.stopPropagation();
            }
        });
    }); */
    //* **************Ends Here*********************************//

// Function to refresh the providers display at interval
function refresh() {
    $(document).ready(function() {
        $.ajax({
            type: 'POST',
            url: 'provider_refresh.php',
            success: function(data) {
                $('#divContent').html(data)
            },
            complete: function() {
                setTimeout(refresh, 50000)
            }
        })
    })
}

$(document).ready(function() {
        // run the first time; all subsequent calls will take care of themselves
        setTimeout(refresh, 50000)
    })
    // Ends Here //

// Script for menu-bar tab selection
$(document).ready(function() {
        if (document.URL.indexOf('user_audio') != -1) {
            $('#homeid').css('color', 'white')
        } else if (document.URL.indexOf('follow') != -1) {
            $('#friendid').css('color', 'white')
        } else if ((document.URL.indexOf('profile') != -1) || (document.URL.indexOf('callrecords') != -1) || (document.URL.indexOf('billing') != -1) || (document.URL.indexOf('filter') != -1) || (document.URL.indexOf('apicreate') != -1)) {
            $('#settingsid').css('color', 'white')
        } else if (document.URL.indexOf('notification_page') != -1) {
            $('#note_still').css('color', 'white')
        }
    })
    // Ends Here //

$(document).ready(function() {
    $('#share').jsSocials({
        shares: ['twitter', 'facebook', 'googleplus', 'linkedin', 'whatsapp', 'messenger']
    })
})

// Script to fetch comments for display
function fetchComment() {
    $(document).ready(function() {
        //var url = window.location.href;
        //post_id = url.split('/')[5];
        //post_tag = url.split('/')[6];
        post_id = getUrlParameter('id')
        post_tag = getUrlParameter('tag')
        var commentElem = document.getElementById('comment-id')
        if (commentElem) {
            commentElem.innerHTML = '<div style="text-align:center; margin-top:20px;"><img src="images/loading2.gif" height="30", width="30"></div>'
        }
        $.ajax({
            type: 'POST',
            url: 'functions/commentUpdate.php',
            data: { post_id: post_id, post_tag: post_tag },
            success: function(data) {
                if (commentElem) {
                    commentElem.innerHTML = data
                }
            }
        })
    })
}
fetchComment()
    // setInterval(fetchComment, 5000);

// Script to load more comments on a post
var x = 1;
$(document).ready(function() {
$("#comment-id").on("click", ".load-comment", function(evt) {
    var rows = Number($('#row').val())
    var allcount = Number($('#all').val())
    var postid = $('#postid').val()
    var posttag = $('#posttag').val()
    var rowperpage = 5
    var increment = document.getElementById('incr').value = ++x;
    row = rows + rowperpage

    if (row <= allcount) {
        $('#row').val(row)
        $('.load-comment').html('Loading...')
        $.ajax({
            url: 'functions/getComments.php',
            type: 'POST',
            data: { row: row, postid: postid, posttag: posttag, increment: increment },
            success: function(response) {
                if(response != '') {
                  $('.load-comment').remove()
                  $('#comment-id').append(response)
              }
            }
        })
    }
  });
});

// Script for broadcast comment
function comment(cmt) {
    if (document.getElementById('comments').value == '') {
        alert('You can\'t submit an empty comment')
        exit()
    }
    $(document).ready(function() {
        comments = document.getElementById('comments').value
        var input_id = cmt.parentNode.firstElementChild
        post_id = input_id.id.split('_')[0]
        post_tag = input_id.id.split('_')[1]
        $.ajax({
            url: 'functions/comments.php',
            type: 'POST',
            data: { post_tag: post_tag, post_id: post_id, comments: comments },
            success: function(result) {
                output = $.parseHTML(result),
                    $('#comment-id').prepend(output)
            }
        })
        document.getElementById('comments').value = ''
    })
}

function deleteComment(id) {
    var conf = confirm('Are you sure you want to delete this comment?')
    if (conf != true) {
        exit();
    }
    var xhttp
    if (window.XMLHttpRequest) {
        // code for modern browsers
        xhttp = new XMLHttpRequest()
    } else {
        // code for IE6, IE5
        xhttp = new ActiveXObject('Microsoft.XMLHTTP')
    }
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            fetchComment()
        }
    }
    xhttp.open('GET', 'functions/deleteComment.php?id=' + id + '&status=delete', true)
    xhttp.send(null)
}

function commentCnt(cnt) {
    $(document).ready(function() {
        var count_id = cnt.parentNode.firstElementChild
        post_id = count_id.id.split('_')[0]
        post_tag = count_id.id.split('_')[1]
        $.ajax({
            url: 'functions/commentCount.php',
            type: 'POST',
            data: { post_id: post_id, post_tag: post_tag },
            success: function(responseText) {
                $('.comment').html(responseText)
            }
        })
    })
}

$(document).ready(function() {
    $('#contentCategory').change(function() {
        var selection = $(this).find('option:selected').val()
        $('status').innerHTML = '<img src="images/loading2.gif" height="30", width="30">'
        $.ajax({
            url: 'functions/displayProvider.php',
            type: 'POST',
            data: { selection: selection },
            success: function(data) {
                $('#providerPage').html(data)
            }
        })
    })
})

$(document).ready(function() {
    $('#searchuserWrap').on('keyup', 'input[id*="searchusers"]', function() {
        var input = $('#searchusers').val()
        $('status').innerHTML = '<img src="images/loading2.gif" height="30", width="30">'
        $.ajax({
            url: 'functions/displayUsers.php',
            type: 'POST',
            data: { input: input },
            success: function(data) {
                $('#user_search').html(data)
            }
        })
    })
})


function broadcast(cast) {
    $(document).ready(function() {
        var pop_id = cast.parentNode.firstElementChild
        tag = pop_id.id.split('.')[0]
        id = pop_id.id.split('.')[1]
        $('#popbtn' + id + tag).css('display', 'block')
    })
}

function deletePopup(pop) {
  $(document).ready(function() {
    var delPop = pop.parentNode.parentNode.firstElementChild
    tag = delPop.id.split('.')[0]
    id = delPop.id.split('.')[1]
    $('#popbtn' + id + tag).css('display', 'none')
  })
}

function sharebcast(share) {
  $(document).ready(function() {
    var shareb = share.previousSibling.previousSibling.previousSibling.previousSibling.previousSibling.previousSibling
    id = shareb.id.split('.')[0] //get the first part of the id before dot
    tag = shareb.id.split('.')[1] // get the second part after dot
    content = shareb.value // get the content of the textarea
    numeralid = id.replace(/[^0-9]/g,''); // get the digit part of the first part of id
    var pattern = /[^0-9,]/g; //Pattern to match the mobile number
    //var form = document.getElementsByClassName('mobilepop')

    if (content == '') {
        _('broadcaststatus').innerHTML = 'Enter recipient mobile.'
        exit();
    } else if (pattern.test(content)) {
      _('broadcaststatus').innerHTML = 'Only mobile number separated by comma are allowed, e.g. 2348020000000,2348020000000.'
      exit();
    } else {
        _('btnCast').style.display = 'none'
        _('broadcaststatus').innerHTML = 'Please wait...<img src="images/loading2.gif" height="30", width="30">'
    }
    $.ajax({
        url: 'functions/broadcastAudio.php',
        type: 'POST',
        data: { numeralid: numeralid, tag: tag, content: content },
        success: function(response) {
            if (response == 'cant_be_empty') {
                alert("Enter recipient mobile")
                _('btnCast').style.display = 'block'
            }else if (response == 'no_credit') {
                alert("You don't have enough credit to complete this request, please recharge")
                  _('btnCast').style.display = 'block'
            } else if (response == 'success') {
                alert('You will soon receive a call and the requested audio would be played back to you')
                  _('btnCast').style.display = 'block'
                  _('broadcaststatus').innerHTML = ""

            } else if (response == 'error') {
                alert('Sorry, your request is not successful. Try again later')
                  _('btnCast').style.display = 'block'
            }
        }
    })
  })
}

$(document).ready(function() {
    $('#apibtn').on('click', function() {
        $('#createapi').toggle()
    })
})

function showApi() {
  $(document).ready(function() {
    document.getElementById('apilist').innerHTML = '<div style="text-align:center; margin-top:20px;"><img src="images/loading2.gif" height="30", width="30"></div>'
    $.ajax({
        url: 'functions/showApi.php',
        type: 'POST',
        success: function(data) {
            $('#apilist').html(data)
      }
    })
  })
}
showApi()

function saveapi() {
  $(document).ready(function(){
    var apiname = $('#api').val()
    var apidescription = $('#apidesc').val()
    if(apiname == '' || apidescription == '') {
      alert("Fill the two fields please")
      exit();
    }
    $.ajax({
      url: 'functions/createapi.php',
      type: 'POST',
      data: { apiname: apiname, apidescription: apidescription },
      success: function(response) {
        showApi()
      }
    })
    document.getElementById('api').value = '';
    document.getElementById('apidesc').value = '';
    $('#createapi').css('display', 'none')
  })
}

function deleteapi(id) {
  var conf = confirm('Are you sure you want to delete this API? Note that you would not be able to initiate broadcast from any application using the API anymore.' )
  if (conf != true) {
      exit();
  }
  var xhttp
  if (window.XMLHttpRequest) {
      // code for modern browsers
      xhttp = new XMLHttpRequest()
  } else {
      // code for IE6, IE5
      xhttp = new ActiveXObject('Microsoft.XMLHTTP')
  }
  xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
          showApi()
      }
  }
  xhttp.open('GET', 'functions/deleteApi.php?id=' + id + '&status=delete', true)
  xhttp.send(null)
}

$(document).ready(function() {
    $('#samplecode').on('click', function() {
        $('#samplecd').toggle()
    })
})

function showQueries() {
  $(document).ready(function() {
    var ticketElem = document.getElementById('querylist')
    if (ticketElem) {
        ticketElem.innerHTML = '<div style="text-align:center; margin-top:20px;"><img src="images/loading2.gif" height="30", width="30"></div>'
    }
    $.ajax({
        url: 'functions/showqueries.php',
        type: 'POST',
        success: function(data) {
            $('#querylist').html(data)
      }
    })
  })
}
showQueries()

function sendquery() {
  $(document).ready(function(){
    var queryoption = $('#query').val()
    var querydetail = $('#detail').val()
    if(querydetail == '' || queryoption == '') {
      alert("Fill the two fields please")
      exit();
    }
    $.ajax({
      url: 'functions/queries.php',
      type: 'POST',
      data: { queryoption: queryoption, querydetail: querydetail },
      success: function(data) {
        $('#querylist').prepend(data)
      }
    })
  })
}

function ticketComment(id) {
    if (document.getElementById('tcomments').value == '') {
        alert('You can\'t submit an empty comment')
        exit();
    }
    $(document).ready(function() {
        tcomments = document.getElementById('tcomments').value
        $.ajax({
            url: 'functions/ticketquery.php',
            type: 'POST',
            data: { tcomments: tcomments, id: id },
            success: function(result) {
                output = $.parseHTML(result),
                    $('#tCommentShow').prepend(output)
            }
        })
        document.getElementById('tcomments').value = ''
    })
}

// Script to fetch ticket comments for display
function showQueryComments() {
    $(document).ready(function() {
        //var url = window.location.url;
        //ticket_username = url.split('/')[3];
        //ticket_id = url.split('/')[4];
        //alert(ticket_username);
        ticket_id = getUrlParameter('id')
        ticket_username = getUrlParameter('u')
        var ticketElem = document.getElementById('tCommentShow')
        if (ticketElem) {
            ticketElem.innerHTML = '<div style="text-align:center; margin-top:20px;"><img src="images/loading2.gif" height="30", width="30"></div>'
        }
        $.ajax({
            type: 'POST',
            url: 'functions/showqueryComments.php',
            data: { ticket_username: ticket_username, ticket_id: ticket_id },
            success: function(data) {
                if (ticketElem) {
                    ticketElem.innerHTML = data
                }
            }
        })
    })
}
showQueryComments()

function deleteticket(id) {
    var conf = confirm('Are you sure you want to delete this ticket comment?')
    if (conf != true) {
        exit();
    }
    var xhttp
    if (window.XMLHttpRequest) {
        // code for modern browsers
        xhttp = new XMLHttpRequest()
    } else {
        // code for IE6, IE5
        xhttp = new ActiveXObject('Microsoft.XMLHTTP')
    }
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            showQueryComments()
        }
    }
    xhttp.open('GET', 'functions/deleteticket.php?id=' + id + '&status=delete', true)
    xhttp.send(null)
}

function restrictsignup(elem){
    var tf = _(elem);
    var rx = new RegExp;
    if(elem == "email"){
        rx = /[' "]/gi;
    } else if(elem == "firstname"){
        rx = /[^a-z]/gi;
    } else if(elem == "lastname"){
        rx = /[^a-z]/gi;
    } else if(elem == "mobile"){
        rx = /^[2-9, ]/gi;
    }
    tf.value = tf.value.replace(rx, "");
}
function emptyElementsignup(x){
    _(x).innerHTML = "";
}
function checkemail(){
    var e = _("email").value;
    if(e != ""){
        _("emailstatus").innerHTML = 'checking ...';
        var ajax = ajaxObj("POST", "signup.php");
        ajax.onreadystatechange = function() {
            if(ajaxReturn(ajax) == true) {
                _("emailstatus").innerHTML = ajax.responseText;
            }
        }
        ajax.send("email="+e);
    }
}
function signup(){
    var e = _("email").value;
    var f = _("firstname").value;
    var l = _("lastname").value;
    var p1 = _("pass1").value;
    var p2 = _("pass2").value;
    var c = _("country").value;
    var m = _("mobile").value;
    //var w = _("website").value;
    var status = _("status");
    if(e == "" || f == "" || l == "" || p1 == "" || p2 == "" || c == "" || m == ""){
        status.innerHTML = "Fill out all of the form data";
    } else if(p1 != p2){
        status.innerHTML = "Your password fields do not match";
    } else if(!document.getElementById("checkbox_id").checked){
        status.innerHTML = "You must accept the terms and condition to register";
    } else {
        _("signupbtn").style.display = "none";
        status.innerHTML = 'Please wait...<img src="images/loading2.gif" height="30", width="30">';
        var ajax = ajaxObj("POST", "signup.php");
        ajax.onreadystatechange = function() {
            if(ajaxReturn(ajax) == true) {
                if(ajax.responseText != "signup_success"){
                    status.innerHTML = ajax.responseText;
                    _("signupbtn").style.display = "block";
                } else {
                    window.scrollTo(0,0);
                    _("signupform").innerHTML = "OK "+f+", check your email inbox and junk mail box at <u>"+e+"</u> in a moment to complete the sign up process by activating your account. You will not be able to do anything on the site until you successfully activate your account.";
                }
            }
        }
        ajax.send("e="+e+"&f="+f+"&l="+l+"&p="+p1+"&c="+c+"&m="+m);
    }
}

$(document).ready(function() {
    $('#updateRate').click(function(event) {
        // stop submit the form, we will post it manually.
        event.preventDefault()
            // Get form
        var id = $('#id').val()
        var destination = $('#destination').val()
        var prefix = $('#prefix').val()
        var rate = $('#rate').val()
        $('status').innerHTML = '<img src="images/loading2.gif" height="30", width="30">'
        $.ajax({
            url: 'functions/updateRate.php',
            type: 'POST',
            data: { id: id, destination: destination, prefix: prefix, rate: rate },
            success: function(responseText) {
                $('#status').html(responseText).fadeOut(8000)
            }
        })
        var form = document.getElementById('updateRateSheet')
        form.reset()
    })
})
