@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row page-tctle-row">
        <div class="col-md-6">
            <h3>SQL <small>» History</small></h3>
        </div>
    </div>
</div>

@if(isset($sqls))
<div class="row">
    <div class="col-sm-12">
        <table id="history-table" class="table table-striped row-border">
            <thead>
                <tr>
                    <th>SQL Statement</th>
                </tr>
            </thead>
            <tbody>
            @foreach($sqls as $sql)
                <tr>
                    <td>{{ $sql->sql }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@stop

@section('scripts')
<script>

    // DataTable
    $(function() {
        $("#history-table").DataTable({
            order: [[0, "asc"]]
        });
    });
</script>
@stop