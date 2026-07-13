<?php

$forumTitle = $dynamicKeys->settingOrTranslation('forum_title');

?>

@extends('flarum.forum::error.default')

@section('content')
	<h2>{{ $message }}</h2>
	<div class="not-centered">
		<p>{{ $translator->trans('glowingblue-localizd.forum.errors.causes') }}
		<ul>
			<li>{{ $translator->trans('glowingblue-localizd.forum.errors.does_not_exist') }}</li>
			<li>{{ $translator->trans('glowingblue-localizd.forum.errors.deleted') }}</li>
			<li>{{ $translator->trans('glowingblue-localizd.forum.errors.logged_in_only') }}</li>
			<li>{{ $translator->trans('glowingblue-localizd.forum.errors.no_permission') }}</li>
		</ul>
	</div>
	<p>
		<a href="{{ $url->to('forum')->base() }}">
			{{ $translator->trans('core.views.error.not_found_return_link', ['forum' => $forumTitle]) }}
		</a>
	</p>
@endsection
