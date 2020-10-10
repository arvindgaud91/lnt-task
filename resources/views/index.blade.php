@extends('layouts.app')
 
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb mt-3">
            <div class="float-left">
                <h2>List</h2>
            </div>
            <div class="float-right">
                <a class="btn btn-sm btn-info" href="{{ route('task.create') }}"> Create New Task</a>
            </div>
        </div>
    </div>
   <div id="alert">
    @if ($message = Session::get('success'))
        <div class="alert alert-success mt-1">
            <p>{{ $message }}</p>
        </div>
    @endif
   </div>
   
    <table class="table table-striped" id="router">
        <thead>
            <tr>
                <th>SAP ID</th>
                <th>Host Name</th>
                <th>IP Address</th>
                <th>MAC Address</th>
                <th width="280px">Action</th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
        <tfoot>
            <tr>
                <th>SAP ID</th>
                <th>Host Name</th>
                <th>IP Address</th>
                <th>MAC Address</th>
                <!-- <th>Action</th> -->
            </tr>
        </tfoot>
    </table>
      
@endsection
@section('scripts')
<script type="text/javascript">
$(document).ready(function(){
  $('#router tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );

  var table = $('#router').DataTable({
    "paging": true,
    "processing": true,
    "serverSide": true,
    "lengthMenu": [25, 50, 75, 100],
    "responsive": true,
    "lengthChange": true,
    "searching": true,

    "ajax": {
      "url": "{{route('task.index')}}",
      "type": "GET",
    },

    "columns": [{
        "data": "sap_id",
      },
      {
        "data": "host_name",
      },
      {
        "data": "loop_back",
      },
      {
        "data": "mac_address",
      },
      {
        "data": "action",
        sortable: false,
        filterable: false,
      },
    ],

    initComplete: function () {
      // Apply the search
      this.api().columns().every( function () {
        var that = this;

        $( 'input', this.footer() ).on( 'keyup change clear', function () {
          if ( that.search() !== this.value ) {
            that.search( this.value ).draw();
          }
        });
      });
    }
  });

  $(document).on('click', '.delete-task', function(){
    var elementRef = $(this);
    var id = elementRef.attr('data-id');
    url = "{{route('task.destroy',":id")}}";
    url = url.replace(':id', id);
    $.ajax({
      type: 'delete',
      url: url,
      headers: {
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
      },
      success: function(response) {
        table.draw();
        $('#alert').html('<div class="alert alert-success mt-1"><p>'+response.message+'</p></div>');
      },
      error: function(error) {
        $('#alert').html('<div class="alert alert-danger mt-1"><p>'+error.responseJSON.message+'</p></div>');
      }
    });
  });
});
</Script>
@endsection