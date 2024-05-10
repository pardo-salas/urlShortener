@extends('layouts.app')

@section('styles')
    
@endsection

@section('content')
    <div id="app" class="wrapper_url_dashboard">
        <url-dashboard links="{{$links}}" authorized="{{auth()->user()->id ?? null}}" ></url-dashboard>
    </div>
@endsection