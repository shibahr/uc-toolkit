@if (Session::has('flash_notification.message'))
<div class="alert text-center alert-{{ Session::get('flash_notification.level') }}">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

    {{ Session::get('flash_notification.message') }}
</div>
@endif