@if ($mode == 'sent')
    <div class="my-2 shadow text-white bg-dark p-1" id="request_connection_{{ $connectionRequest->id }}">
        <div class="d-flex justify-content-between">
            <table class="ms-1">
                <td class="align-middle">{{ $connectionRequest->name ?? '' }}</td>
                <td class="align-middle"> - </td>
                <td class="align-middle">{{ $connectionRequest->email ?? '' }}</td>
                <td class="align-middle">
            </table>
            <div>
                <button id="cancel_request_btn_" class="btn btn-danger me-1"
                    onclick="deleteRequest('','{{ $connectionRequest->id }}')">Withdraw Request</button>
            </div>
        </div>
    </div>
@else
    <div class="my-2 shadow text-white bg-dark p-1" id="request_connection_{{ $receivedConnectionRequest->id }}">
        <div class="d-flex justify-content-between">
            <table class="ms-1">
                <td class="align-middle">{{ $receivedConnectionRequest->name ?? '' }}</td>
                <td class="align-middle"> - </td>
                <td class="align-middle">{{ $receivedConnectionRequest->email ?? '' }}</td>
                <td class="align-middle">
            </table>
            <div>
                <button id="accept_request_btn_" class="btn btn-primary me-1" onclick="acceptRequest('','{{ $receivedConnectionRequest->id }}')">Accept</button>
            </div>
        </div>
    </div>
@endif
