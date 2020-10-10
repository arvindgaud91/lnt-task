@extends('layouts.app')
 
@section('content')
<h1>Geomatric Shape</h1>
<div class="row">
    <div class="col-6">
    <img src="{{route('shape1')}}" alt="Shape 1"> 
  </div>
  <div class="col-6">
    <img src="{{route('shape2')}}" alt="Shape 2">
  </div>
</div>
<div class="row mt-2">
  <div class="col-6">
    <img src="{{route('shape3')}}" alt="Shape 3">
  </div>
</div>
@endsection