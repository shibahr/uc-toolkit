@extends('app')

@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

<div class="row">
    <div class="col-md-12 text-center">
        <h1 class="login-header">Trust List Eraser</h1>
    </div>
</div>

<div class="login-form">
    <form class="" role="form" method="POST" action="{{ url('/auth/login') }}">

            <div class="form-group">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="input-group margin-bottom-sm">
                    <span class="input-group-addon"><i class="fa fa-envelope-o fa-fw"></i></span>
                    <input class="form-control" type="text" placeholder="Email address" name="email">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa fa-lock fa-fw"></i></span>
                    <input class="form-control" type="password" placeholder="Password" name="password">
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">
                    Log in
                </button>
            </div>

    </form>

</div>
@endsection
