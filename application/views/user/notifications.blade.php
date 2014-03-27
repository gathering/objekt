<section class="panel">
  <header class="panel-heading">
    {{ __('user.notifications') }}
  </header>
  <ul class="list-group">
    @foreach($notifications as $notification)
    <li class="list-group-item">
      <div class="media">
        <div class="media-body">
          <div>
            @if(!empty($notification->url))
            <a href="{{ $notification->url }}">{{ $notification->title }}<br />
            {{ $notification->message }}</a>
            @else
            <b>{{ $notification->title }}</b><br />
            {{ $notification->message }}
            @endif            
          </div>
          <small class="text-muted">{{ date::nice($notification->created_at) }}</small>
        </div>
      </div>
    </li>
    @endforeach
  </ul>
</section>