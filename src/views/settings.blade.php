@extends('administrator::layout')

@section('module_header')
    Settings
@stop

@section('content')

    {!! Form::open() !!}

    <table class="table">
        @foreach($fieldFactory->getFields() as $field)
        <?php
            $object = $settings->where('key', $field->getName())->first();
            if ($object)
            {
                $field->setValue($object->value);
            }
        ?>
        <tr {{ $field->hasErrors() ? 'class="has-error"' : '' }}>
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
                <input type="submit" name="save" value="Save" class="btn btn-flat bg-purple" />
            </td>
        </tr>
    </table>

    {!! Form::close() !!}
@stop

@section('js')
    @include('administrator::partials.htmlhandlers')

    @include('administrator::partials.wsywyg')
@stop