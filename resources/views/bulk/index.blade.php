@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row page-tctle-row">
        <div class="col-md-6">
            <h3>Bulk <small>» Listing</small></h3>
        </div>
        <div class="col-md-6 text-right">
          <a type="button" class="btn btn-success btn-md" href="{{ url('/bulk/create') }}" role="button">
            <i class="fa fa-plus-circle fa-lg"></i>
            Erase in Bulk
          </a>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">

            <table id="bulks-table" class="table table-striped row-border">
                <thead>
                <tr>
                    <th>Filename</th>
                    <th>Process ID</th>
                    <th>Result</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($bulks as $bulk)
                <tr>
                    <td>{{ $bulk->file_name }}</td>
                    <td>{{ $bulk->process_id }}</td>
                    <td>{{ $bulk->result }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@stop

@section('scripts')
<script>
    // DataTable
    $(function() {
        $("#bulks-table").DataTable({
            order: [[0, "desc"]]
        });
    });
</script>
@stop