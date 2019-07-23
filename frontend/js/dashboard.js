var activeChatFriend = '';
var hideSuggestion = true;
var mouseOverChatArea = false;
var mouseOverFriendsList = false;
var hideShowOptions = true;

function getSuggestions(term) {
    if (term.length === 0) {
        document.querySelector('#show-friends').style.display = "none"; // hide if no term is given
        document.querySelector('#show-friends').innerHTML = ""; // hide if no term is given
        return;
    }
    let xmlHttpReq = new XMLHttpRequest();
    xmlHttpReq.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector('#show-friends').style.display = "revert"; // show the list if search term is given
            var suggested_users = JSON.parse(this.responseText);
            if (suggested_users.length === 0) {
                document.querySelector('#show-friends').innerHTML = "<table class='table'><tbody><tr><td class='text-center'>No Suggestions</td></tr></tbody></table>";
                return;
            }
            let rows = "";
            for (var i = 0; i < suggested_users.length; i++) {
                rows += `
                    <tr>
                        <td>
                            <div style="width: 100%;">
                                <ul style="overflow: auto; position: relative;">
                                    <li style="display: block"><h5 style="display: inline-block; padding: 5px 5px !important;">${suggested_users[i].full_name}</h5><button id="${suggested_users[i].user_id}"  class='btn btn-primary float-right' onclick="addFriend(event)">Add Friend</button></li>
                                    <li style="display: block;"><p style="padding: 0px 5px !important; margin: 0px !important;" class="text-muted">${suggested_users[i].user_id}</p></li>
                                </ul>
                            <div>
                        </td>
                    </tr>
                `;
            }
            document.getElementById("show-friends").innerHTML = "<table class='table'><tbody>" + rows + "</tbody></table>";
        }
    };
    xmlHttpReq.open("GET", `../backend/users_suggestion.php?q=${term}`, true);
    xmlHttpReq.send();
}

function addFriend(e) {
    let xmlHttpReq = new XMLHttpRequest();
    xmlHttpReq.onreadystatechange = function() {
        if (!(this.readyState == 4 && this.status == 200)) {
            return;
        }
        // update the friends list in DOM
        document.getElementById(e.target.id).innerText = "Friend Added";
        document.getElementById(e.target.id).onclick = null;
        document.getElementById(e.target.id).setAttribute('class', 'btn btn-success btn-disabled float-right');
        console.log(this.responseText);
        update_friends_list();
    };
    document.getElementById(e.target.id).innerText = "Sending Request...";
    xmlHttpReq.open("GET", `../backend/add_friend.php?q=${e.target.id}`, true);
    xmlHttpReq.send();
}

function unfriend() {
    let xmlHttpReq = new XMLHttpRequest();
    xmlHttpReq.onreadystatechange = function() {
        if (!(this.readyState == 4 && this.status == 200)) {
            return;
        }
        document.location.reload(true);
        console.log(this.responseText);
    };
    xmlHttpReq.open("GET", `../backend/unfriend.php?q=${activeChatFriend}`, true);
    xmlHttpReq.send();
}

function show_in_chat(e) {
    document.getElementById('message-bar').style.display = '';
    document.getElementById('chat-area').style.visibility = 'hidden';
    hideShowOptions = false;
    showOptions();
    mouseOverChatArea = false;
    if (activeChatFriend !== '')
        document.getElementById(activeChatFriend).style.backgroundColor = "";
    activeChatFriend = e.target.id;
    // Get details of user from database
    let xmlHttpReq = new XMLHttpRequest();
    xmlHttpReq.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let friend = JSON.parse(this.responseText);
            // console.log(friend);
            document.getElementById('active-friend-name').innerText = friend[0].full_name;
            document.getElementById('active-friend-options').innerHTML = `<button class="hide-options-button" onclick="showOptions()"><i class="fa fa-ellipsis-v"></i></button>`;
        }
    };
    xmlHttpReq.open("GET", `../backend/get_user_details.php?q=${e.target.id}`, true);
    xmlHttpReq.send();

}

function showOptions() {
    hideShowOptions = !hideShowOptions;
    if (!hideShowOptions) {
        document.getElementById('active-friend-options-list').style.display = 'block';
    } else {
        document.getElementById('active-friend-options-list').style.display = 'none';
    }

}

function update_friends_list() {
    let xmlHttpReq = new XMLHttpRequest();
    xmlHttpReq.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var friends = JSON.parse(this.responseText);
            if (friends.length === 0) {
                document.querySelector('.show-friends-list').innerHTML = "<table class='table'><thead><th class='text-center' style='padding: 0px; margin: 0px;'><h4 style='margin: 0px; padding: 10px 0px !important;'>Friends List</h4></th></thead><tbody><tr><td class='text-center'>No Friends</td></tr></tbody></table>";
                return;
            }
            // console.log(friends);
            let rows = "";
            for (var i = 0; i < friends.length; i++) {
                rows += `
                    <tr>
                        <td>
                            <div style="cursor: pointer; width: 100%;" id=${friends[i].friend_id} onclick='show_in_chat(event)'>
                                <ul style="position: relative;" id=${friends[i].friend_id} onclick='show_in_chat(event)'>
                                    <li><h5 id=${friends[i].friend_id} onclick='show_in_chat(event)' style="padding: 5px 10px !important;">${friends[i].full_name}</h5></li>
                                    <li id=${friends[i].friend_id} onclick='show_in_chat(event)' style="position: absolute; font-size: 10px; top: 10px; right: 5px;">${friends[i].is_active == 1 && friends[i].hide_status == 0 && friends[i].hide_status_to_all ? '<i class="fa fa-circle" style="font-size:12px;color:green"></i>' : friends[i].last_active}</li>
                                    <li style="display: block;"><h6 id=${friends[i].friend_id} onclick='show_in_chat(event)' style="padding: 5px 10px !important;">${friends[i].friend_id}</h6></li>
                                </ul>
                            <div>
                        </td>
                    </tr>
                `;
            }
            var html = `
                <table class='table table-hover'>
                    <thead>
                        <th class='text-center' style="padding: 0px; margin: 0px;">
                            <h4 style="margin: 0px; padding: 10px 0px !important;">Friends List</h4>
                        </th>
                    </thead>
                    <tbody>
                        ${rows}
                    </tbody>
                </table>
            `;
            document.querySelector('.show-friends-list').innerHTML = html;
        }
    };
    xmlHttpReq.open("GET", "../backend/get_friends_list.php", true);
    xmlHttpReq.send();
}

function showSuggestionList(term) {
    // show only if there is some content in input
    if (term.length === 0) {
        document.querySelector('#show-friends').style.display = "none"; // hide if no term is given
        document.querySelector('#show-friends').innerHTML = "";
    } else {
        document.querySelector('.show-friends').style.display = "unset";
    }
}

function hideSuggestionList() {
    if (!hideSuggestion)
        document.querySelector('.show-friends').style.display = "none";
}

function preventSuggestionHide(bool) {
    document.getElementById('suggestion-input').focus();
    hideSuggestion = bool;
}

function sendMessage(e) {
    e.preventDefault();
    mouseOverChatArea = false; // for bottom scrolling
    var messageElement = document.getElementById('message');
    if (messageElement.value === '')
        return;
    var xmlHttpReq = new XMLHttpRequest();
    xmlHttpReq.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            //console.log("Message Sent");
            console.log(this.responseText);
        }
    }
    xmlHttpReq.open('POST', `../backend/insert_message.php`, true);
    xmlHttpReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlHttpReq.send(`friend_id=${activeChatFriend}&message=${messageElement.value}`);
    messageElement.value= "";
    return;
}

function showMessages() {
    if (activeChatFriend == '')
        return;
    let div = document.getElementById('chat-area');
    div.style.visibility = 'visible';
    if (!mouseOverChatArea)
        scrollToBottom('chat-area');
    // show chats
    let xmlHttpReq = new XMLHttpRequest();
    xmlHttpReq.onreadystatechange = function() {
        if (!(this.readyState == 4 && this.status == 200)) {
            return;
        }

        // update the chat area
        messages = JSON.parse(this.responseText);
        if (messages.length == 0) {
            document.getElementById('chat-area').innerHTML = "";
            return;
        }
        messages.map((val) => {
            val.type = val.sender_id == activeChatFriend ? "friend" : "sender";
        });
        var messageHtml = "";
        for (var i = 0; i < messages.length; i++) {
            messageHtml += `
            <li class="${messages[i].type}">
                <ul>
                    <li style="margin-bottom: 0px !important;">${messages[i].message}</li>
                    <li class="message_time">${messages[i].posted_at}</li>
                </ul>
            </li>
        `;
        }
        var html = "<ul>" + messageHtml + "</ul>";
        document.getElementById('chat-area').innerHTML = html;
    };
    xmlHttpReq.open("GET", `../backend/get_messages.php?q=${activeChatFriend}`, true);
    xmlHttpReq.send();
}

function scrollToBottom(id) {
    var div = document.getElementById(id);
    div.scrollTop = 9999999999999;
}

function funMouseOverChatArea(bool) {
    mouseOverChatArea = bool;
}

function funMouseOverFriendList(bool) {
    mouseOverFriendsList = bool;
}

window.onbeforeunload = function(e) {
    var xmlHttpReq = new XMLHttpRequest();
    xmlHttpReq.open('GET', `../backend/logout.php`, true);
    xmlHttpReq.send();
};

update_friends_list();

showMessages();
setInterval(() => {
    showMessages();
    if (!mouseOverFriendsList)
        update_friends_list();
}, 100);
