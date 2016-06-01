<li class="dropdown">
    @if (count($settingsPages) > 1)
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="glyphicon glyphicon-cog"></i>
            <span>{{ trans('Settings') }} <i class="caret"></i></span>
        </a>
        <ul class="dropdown-menu">
            <!-- Menu Body -->
            <li class="user-body">
                @foreach($settingsPages as $page => $title)
                    <div class="col-xs-4 text-center">
                        <a href="{{ route('admin_settings_edit', ['page' => $page]) }}">{{ trans($title) }}</a>
                    </div>
                @endforeach
            </li>
        </ul>
    @else
        <?php list($page, $title) = each($settingsPages);?>
        <a href="{{ route('admin_settings_edit', ['page' => $page]) }}">
            <span>{{ trans('Settings') }}</span>
        </a>
    @endif
</li>