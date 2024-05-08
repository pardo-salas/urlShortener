@extends('layouts.app')

@section('styles')
    
@endsection

@section('content')
    <div id="app" class="wrapper_url_stats">
        <url-statics authorized="{{auth()->user()->id ?? null}}" ></url-statics>
    </div>
@endsection