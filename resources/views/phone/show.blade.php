@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row page-title-row">
        <div class="col-md-12">
            <h5>MAC</h5>
            <h6>{{ $phone->mac }}</h6>
        </div>
    </div>
    <div class="row page-title-row">
        <div class="col-md-12">
            <h5>Description</h5>
            <h6>{{ $phone->description }}</h6>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">

            <table id="phone-table" class="table table-striped row-border">
                <thead>
                <tr>
                    <th>IP Address</th>
                    <th>ETL Type</th>
                    <th>Sent On</th>
                    <th>Result</th>
                    <th>Fail Reason</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($phone->itl as $itl)
                <tr>
                    <td>{{ $itl->ip_address }}</td>
                    <td>ITL</td>
                    <td>{{ $itl->created_at->toDayDateTimeString()}}</td>
                    <td >
                        <i class="{{ $itl->result == 'Success' ? 'fa fa-check' : 'fa fa-times' }}"></i>
                    </td>
                    <td>{{ $itl->failure_reason}}</td>
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