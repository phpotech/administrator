@extends('administrator::layout')

@section('module_header')
Edit
@stop

@section('content')

<form method="post" action="{{ URL::current() . $queryString->toString() }}" enctype="multipart/form-data">
<?=Form::token()?>
<table class="table">
    @foreach($fieldFactory->getFields() as $field)
    <tr class="{{ $field->hasErrors() ? 'has-error' : '' }} {{ ($field instanceof \Keyhunter\Administrator\Form\HiddenElement ? 'hidden' : '') }}">
        <td style="width: 20%; min-width: 200px;">
            {!! Form::label($field->getName(), $field->getLabel()) !!}:
            @if ($field->getDescription())
            <p class="small">{!! $field->getDescription() !!}</p>
            @endif
        </td>
        <td>
            {!! $field->html() !!}
        </td>
    </tr>
    @endforeach

    <tr>
        <td colspan="2" class="text-center">
            <input type="submit" name="save"        value="Save" class="btn btn-flat bg-purple" />
            <input type="submit" name="save_return" value="Save &amp; Return" class="btn btn-flat bg-purple" />
            <input type="submit" name="save_create" value="Save &amp; Create new" class="btn btn-flat" />
        </td>
    </tr>
</table>

</form>
@stop

@section('js')
@include('administrator::partials.htmlhandlers')
@include('administrator::partials.wsywyg')
@stop