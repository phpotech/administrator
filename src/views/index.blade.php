@extends('administrator::layout')

@section('filter')
    @include('administrator::partials.filters')
@stop

@section('content')
<?php $queryString = $queryString->toString(); ?>
<form method="post" id="datagrid-form" action="{{ route('admin_model_global_action', ['page' => $modelName]) . $queryString }}">
    <?=Form::token()?>
    @if (! empty($globalActions))
    <div class="fluid-row">
        @foreach($globalActions as $action)
        <div class="pull-right">
            @if ($action->isReservedUrl())
            <a href="{{ route('admin_model_create', ['page' => $modelName]) . $queryString }}" class="btn btn-link">{{ $action->getTitle() }}</a>
            @else
                <button class="btn btn-link" name="action" value="{{ $action->getName() }}">{{ $action->getTitle() }}</button>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                @foreach($columns as $column)
                <th class="sorting_asc" data-column="{{ $column->getName() }}">{{ $column->getTitle() }}</th>
                @endforeach

                @if ($hasActions)
                <th class="actions" style="width: 10%;">
                    Actions
                </th>
                @endif
            </tr>
        </thead>

        <tbody>
            @foreach($items as $item)
            <tr>
                @foreach($columns as $column)
                <td>
                    @if(is_a($column, 'Keyhunter\Administrator\Columns\Column'))
                     {!! $column->getFormatted($item) !!}
                    @else
                     <ul class="list-unstyled">
                        @foreach($column->getElements() as $colInGroup)
                        <?php $value = $colInGroup->getFormatted($item); ?>
                        @if($value)
                        <li>
                            @if ($colInGroup->isStandalone())
                            <strong>{!! $value !!}</strong>
                            @else
                            <label for="{{ $colInGroup->getName() }}">{{ $colInGroup->getTitle() }}:</label>
                            {!! $value !!}
                            @endif
                        </li>
                        @endif
                        @endforeach
                     </ul>
                    @endif
                </td>
                @endforeach
                @if ($hasActions)
                <td class="actions">
                    <?php
                    $actions = $actionFactory->getActions($item);
                    ?>
                    <ul class="list-unstyled">
                        @foreach($actions as $action)
                        <li><a {!! $action->getConfirmation() !!}
                                href="{{ $action->getUrl($item->id, $action->getName()) . $queryString }}"
                                data-action="{{ $action->getName() }}">
                                {{ $action->getTitle() }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</form>
    {!! $items->render() !!}
@endsection