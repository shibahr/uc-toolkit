@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row page-title-row">
        <div class="col-md-6">
            <h3>Itl <small>» Listing</small></h3>
        </div>
        <div class="col-md-6 text-right">
            <a href="/itl/create" class="btn btn-success btn-md">
                <i class="fa fa-plus-circle"></i> Erase ITLs
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">

            <table id="itls-table" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Phone Name</th>
                    <th>Phone Description</th>
                    <th>IP Address</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($itls as $itl)
                <tr>
                    <td data-order="{{ $itl->created_at->timestamp }}">
                        {{ $itl->created_at->format('j-M-y g:ia') }}
                    </td>
                    <td>{{ $itl->title }}</td>
                    <td>{{ $itl->subtitle }}</td>
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
    $(function() {
        $("#itls-table").DataTable({
            order: [[0, "desc"]]
        });
    });
</script>
@stop