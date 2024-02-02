<?php

namespace App\Support;

class Base
{
    public static function getInstance()
    {
        return new static;
    }

    public static function getModules($moduleName = false)
    {
        $modules = [];
        foreach (config('backendmenu') as $menu) {
            if (!empty($menu['name']) && !empty($menu['title'])) {
                $name = $menu['name'];
                $title = __($menu['title']);
                $modules[$name] = $title;
                if (!empty($menu['submenu'])) {
                    foreach ($menu['submenu'] as $submenu) {
                        $subname = $submenu['name'];
                        $subtitle = __($submenu['title']);
                        // $modules[$subname] = $title . '/' . $subtitle;
                        $modules[$subname] = $subtitle;
                        if (!empty($submenu['submenu'])) {
                            foreach ($submenu['submenu'] as $submenu2) {
                                $subname2 = $submenu2['name'];
                                $subtitle2 = __($submenu2['title']);
                                $modules[$subname2] = $title . '/' . $subtitle . '/' . $subtitle2;
                            }
                        }
                    }
                }
            }
        }
        $modules['auth_login'] = 'Auth/Login';
        $modules['auth_logout'] = 'Auth/Logout';

        if ($moduleName === false) {
            return $modules;
        }

        return $modules[$moduleName] ?? null;
    }

    public static function getModulesMain($moduleName = false)
    {
        $modules = [];
        foreach (config('backendmenu') as $menu) {
            if (!empty($menu['name']) && !empty($menu['title']) && $menu['name'] != 'dashboard') {
                $name = $menu['name'];
                $title = __($menu['title']);
                $modules[$name] = $title;
                // if (!empty($menu['submenu'])) {
                //     foreach ($menu['submenu'] as $submenu) {
                //         $subname = $submenu['name'];
                //         $subtitle = __($submenu['title']);
                //         $modules[$subname] = $title . '/' . $subtitle;
                //         if (!empty($submenu['submenu'])) {
                //             foreach ($submenu['submenu'] as $submenu2) {
                //                 $subname2 = $submenu2['name'];
                //                 $subtitle2 = __($submenu2['title']);
                //                 $modules[$subname2] = $title . '/' . $subtitle . '/' . $subtitle2;
                //             }
                //         }
                //     }
                // }
            }
        }
        $modules['auth_login'] = 'Auth/Login';
        $modules['auth_logout'] = 'Auth/Logout';

        if ($moduleName === false) {
            return $modules;
        }

        return $modules[$moduleName] ?? null;
    }

    public static function getModulesPerms()
    {
        $modules = [];
        foreach (config('backendmenu') as $menu) {
            if (empty($menu['section'])) {
                if (!empty($menu['perms'])) {
                    $modules[] = [
                        'perms' => $menu['perms'],
                        'title' => __($menu['title']),
                    ];
                }

                // Submenu 1
                if (!empty($menu['submenu'])) {
                    foreach ($menu['submenu'] as $sub1) {
                        if (!empty($sub1['perms'])) {
                            $modules[] = [
                                'perms' => $sub1['perms'],
                                'title' => __($menu['title']) . '/' . __($sub1['title'])
                            ];
                        }
                        // Submenu 2
                        if (!empty($sub1['submenu'])) {
                            foreach ($sub1['submenu'] as $sub1) {
                                if (!empty($sub2['perms'])) {
                                    $modules[] = [
                                        'perms' => $sub2['perms'],
                                        'title' => __($menu['title']) . '/' . __($sub1['title']) . '/' . ($sub2['title'])
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }

        return $modules;
    }

    public static function renderMenuTree($menu)
    {
        $user = auth()->user();
        if (empty($menu['url'])) {
            $menu['url'] = 'javascript:;';
        }

        if (!empty($menu['section'])) {
            $str = '<li class="nav-header menu-section">
                        <h4 class="menu-text">' . __($menu['section']) . '</h4>
                        <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
                    </li>';
            if ($menu['name'] == 'console_admin') {
                if (!$user->hasPermissionTo('setting.view') && !$user->hasPermissionTo('master.view')) {
                    $str = '';
                }
            }
        } elseif (!empty($menu['submenu'])) {
            $first = '<li class="has-sub" data-title="' . __($menu['title']) . '">
                        <a href="' . $menu['url'] . '" class="' . ($menu['url'] !== 'javascript:;' ? 'base-content--replace' : '') . ' menu-link" data-name="' . $menu['name'] . '">';
            if ($menu['url'] === 'javascript:;') {
                $first .= '<b class="caret width-10px"></b>';
            }
            if (!empty($menu['icon'])) {
                $first .= static::renderIcon($menu['icon']);
            }
            $first .= '<span>' . __($menu['title']) . '</span>
                        </a>';
            $first .= '<ul class="sub-menu">';
            $sub = '';
            foreach ($menu['submenu'] as $item) {
                if (isset($item['perms']) && !$user->hasPermissionTo($item['perms'] . '.view')) {
                    continue;
                }
                $sub .= static::renderMenuTree($item);
            }
            $last = '</ul>';
            $last .= '</li>';
            $str = !empty($sub) ? $first . '' . $sub . '' . $last : '';
        } else {
            $menuActive = (request()->path() == $menu['url']) ? 'active' : '';
            $str = '<li class="' . $menuActive . '" data-title="' . __($menu['title']) . '">
                        <a href="' . $menu['url'] . '" class="' . (empty($menu['reload']) ? 'base-content--replace' : '') . ' menu-link" data-name="' . $menu['name'] . '">';
            if (!empty($menu['icon'])) {
                $str .= static::renderIcon($menu['icon']);
            }
            $str .= '<span>' . __($menu['title']) . '</span>
                        </a>
                    </li>';
        }
        return $str;
    }

    public static function renderAsideMenu($menu)
    {
        $user = auth()->user();
        if (empty($menu['url'])) {
            $menu['url'] = 'javascript:;';
        }

        if (!empty($menu['section'])) {
            $str = '<li class="menu-section">
                        <h4 class="menu-text">' . __($menu['section']) . '</h4>
                        <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
                    </li>';
            if ($menu['name'] == 'console_admin') {
                if (!$user->hasPermissionTo(['setting.view', 'master.view',])) {
                    $str = '';
                }
            }
        } elseif (!empty($menu['submenu'])) {
            $str = '<li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover" data-title="' . __($menu['title']) . '">
                        <a href="' . $menu['url'] . '" class="menu-link menu-toggle">';
            if (!empty($menu['icon'])) {
                $str .= static::renderIcon($menu['icon']);
            } elseif (!empty($menu['bullet'])) {
                $str .= '<i class="menu-bullet menu-bullet-' . $menu['bullet'] . '">
                                            <span></span>
                                        </i>';
            }
            $str .= '<span class="menu-text">' . __($menu['title']) . '</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <i class="menu-arrow"></i>
                            <ul class="menu-subnav">
                                <li class="menu-item menu-item-parent" aria-haspopup="true" data-title="' . __($menu['title']) . '">
                                    <span class="menu-link">
                                        <span class="menu-text">' . __($menu['title']) . '</span>
                                    </span>
                                </li>';
            foreach ($menu['submenu'] as $item) {
                if (isset($item['perms']) && !$user->hasPermissionTo($item['perms'] . '.view')) {
                    continue;
                }
                if ($item['name'] === 'menu_company') {
                    foreach (static::asideMenuCompany() as $val) {
                        $str .= static::renderAsideMenu($val);
                    }
                } else {
                    $str .= static::renderAsideMenu($item);
                }
            }
            $str .= '</ul>
                        </div>
                    </li>';
        } else {
            $menuActive = (request()->path() == $menu['url']) ? 'menu-item-active' : '';
            $str = '<li class="menu-item ' . $menuActive . '" aria-haspopup="true" data-title="' . __($menu['title']) . '">
                        <a href="' . $menu['url'] . '" class="menu-link base-content--replace" data-name="' . $menu['name'] . '">';
            if (!empty($menu['icon'])) {
                $str .= static::renderIcon($menu['icon']);
            } elseif (!empty($menu['bullet'])) {
                $str .= '<i class="menu-bullet menu-bullet-' . $menu['bullet'] . '">
                                        <span></span>
                                    </i>';
            }
            $str .= '<span class="menu-text">' . __($menu['title']) . '</span>
                        </a>
                    </li>';
        }
        return $str;
    }

    public static function renderIcon($icon, $className = 'menu-icon')
    {

        if (static::isSVG($icon)) {
            return static::getSvg($icon, 'menu-icon');
        } else {
            return '<i class="' . $icon . ' ' . $className . '"></i>';
        }
    }

    public static function isSVG($path)
    {
        if (is_string($path)) {
            return substr(strrchr($path, '.'), 1) === 'svg';
        }

        return false;
    }

    public static function getSvg($filepath, $class = '')
    {
        if (!is_string($filepath) || !file_exists($filepath)) {
            return '';
        }

        $svg_content = file_get_contents($filepath);

        $dom = new \DOMDocument();
        $dom->loadXML($svg_content);

        // remove unwanted comments
        $xpath = new \DOMXPath($dom);
        foreach ($xpath->query('//comment()') as $comment) {
            $comment->parentNode->removeChild($comment);
        }

        // remove unwanted tags
        $title = $dom->getElementsByTagName('title');
        if ($title['length']) {
            $dom->documentElement->removeChild($title[0]);
        }
        $desc = $dom->getElementsByTagName('desc');
        if ($desc['length']) {
            $dom->documentElement->removeChild($desc[0]);
        }
        $defs = $dom->getElementsByTagName('defs');
        if ($defs['length']) {
            $dom->documentElement->removeChild($defs[0]);
        }

        // remove unwanted id attribute in g tag
        $g = $dom->getElementsByTagName('g');
        foreach ($g as $el) {
            $el->removeAttribute('id');
        }
        $mask = $dom->getElementsByTagName('mask');
        foreach ($mask as $el) {
            $el->removeAttribute('id');
        }
        $rect = $dom->getElementsByTagName('rect');
        foreach ($rect as $el) {
            $el->removeAttribute('id');
        }
        $path = $dom->getElementsByTagName('path');
        foreach ($path as $el) {
            $el->removeAttribute('id');
        }
        $circle = $dom->getElementsByTagName('circle');
        foreach ($circle as $el) {
            $el->removeAttribute('id');
        }
        $use = $dom->getElementsByTagName('use');
        foreach ($use as $el) {
            $el->removeAttribute('id');
        }
        $polygon = $dom->getElementsByTagName('polygon');
        foreach ($polygon as $el) {
            $el->removeAttribute('id');
        }
        $ellipse = $dom->getElementsByTagName('ellipse');
        foreach ($ellipse as $el) {
            $el->removeAttribute('id');
        }

        $string = $dom->saveXML($dom->documentElement);

        // remove empty lines
        $string = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $string);

        $cls = array('svg-icon');
        if (!empty($class)) {
            $cls = array_merge($cls, explode(' ', $class));
        }

        return '<span class="' . implode(' ', $cls) . '"><!--begin::Svg Icon | path:' . $filepath . '-->' . $string . '<!--end::Svg Icon--></span>';
    }

    public static function getQrcode($data)
    {
        $code = \QrCode::format('png')->merge(config('base.logo.barcode'), 0.2, true)
            ->size(180)
            ->errorCorrection('H')
            ->margin(0)
            ->generate($data, 0, 10);
        $result = '<div class="visible-print text-center m-0">
                        <img style="height:100px;" src="data:image/png;base64,' . base64_encode($code) . '">
                    </div>';
        return $result;
    }

    public static function makeLabel($label, $color = 'primary', $params = [])
    {
        $default = [
            'class' => '',
            'attrs' => '',
            'style' => '',
        ];
        $params = array_merge($default, $params);
        return '<span data-short="' . $label . '" class="label label-' . $color . ' label-inline text-nowrap ' . $params['class'] . '" ' . $params['attrs'] . ' style="' . $params['style'] . '">' . $label . '</span>';
    }

    public static function getStatus($key)
    {
        $data = [
            'new' => [
                'color' => 'danger',
                'label' => 'New',
            ],
            'draft' => [
                'color' => 'warning',
                'label' => 'Draft',
            ],
            'waiting.approval' => [
                'color' => 'primary',
                'label' => 'Waiting Approval',
            ],
            'waiting.review' => [
                'color' => 'primary',
                'label' => 'Waiting Review',
            ],
            'rejected' => [
                'color' => 'danger',
                'label' => 'Rejected',
            ],
            'approved' => [
                'color' => 'success',
                'label' => 'Approved',
            ],
            'completed' => [
                'color' => 'success',
                'label' => 'Completed',
            ],
            'upgraded' => [
                'color' => 'info',
                'label' => 'Upgraded',
            ],
            'active' => [
                'color' => 'success',
                'label' => 'Active',
            ],
            'nonactive' => [
                'color' => 'danger',
                'label' => 'Non Active',
            ],
            'valid' => [
                'color' => 'success',
                'label' => 'Valid',
            ],
            'invalid' => [
                'color' => 'danger',
                'label' => 'Invalid',
            ],
            'open' => [
                'color' => 'success',
                'label' => 'Open',
            ],
            'opened' => [
                'color' => 'success',
                'label' => 'Opened',
            ],
            'closed' => [
                'color' => 'info',
                'label' => 'Closed',
            ],
            'done' => [
                'color' => 'success',
                'label' => 'Done',
            ],
        ];

        $status = $data[$key] ?? ['color' => 'primary', 'label' => ucfirst(str_replace('.', ' ', $key))];

        return static::makeLabel($status['label'], $status['color']);
    }
}
