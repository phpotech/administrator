@if (isset($filter) && count($filter))
<section class="content" id="scaffold-filter" style="min-height: 0">
    <form action="">
        @foreach($queryString->toArray() as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}" />
        @endforeach
        <div class="row">
            <div class="col-xs-12">
                <div class="row">
                    @foreach($filter as $element)
                        @if($element->getType() != 'hidden')
                            <div class="col-xs-3">
                                <div class="form-group">
                                    {!! $element->html() !!}
                                </div>
                            </div>
                        @else
                            {!! $element->html() !!}
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="col-xs-3" style="float: right">
                <input type="submit" value="Search" class="btn btn-flat bg-purple" style="width: 48%; float: left"/>

                <a href="{{ route('admin_model_index', ['page' => app()->make('scaffold.module')->get('page')]) }}"
                   class="btn btn-flat bg-purple" style="width: 48%; float: right">Reset</a>
            </div>
        </div>
    </form>
</section>

@section('js')
@include('administrator::partials.htmlhandlers')
@stop

@endif