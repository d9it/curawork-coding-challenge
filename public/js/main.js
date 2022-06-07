var skeletonId = 'skeleton';
var contentId = 'content';
var skipCounter = 0;
var takeAmount = 10;
var suggestionPage = 1
var requestPage = 1
var receivedPage = 1
var connectionPage = 1
var commonConnectionPage = 1
// Call onpage load
getSuggestions()

function getRequests(mode) {
    let form = ajaxForm([
        ['mode', mode]
    ])
    if (mode == 'sent') {
        let url = '/connection_requests/get_sent_request?page=' + requestPage
        let method = 'post'
        var functionsOnSuccess = [
            [onSuccessGetRequest, ['response']]
        ];
        ajax(url, method, functionsOnSuccess, form)
        requestPage++;
    } else {
        let url = '/connection_requests/get_received_request?page=' + receivedPage
        let method = 'post'
        var functionsOnSuccess = [
            [onSuccessReceivedRequest, ['response']]
        ];
        ajax(url, method, functionsOnSuccess, form)
        receivedPage++;
    }
}
function onSuccessGetRequest(resp) {
    $('#sent_requests').empty()
    $('#sent_requests').html(resp.data)
}
function onSuccessReceivedRequest(resp) {
    $('#received_requests').empty()
    $('#received_requests').html(resp.data)
}

function getMoreRequests(mode) { //load more requests
    let form = ajaxForm([
        ['mode', mode]
    ])
    if (mode == 'sent') {
        let url = '/connection_requests/get_sent_request?page=' + requestPage
        let method = 'post'
        var functionsOnSuccess = [
            [onSuccessGetMoreRequests, ['response']]
        ];
        ajax(url, method, functionsOnSuccess, form)
        requestPage++;
    } else {
        let url = '/connection_requests/get_received_request?page=' + receivedPage
        let method = 'post'
        var functionsOnSuccess = [
            [onSuccessGetMoreReceivedRequests, ['response']]
        ];
        ajax(url, method, functionsOnSuccess, form)
        receivedPage++;
    }
}
function onSuccessGetMoreRequests(resp) {
    if (resp.data == '') {
        $('#load_more_sent_requests').addClass('d-none')
    }
    $('#sent_requests_skeleton').removeClass('d-none')
    setTimeout(() => {
        $('#sent_requests_skeleton').addClass('d-none')
        $('#sent_requests').append(resp.data)
    }, 2000);
}
function onSuccessGetMoreReceivedRequests(resp) {
    if (resp.data == '') {
        $('#load_more_received_requests').addClass('d-none')
    }
    $('#sent_received_skeleton').removeClass('d-none')
    setTimeout(() => {
        $('#sent_received_skeleton').addClass('d-none')
        $('#received_requests').append(resp.data)
    }, 2000);
}

function getConnections() {
    let url = '/connections?page=' + connectionPage
    let method = 'get'
    var functionsOnSuccess = [
        [onSuccessGetConnection, ['response']]
    ];
    ajax(url, method, functionsOnSuccess)
    connectionPage++;
}
function onSuccessGetConnection(resp) {
    $('#connections').empty()
    $('#connections').html(resp.data)
}

function getMoreConnections() {
    let url = '/connections?page=' + connectionPage
    let method = 'get'
    var functionsOnSuccess = [
        [onSuccessGetMoreConnection, ['response']]
    ];
    ajax(url, method, functionsOnSuccess)
    connectionPage++;
}
function onSuccessGetMoreConnection(resp) {
    if (resp.data == '') {
        $('#load_more_connections').addClass('d-none')
    }
    $('#connections_skeleton').removeClass('d-none')
    setTimeout(() => {
        $('#connections_skeleton').addClass('d-none')
        $('#connections').append(resp.data)
    }, 2000);
}

function getConnectionsInCommon(userId, connectionId) {
    $('#common_connection_' + connectionId).collapse("toggle");
    let form = ajaxForm([
        ['connectionId', connectionId]
    ])
    let url = '/connections/get_common_connection?page=' + commonConnectionPage
    let method = 'post'
    var functionsOnSuccess = [
        [onSuccessGetConnectionInCommon, [connectionId, 'response']]
    ];
    ajax(url, method, functionsOnSuccess, form)
    commonConnectionPage++;
}
function onSuccessGetConnectionInCommon(connectionId, resp) {
    $('#common_connection_' + connectionId).empty()
    $('#common_connection_' + connectionId).html(resp.data)
}

function getMoreConnectionsInCommon(userId, connectionId) {
    let form = ajaxForm([
        ['connectionId', connectionId]
    ])
    let url = '/connections/get_common_connection?page=' + commonConnectionPage
    let method = 'post'
    var functionsOnSuccess = [
        [onSuccessGetMoreConnectionInCommon, [connectionId, 'response']]
    ];
    ajax(url, method, functionsOnSuccess, form)
    commonConnectionPage++;
}
function onSuccessGetMoreConnectionInCommon(connectionId, resp) {
    if (resp.data == '') {
        $('#load_more_suggestion_connections_in_common_' + connectionId).addClass('d-none')
    }
    $('#connections_in_common_skeletons_' + connectionId).removeClass('d-none')
    setTimeout(() => {
        $('#connections_in_common_skeletons_' + connectionId).addClass('d-none')
        $('#common_connection_' + connectionId).append(resp.data)
    }, 2000);
}

function getSuggestions() {
    let url = '/connections/get_suggestions?page=' + suggestionPage
    let method = 'get'
    var functionsOnSuccess = [
        [onSuccessGetSuggestion, ['response']]
    ];
    ajax(url, method, functionsOnSuccess)
    suggestionPage++;
}
function onSuccessGetSuggestion(resp) {
    $('#suggestions').empty()
    $('#suggestions').html(resp.data)
}

function getMoreSuggestions() {
    let url = '/connections/get_suggestions?page=' + suggestionPage
    let method = 'get'
    var functionsOnSuccess = [
        [onSuccessGetMoreSuggestion, ['response']]
    ];
    ajax(url, method, functionsOnSuccess)
    suggestionPage++;
}
function onSuccessGetMoreSuggestion(resp) {
    if (resp.data == '') {
        $('#load_more_suggestion').addClass('d-none')
    }
    $('#suggestion_skeleton').removeClass('d-none')
    setTimeout(() => {
        $('#suggestion_skeleton').addClass('d-none')
        $('#suggestions').append(resp.data)
    }, 2000);
}

function sendRequest(userId, suggestionId) { // connect request
    let form = ajaxForm([
        ['suggestionId', suggestionId]
    ])
    let url = '/connection_requests'
    let method = 'POST'
    var functionsOnSuccess = [
        [onSuccessRequest, [suggestionId, 'response']]
    ];
    ajax(url, method, functionsOnSuccess, form)
}

function deleteRequest(userId, requestId) { //withdraw request
    let form = ajaxForm([
        ['requestId', requestId]
    ])
    let url = '/connection_requests/' + requestId
    let method = 'DELETE'
    var functionsOnSuccess = [
        [onSuccessDeleteRequest, [requestId, 'response']]
    ];
    ajax(url, method, functionsOnSuccess, form)
}

function acceptRequest(userId, requestId) {
    let form = ajaxForm([
        ['requestId', requestId]
    ])
    let url = '/connections'
    let method = 'post'
    var functionsOnSuccess = [
        [onSuccessAcceptRequest, [requestId, 'response']]
    ];
    ajax(url, method, functionsOnSuccess, form)
}

function removeConnection(userId, connectionId) {
    let form = ajaxForm([
        ['connectionId', connectionId]
    ])
    let url = '/connections/' + connectionId
    let method = 'DELETE'
    var functionsOnSuccess = [
        [onSuccessDeleteConnection, [connectionId, 'response']]
    ];
    ajax(url, method, functionsOnSuccess, form)
}
function onSuccessRequest(suggestionId, response) {
    if (response.success) {
        $('#suggestion_' + suggestionId).remove()
    }
}
function onSuccessDeleteRequest(requestId, response) {
    if (response.success) {
        $('#request_connection_' + requestId).remove()
    }
}
function onSuccessAcceptRequest(requestId, response) {
    if (response.success) {
        $('#request_connection_' + requestId).remove()
    }
}
function onSuccessDeleteConnection(requestId, response) {
    if (response.success) {
        $('#connections_' + requestId).remove()
    }
}

$(function () {
    $('#btnradio1').click(function () {
        suggestionPage = 1
        getSuggestions()
        $('#load_more_sent_requests').addClass('d-none')
        $('#load_more_suggestion').removeClass('d-none')
        $('#load_more_received_requests').addClass('d-none')
        $('#load_more_connections').addClass('d-none')

        $('#suggestions_parent').removeClass('d-none')
        $('#sent_requests_parent').addClass('d-none')
        $('#received_requests_parent').addClass('d-none')
        $('#connections_parent').addClass('d-none')
    })
    $('#btnradio2').click(function () {
        requestPage = 1
        getRequests('sent')
        $('#load_more_sent_requests').removeClass('d-none')
        $('#load_more_received_requests').addClass('d-none')
        $('#load_more_suggestion').addClass('d-none')
        $('#load_more_connections').addClass('d-none')

        $('#suggestions_parent').addClass('d-none')
        $('#sent_requests_parent').removeClass('d-none')
        $('#received_requests_parent').addClass('d-none')
        $('#connections_parent').addClass('d-none')
    })
    $('#btnradio3').click(function () {
        receivedPage = 1
        getRequests('received')
        $('#load_more_sent_requests').addClass('d-none')
        $('#load_more_suggestion').addClass('d-none')
        $('#load_more_received_requests').removeClass('d-none')
        $('#load_more_connections').addClass('d-none')


        $('#suggestions_parent').addClass('d-none')
        $('#sent_requests_parent').addClass('d-none')
        $('#received_requests_parent').removeClass('d-none')
        $('#connections_parent').addClass('d-none')
    })
    $('#btnradio4').click(function () {
        connectionPage = 1
        getConnections()

        $('#load_more_connections').removeClass('d-none')
        $('#load_more_sent_requests').addClass('d-none')
        $('#load_more_suggestion').addClass('d-none')
        $('#load_more_received_requests').addClass('d-none')

        $('#suggestions_parent').addClass('d-none')
        $('#sent_requests_parent').addClass('d-none')
        $('#received_requests_parent').addClass('d-none')
        $('#connections_parent').removeClass('d-none')
    })
    $(document).on('click', '.click_common_connection', function () {
        let id = $(this).data('id')
        commonConnectionPage = 1
        getConnectionsInCommon('', id)
    })
});
