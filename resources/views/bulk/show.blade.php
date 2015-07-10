@extends('app')
@section('content')
<div class="container-fluid">
    <div class="row page-title-row">
        <div class="col-md-12">
            <h6 class="mac">{{ $bulk->file_name }}</h6>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <small class="mac-description">{{ $bulk->process_id }}</small>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">

            <table id="bulk-table" class="table table-striped row-border">
                <thead>
                <tr>
                    <th>Phone Name</th>
                    <th>IP Address</th>
                    <th>Type</th>
                    <th>Result</th>
                    <th>Fail Reason</th>
                    <th>Sent On</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($bulk->erasers as $eraser)
                <tr>
                    <td>{{ $eraser->Phone->mac }}</td>
                    <td>{{ $eraser->ip_address }}</td>
                    <td >
                        <i class="{{ $eraser->result == 'Success' ? 'fa fa-check' : 'fa fa-times' }}"></i>
                    </td>
                    <td>{{ $eraser->failure_reason}}</td>
                    <td>{{ $eraser->created_at->toDayDateTimeString()}}</td>
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
        $("#phone-table").DataTable({
            order: [[2, "desc"]]
        });
    });
</script>
@stop