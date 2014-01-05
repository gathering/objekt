<section class="panel">
	<header class="panel-heading">{{ __('user.delete_user') }}</header>
	<div class="panel-body">
		<p>
			{{ __('user.delete_are_you_sure') }}
		</p>
		<p>
			<form method="post">
				<button type="submit" class="btn btn-black btn-block">{{ __('user.delete_yes') }} {{ $user->username }}!</button>
			</form>
		</p>
	</div>
</section>