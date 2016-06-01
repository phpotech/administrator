<?php namespace spec\Keyhunter\Administrator;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\UrlGenerator;
use Mockery;
use PhpSpec\Laravel\LaravelObjectBehavior;
//use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Keyhunter\Administrator\Guard;

class NavigationSpec extends LaravelObjectBehavior
{
    function let(Guard $guard)
    {
        $guard->isPermissionGranted(true)->willReturn(true);

        $application = app();
        $generator = Mockery::mock(
            new UrlGenerator(new RouteCollection(), new Request()),
            function($mock)
            {
                $mock
                    ->shouldReceive('route')
                    ->with('admin_model_index', ['model' => 'members'], true)
                    ->andReturn('admin/members')
                    ->zeroOrMoreTimes();

                $mock->shouldReceive('route')
                    ->with('admin_model_index', ['model' => 'admins'], true)
                    ->andReturn('admin/admins')
                    ->zeroOrMoreTimes();
            }
        );

        $this->beConstructedWith($application, $generator, $guard);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Keyhunter\Administrator\Navigation');
    }

    function it_does_not_have_pages_by_default()
    {
        $this->getPages()->shouldBeNull();
    }

    function it_accepts_simple_array_as_single_page()
    {
        $this->setPages(['members']);

        $this->getPages()->shouldBe([
            'members' => [
                'title' => 'Members',
                'model' => 'members',
                'link'  => "admin/members",
                'icon'  => "fa-angle-double-right"
            ]
        ]);
    }

    function it_accepts_groups_of_elements()
    {
        $this->setPages([
            'Roles' => [
                'icon' => 'fa-folder',
                'pages' => [
                    'admins',
                    'members',
                ]
            ]
        ]);

        $this->getPages()->shouldBe([
            'Roles' => [
                'title' => "Roles",
                'icon' => "fa-folder",
                'pages' => [
                    'admins' => [
                        'title' => "Admins",
                        'model' => "admins",
                        'link'  => "admin/admins",
                        'icon'  => "fa-angle-double-right",
                    ],
                    'members' => [
                        'title' => "Members",
                        'model' => "members",
                        'link'  => "admin/members",
                        'icon'  => "fa-angle-double-right",
                    ]
                ]
            ]
         ]);
    }

    function it_does_not_allow_forbidden_items(Guard $guard)
    {
        $guard
            ->isPermissionGranted(true)
            ->shouldBeCalled()
            ->willReturn(false);

        $this->setPages(['members']);

        $this->getPages()->shouldHaveCount(0);
    }

    function it_does_not_allow_forbidden_groups(Guard $guard)
    {
        $guard
            ->isPermissionGranted(false)
            ->shouldBeCalled();

        $this->setPages([
            'Users' => [
                'pages'      => ['members'],
                'permission' => false
            ]
        ]);

        $this->getPages()->shouldHaveCount(0);
    }
}
