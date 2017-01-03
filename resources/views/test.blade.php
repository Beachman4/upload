@extends('layouts.app')
@section('content')
    <form method="post" action="/upload" enctype="multipart/form-data">
        <input type="hidden" name="apiKey" value="{{ Auth::User()->apiKey }}">
        <input type="file" name="file">
        <button type="submit">Submit</button>
    </form>
@stop