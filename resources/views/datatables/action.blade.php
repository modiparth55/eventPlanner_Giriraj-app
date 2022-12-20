<a href="{{ url('event', [$event->id]) }}" data-id={{ $event->id }} class="ui blue button  edit_btn">Edit</a>
<a href="{{ url('event', [$event->id]) }}" data-id={{ $event->id }} class="ui teal button show_btn">Show</a>
<a href="{{ url('event', [$event->id]) }}" data-route="{{ route('event.destroy', $event->id) }}"
    data-id={{ $event->id }} class="ui red button delete_btn">Delete</a>
