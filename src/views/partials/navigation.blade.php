@if(config('administrator.show_user_panel', true))
    @include('administrator::partials/user_panel')
@endif

@if(config('administrator.show_search_bar', false))
    @include('administrator::partials/search_nav')
@endif

<!-- sidebar menu: : style can be found in sidebar.less -->

<div class="sidebar" id="scrollspy" style="overflow: hidden; width: auto">
    <ul class="sidebar-menu">
        @foreach($navigation->getPages() as $page => $options)
            @if(isset($options['page_header']))
                <li class="header">{{ strtoupper($options['page_header']) }}</li>
            @endif
            <!--todo: find better way instead array_key_exists() to show active nav -->
            <li class="{{ (isset($options['pages']) ? (array_key_exists($navigation->getCurrentModule(), $options['pages'])) ? 'treeview active' : '' : '') }}">
                <a href="{{ isset($options['link']) ? $options['link'] : '#' }}">
                    <i class="fa {{ $options['icon'] }}"></i>
                    <span>{{ $options['title'] }}</span>
                    @if (isset($options['pages']))
                        <i class="fa fa-angle-left pull-right"></i>
                    @endif
                </a>

                @if (isset($options['pages']))
                    <ul class="treeview-menu">
                        @foreach($options['pages'] as $page)
                            <li class="{{ ($page['page'] == $navigation->getCurrentModule() ? 'active' : '') }}"><a
                                        href="{{ $page['link'] }}">
                                    <i class="{{ $page['icon'] }}"></i>
                                    {{ $page['title'] }}</a></li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
</div>