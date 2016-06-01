<!-- Validation errors -->

@if (isset($errors) && $errors->count())
    @foreach($errors->all() as $error)
        <div class="alert alert-danger alert-dismissable" style="margin: 15px 15px 0 15px; padding-left: 15px;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <b>Error!</b>
            {{ $error }}
        </div>
    @endforeach
@endif

<!-- Success messages -->
@if (Session::has('messages'))
    @foreach(Session::get('messages') as $message)
        <div class="alert alert-success alert-dismissable" style="margin: 15px 15px 0 15px; padding-left: 15px;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <b>Success!</b>
            {{ $message }}
        </div>
    @endforeach
@endif