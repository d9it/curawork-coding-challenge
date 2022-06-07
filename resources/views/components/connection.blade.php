<div class="my-2 shadow text-white bg-dark p-1" id="connections_{{ $connection->id }}">
  <div class="d-flex justify-content-between">
    <table class="ms-1">
      <td class="align-middle">{{ $connection->name ?? '' }}</td>
      <td class="align-middle"> - </td>
      <td class="align-middle">{{ $connection->email ?? '' }}</td>
      <td class="align-middle">
    </table>
    <div>
      <button style="width: 220px" id="get_connections_in_common_" class="btn btn-primary click_common_connection" type="button" data-id="{{ $connection->id }}" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $connection->id }}" aria-expanded="false" aria-controls="collapseExample">
        Connections in common ({{ count($connection->commonConnection) }})
      </button>
      <button id="create_request_btn_" class="btn btn-danger me-1" onclick="removeConnection('',{{ $connection->id }})">Remove Connection</button>
    </div>

  </div>
  <div class="collapse" id="collapse_{{ $connection->id }}">

    <div id="common_connection_{{ $connection->id }}" class="p-2">
      {{-- Display data here --}}
    </div>
    <div id="connections_in_common_skeletons_{{ $connection->id }}" class="d-none">
        @for ($i = 0; $i < 10; $i++)
            <x-skeleton />
        @endfor
    </div>
    <div class="d-flex justify-content-center w-100 py-2" id="load_more_suggestion_connections_in_common_{{ $connection->id }}">
      <button class="btn btn-sm btn-primary" onclick="getMoreConnectionsInCommon('','{{ $connection->id }}')">Load
        more</button>
    </div>
  </div>
</div>
