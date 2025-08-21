<?php

namespace Core;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Menu
{
    protected ?User $user;

    protected string $activePath = '';

    public function __construct()
    {
        $this->user = Auth::user();
    }

    /**
     * @param string $activePath
     *
     * @return Menu
     */
    public function setActivePath(string $activePath): static
    {
        $this->activePath = url($activePath);

        return $this;
    }
    private function renderMenu($menus, $level = 0): string
    {
        $lv         = $level;
        $currentUrl = url()->current();
        $menuRender = '';
        foreach ($menus as $menu) {

            $href = isset($menu->href) ? url($menu->href) : '';
            if (isset($menu->children)) {
                $menuRender .= '<div data-level="' . $lv . '" data-kt-menu-trigger="click" class="menu-item menu-accordion">';
            } else {
                $menuRender .= '<div data-level="' . $lv . '" class="menu-item">';
            }
            $target = '';
            if (isset($menu->target)) {
                $target = $menu->target;
            }

            $activeClass = '';
            if ($currentUrl === $href || $this->activePath == $href) {
                $activeClass = 'active';
            }

            if (isset($menu->href)) {
                $menuRender .= '<a data-level=' . $level . ' class="menu-link ' . $activeClass . '" href="' . $href . '" target="' . $target . '">';
            } else {
                $menuRender .= '<span class="menu-link">';
            }

            if (isset($menu->icon)) {
                $menuRender .= '<span class="menu-icon">' . $menu->icon . '</span>';
            }
            if ($lv > 0) {
                $menuRender .= '<span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>';
            }
            $menuRender .= '<span class="menu-title">' . $menu->title . '</span>';
            if (isset($menu->children)) {
                $menuRender .= '<span class="menu-arrow"></span>';
            }
            if (isset($menu->href)) {
                $menuRender .= '</a>';
            } else {
                $menuRender .= '</span>';
            }
            if (isset($menu->children)) {
                $level      = $lv + 1;
                $menuRender .= '<div class="menu-sub menu-sub-accordion" data-level="' . $lv . '">';
                $menuRender .= $this->renderMenu($menu->children, $level);
                $menuRender .= '</div>';
            }
            $menuRender .= '</div>';
        }

        return $menuRender;
    }

    public function render(): string
    {
        $menu     = json_decode(json_encode(config('menu')));
        $toRender = '';
        foreach ($menu->sidebar as $key => $menu) {
            $toRender .= '<div class="menu-item pt-5">
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7">' . strtoupper($key) . '</span>
                    </div>
                </div>';
            $toRender .= $this->renderMenu($menu, 0);
        }

        return $toRender;
    }

}
