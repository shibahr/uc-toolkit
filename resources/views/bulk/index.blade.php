@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row page-tctle-row">
        <div class="col-md-6">
            <h3>Bulk <small>» Eraser</small></h3>
        </div>

    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Bulk Eraser Upload</h3>
                </div>
                <div class="panel-body">
                    <div class="col-md-8 col-md-offset-2 text-center">
                        <form method="POST" action="/bulk"
                              class="form-horizontal" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="modal-header">
                                <h4 class="modal-title"></h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="file" class="col-sm-3 control-label">
                                        File
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="file" id="file" name="file">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="file_name" class="col-sm-6 control-label">
                                        Optional Filename
                                    </label>
                                    <div class="col-sm-6 pull-left">
                                        <input type="text" id="file_name" name="file_name"
                                               class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    Upload File
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@stop