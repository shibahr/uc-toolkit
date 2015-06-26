@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row page-title-row">
        <div class="col-md-6">
            <h3>Itl <small>» Listing</small></h3>
        </div>
        <div class="col-md-6 text-right">
            <button type="button" class="btn btn-success btn-md"
                      onclick="erase_itl()">
                <i class="fa fa-plus-circle fa-lg"></i>
                Erase ITLs
          </button>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">

            <table id="itls-table" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Phone Name</th>
                    <th>Phone Description</th>
                    <th>Created At</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($itls as $itl)
                <tr>
                    <td>{{ $itl->phone->mac }}</td>
                    <td>{{ $itl->phone->description}}</td>
                    <td data-order="{{ $itl->created_at->timestamp }}">
                        {{ $itl->created_at->format('j-M-y g:ia') }}
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@stop

@include('itl._modal')

@section('scripts')
<script>
    // Confirm file delete
    function erase_itl() {
      $("#modal-erase-itl").modal("show");
    }
    // DataTable
    $(function() {
            $("#itls-table").DataTable({
                order: [[0, "desc"]]
            });
        });
</script>
@stop