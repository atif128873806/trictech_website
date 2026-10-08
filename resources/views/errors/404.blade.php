@extends('layouts.app')

@section('title', 'Page Not Found | Tricetech Solutions')
@section('description', 'The requested Tricetech Solutions page could not be found.')

@section('content')
<section class="not-found shell">
    <p class="error-code">404</p><h1>This page is not available.</h1>
    <p>The address may have changed. Continue to the company overview or choose a service area.</p>
    <div class="hero-actions"><a class="button" href="{{ route('home') }}">Return to company overview</a><a class="text-link" href="{{ route('infrastructure') }}">View infrastructure services →</a></div>
</section>
@endsection
