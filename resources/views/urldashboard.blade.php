@extends('layouts.app')

@section('styles')
    
@endsection

@section('content')
    <div id="app" class="wrapper_url_dashboard d-flex justify-content-center align-items-center h-100">
        <url-dashboard links="{{$links?? null}}" authorized="{{auth()->user()->id ?? null}}" ></url-dashboard>
    </div>
@endsection