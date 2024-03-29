@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row page-title-row">
        <div class="col-md-12">
            <h6 class="mac">{{ $phone->mac }}</h6>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <small class="mac-description">{{ $phone->description }}</small>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">

            <table id="phone-table" class="table table-striped row-border">
                <thead>
                <tr>
                    <th>IP Address</th>
                    <th>Type</th>
                    <th>Result</th>
                    <th>Fail Reason</th>
                    <th>Sent On</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($phone->eraser as $attempt)
                <tr>
                    <td>{{ $attempt->ip_address }}</td>
                    <td>{{ strtoupper($attempt->eraser_type) }}</td>
                    <td >
                        <i class="{{ $attempt->result == 'Success' ? 'fa fa-check' : 'fa fa-times' }}"></i>
                    </td>
                    <td>{{ $attempt->failure_reason}}</td>
                    <td>{{ $attempt->created_at->toDayDateTimeString()}}</td>
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