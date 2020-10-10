@extends('layouts.app')
   
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Product</h2>
            </div>
            <div class="float-right">
                <a class="btn btn-primary" href="{{ route('task.index') }}"> Back</a>
            </div>
        </div>
    </div>
   
    @if ($errors->any())
    <div class="alert alert-danger mt-1">
      <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
      </ul>
    </div><br/>
    @endif
  
    <form action="{{ route('task.update',$task->id) }}" method="POST">
      @csrf
      @method('PATCH')
   
      <div class="row">
        <div class="form-group col-6">
          <label for="uname">Sap ID:</label>
          <input type="text" class="form-control" id="sap_id" maxlength="18" value="{{ $task->sap_id }}" placeholder="Enter Sap ID" name="sap_id" required>
        </div>
        <div class="form-group col-6">
          <label for="pwd">Host Name:</label>
          <input type="text" class="form-control" id="host_name" maxlength="14" value="{{ $task->host_name }}" placeholder="Enter Host Name" name="host_name" required>
        </div>
        <div class="form-group col-6">
          <label for="uname">IP address:</label>
          <input type="text" class="form-control" id="loop_back" value="{{ $task->loop_back }}" placeholder="Enter IP address" name="loop_back" required>
        </div>
        <div class="form-group col-6">
          <label for="pwd">Mac address:</label>
          <input type="text" class="form-control" id="mac_address" maxlength="17" value="{{ $task->mac_address }}" placeholder="Enter Mac address" name="mac_address" required>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection