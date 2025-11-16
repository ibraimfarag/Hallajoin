<?php
$menus = [
    'admin' => [
        'url' => route('admin.index'),
        'title' => __("Dashboard"),
        'icon' => 'icon ion-md-home',
        "position" => 0
    ],
    'members' => [
        'url' => '#',
        'title' => __("User"),
        'icon' => 'icon ion-ios-people',
        'position' => 12,
        'children' => [
            'users_list' => [
                'url' => route('admin.users.index'),
                'title' => __('List'),
                'icon' => 'fa fa-list',
            ],
            'users_cart' => [
                'url' => route('admin.carts.index'),
                'title' => __('Cart'),
                'icon' => 'fa fa-shopping-cart',
            ],
            'users_favourites' => [
                'url' => route('admin.favourites.index'),
                'title' => __('Bookmarks'),
                'icon' => 'fa fa-heart',
            ],
            'users_balance' => [
                'url' => route('admin.balance.index'),
                'title' => __('Balance'),
                'icon' => 'fa fa-money',
            ],

        ]
    ],
    'menu' => [
        "position" => 60,
        'url' => route('core.admin.menu.index'),
        'title' => __("Menu"),
        'icon' => 'icon ion-ios-apps',
        'permission' => 'menu_view',
    ],

    'marketing' => [
        "position" => 21,
        'url' => '#',
        'title' => __("Marketing"),
        'icon' => 'icon ion-ios-megaphone',
        'children' => []
    ],
    'general' => [
        "position" => 80,
        'url' => route('core.admin.settings.index', ['group' => 'general']),
        'title' => __('Setting'),
        'icon' => 'icon ion-ios-cog',
        'permission' => 'setting_update',
        'children' => \Modules\Core\Models\Settings::getSettingPages(true)
    ],
    'tools' => [
        "position" => 90,
        'url' => route('core.admin.tool.index'),
        'title' => __("Tools"),
        'icon' => 'icon ion-ios-hammer',
        'children' => [
            'language' => [
                'url' => route('language.admin.index'),
                'title' => __('Languages'),
                'icon' => 'icon ion-ios-globe',
                'permission' => 'language_manage',
            ],
            'currency' => [
                'url' => route('admin.currency.index'),
                'title' => __('Currency Settings'),
                'icon' => 'fa fa-dollar-sign',
                'permission' => 'setting_update',
            ],
            'translation' => [
                'url' => route('language.admin.translations.index'),
                'title' => __("Translation Manager"),
                'icon' => 'icon ion-ios-globe',
                'permission' => 'language_translation',
            ],
            'logs' => [
                'url' => route('admin.logs'),
                'title' => __("System Logs"),
                'icon' => 'icon ion-ios-nuclear',
                'permission' => 'system_log_view',
            ],
        ]
    ],
];

// Modules
$custom_modules = \Modules\ServiceProvider::getActivatedModules();
if (!empty($custom_modules)) {
    $custom_modules[] = [
        'id' => 'theme',
        'class' => \Modules\Theme\ModuleProvider::class
    ];
    foreach ($custom_modules as $moduleData) {
        $module = $moduleData['id'];
        $moduleClass = $moduleData['class'];
        if (class_exists($moduleClass)) {
            $menuConfig = call_user_func([$moduleClass, 'getAdminMenu']);

            if (!empty($menuConfig)) {
                $menus = array_merge($menus, $menuConfig);
            }

            $menuSubMenu = call_user_func([$moduleClass, 'getAdminSubMenu']);

            if (!empty($menuSubMenu)) {
                foreach ($menuSubMenu as $k => $submenu) {
                    $submenu['id'] = $submenu['id'] ?? '_' . $k;

                    if (!empty($submenu['parent']) and isset($menus[$submenu['parent']])) {
                        $menus[$submenu['parent']]['children'][$submenu['id']] = $submenu;
                        $menus[$submenu['parent']]['children'] = array_values(\Illuminate\Support\Arr::sort($menus[$submenu['parent']]['children'], function ($value) {
                            return $value['position'] ?? 100;
                        }));
                    }
                }
            }
        }
    }
}
// dd($custom_modules);
// Plugins Menu
$plugins_modules = \Plugins\ServiceProvider::getModules();
if (!empty($plugins_modules)) {
    foreach ($plugins_modules as $module) {
        $moduleClass = "\\Plugins\\" . ucfirst($module) . "\\ModuleProvider";
        if (class_exists($moduleClass)) {
            $menuConfig = call_user_func([$moduleClass, 'getAdminMenu']);
            if (!empty($menuConfig)) {
                $menus = array_merge($menus, $menuConfig);
            }
            $menuSubMenu = call_user_func([$moduleClass, 'getAdminSubMenu']);
            if (!empty($menuSubMenu)) {
                foreach ($menuSubMenu as $k => $submenu) {
                    $submenu['id'] = $submenu['id'] ?? '_' . $k;
                    if (!empty($submenu['parent']) and isset($menus[$submenu['parent']])) {
                        $menus[$submenu['parent']]['children'][$submenu['id']] = $submenu;
                        $menus[$submenu['parent']]['children'] = array_values(\Illuminate\Support\Arr::sort($menus[$submenu['parent']]['children'], function ($value) {
                            return $value['position'] ?? 100;
                        }));
                    }
                }
            }
        }
    }
}
// dd($plugins_modules);
// Custom Menu
$custom_modules = \Custom\ServiceProvider::getModules();
if (!empty($custom_modules)) {
    foreach ($custom_modules as $module) {
        $moduleClass = "\\Custom\\" . ucfirst($module) . "\\ModuleProvider";
        if (class_exists($moduleClass)) {
            $menuConfig = call_user_func([$moduleClass, 'getAdminMenu']);

            if (!empty($menuConfig)) {
                $menus = array_merge($menus, $menuConfig);
            }

            $menuSubMenu = call_user_func([$moduleClass, 'getAdminSubMenu']);

            if (!empty($menuSubMenu)) {
                foreach ($menuSubMenu as $k => $submenu) {
                    $submenu['id'] = $submenu['id'] ?? '_' . $k;
                    if (!empty($submenu['parent']) and isset($menus[$submenu['parent']])) {
                        $menus[$submenu['parent']]['children'][$submenu['id']] = $submenu;
                        $menus[$submenu['parent']]['children'] = array_values(\Illuminate\Support\Arr::sort($menus[$submenu['parent']]['children'], function ($value) {
                            return $value['position'] ?? 100;
                        }));
                    }
                }
            }
        }
    }
}

// dd($custom_modules);


$typeManager = app()->make(\Modules\Type\TypeManager::class);
$menuConfig = $typeManager->adminMenus();

$menus = array_merge($menus, $menuConfig);



$currentUrl = request()->url();
$user = \Illuminate\Support\Facades\Auth::user();

if (!empty($menus)) {
    foreach ($menus as $k => $menuItem) {

        // Check if the title is "Themes" or "User Plans" and skip these menu items
        if (!empty($menuItem['title']) && ($menuItem['title'] === 'Themes' || $menuItem['title'] === 'User Plans ' || $menuItem['title'] === 'News' || $menuItem['title'] === 'Payouts ')) {
            unset($menus[$k]);
            continue;
        }

        if (!empty($menuItem['permission']) && !$user->hasPermission($menuItem['permission'])) {
            unset($menus[$k]);
            continue;
        }

        $menus[$k]['class'] = $currentUrl == url($menuItem['url']) ? 'active' : '';

        if (!empty($menuItem['children'])) {
            $hasActiveChild = false;

            foreach ($menuItem['children'] as $k2 => $menuItem2) {
                if (!empty($menuItem2['permission']) && !$user->hasPermission($menuItem2['permission'])) {
                    unset($menus[$k]['children'][$k2]);
                    continue;
                }

                $isChildActive = $currentUrl == url($menuItem2['url']);
                $menus[$k]['children'][$k2]['class'] = $isChildActive ? 'active' : '';

                if ($isChildActive) {
                    $hasActiveChild = true;
                }
            }

            // Add has-children class first, then active if any child is active
            $menus[$k]['class'] .= ' has-children';
            if ($hasActiveChild) {
                $menus[$k]['class'] .= ' active';
            }
        }
    }

    //@todo Sort Menu by Position
    $menus = array_values(\Illuminate\Support\Arr::sort($menus, function ($value) {
        return $value['position'] ?? 100;
    }));
}


// dd($menus );
?>
<ul class="main-menu pb-5">
    @foreach($menus as $menuItem)
    @php
    $finalClass = trim($menuItem['class']);
    @endphp
    <li class="{{$finalClass}}"><a href="{{ url($menuItem['url']) }}">
            @if(!empty($menuItem['icon']))
            <span class="icon text-center"><i class="{{$menuItem['icon']}}"></i></span>
            @endif
            {!! clean($menuItem['title'], [
            'Attr.AllowedClasses' => null
            ]) !!}
        </a>
        @if(!empty($menuItem['children']))
        <span class="btn-toggle"><i class="fa fa-angle-left pull-right"></i></span>
        <ul class="children">
            @foreach($menuItem['children'] as $menuItem2)
            <li class="{{$menuItem2['class']}}"><a href="{{ url($menuItem2['url']) }}">
                    @if(!empty($menuItem2['icon']))
                    <i class="{{$menuItem2['icon']}}"></i>
                    @endif
                    {!! clean($menuItem2['title'], [
                    'Attr.AllowedClasses' => null
                    ]) !!}</a>
            </li>
            @endforeach
        </ul>
        @endif
    </li>
    @endforeach
</ul>