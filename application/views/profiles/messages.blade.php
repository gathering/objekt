<section class="comment-list block">
  <article class="comment-item media" id="comment-form">
    <section class="media-body">
      <form action="" class="m-b-none">
        <div class="input-group">
          <input type="text" placeholder="Input your comment here" class="form-control">
          <span class="input-group-btn">
            <button class="btn btn-primary" type="button">Send melding</button>
          </span>
        </div>
      </form>
    </section>
  </article>
  @foreach($profile->messages()->group_by('thread')->order_by('created_at', 'ASC')->get() as $thread)
  <article id="comment-thread-{{ $thread->thread }}" class="comment-item media arrow arrow-left">
    <a class="pull-left thumb-small"><span class="btn btn-circle btn-white btn-xs"><i class="fa fa-user"></i></span></a>
    <section class="media-body panel">
      <header class="panel-heading clearfix">
        {{ $thread->person()->firstname.' '.$thread->person()->surname }}
        <label class="label bg-info m-l-mini">{{ $thread->from_email }}</label> <b>{{ $thread->subject }}</b>
        <span class="text-muted m-l-small pull-right"><i class="fa fa-clock-o"></i> {{ Date::nice($thread->created_at) }}</span>
      </header>
      <div class="panel-body">
        <div>{{ $thread->html }}</div>
      </div>
    </section>
  </article>
    @foreach($thread->replys()->order_by('created_at', 'asc')->get() as $message)
    <article id="comment-thread-{{ $message->thread }}-{{ $message->id }}" class="comment-item comment-reply media arrow arrow-left">
      <a class="pull-left thumb-small"><span class="btn btn-circle btn-white btn-xs"><i class="fa fa-user"></i></span></a>
      <section class="media-body panel text-small">
        <header class="panel-heading clearfix">
          {{ $thread->person()->firstname.' '.$thread->person()->surname }}
          <label class="label bg-info m-l-mini">{{ $thread->from_email }}</label>
          <span class="text-muted m-l-small pull-right"><i class="fa fa-clock-o"></i> {{ Date::nice($thread->created_at) }}</span>
        </header>
        <div class="panel-body">
          <div>{{ $thread->html }}</div>
        </div>
      </section>
    </article>
    @endforeach
    <article class="comment-item media comment-reply" id="comment-form">
    <section class="media-body panel">
      <form method="post" action="{{ url('/profile/'.$profile->slug.'/messages/reply/'.$thread->thread) }}" class="m-b-none">
        <div class="input-group">
          <input type="text" name="text" placeholder="Input your comment here" class="form-control">
          <span class="input-group-btn">
            <button class="btn btn-primary" type="submit">Send melding</button>
          </span>
        </div>
      </form>
    </section>
  </article>
  @endforeach
</section>