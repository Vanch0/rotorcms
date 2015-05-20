@extends('layout')

@section('title', 'Ошибка 404 - @parent')

@section('content')

	<?php $images = glob(HOME.'/assets/img/errors/*.png'); ?>

	<div class="row">
		<div class="col-md-4 text-right">
			<img src="/assets/img/errors/{{ basename($images[array_rand($images)]) }}" alt="error 404" />
		</div>
		<div class="col-md-8">
			<h3>Ошибка 404!</h3>
			<div class="lead">Такой страницы не существует!</div>
		</div>
	</div>

@stop
