@extends('layouts.app')

@section('styles')
    
@endsection

@section('content')
    <div id="app" class="wrapper_url_shortener d-flex justify-content-center align-items-center h-100">
        <url-shortener authorized="{{auth()->user()->id ?? null}}" ></url-shortener>
    </div>
@endsection