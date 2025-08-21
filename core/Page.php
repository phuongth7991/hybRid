<?php

namespace Core;

use Core\Exceptions\CoreException;

class Page
{
    protected string $pageTitle        = '';
    protected array  $breadcrumb       = [];
    protected array  $pageAction       = [];
    protected bool   $displayHeader    = true;
    protected bool   $isHideBreadcrumb = false;

    protected array $styles
        = [
            'plugins.bundle'    => 'assets/plugins/global/plugins.bundle.css',
            'datatables.bundle' => 'assets/plugins/custom/datatables/datatables.bundle.css',
            'style.bundle'      => 'assets/css/style.bundle.css',
            'custom'            => 'assets/css/admin.css',
        ];

    protected array $favicons
        = [
            'favicon-logo' => 'assets/media/logos/default-small.png',
        ];

    protected string $styleToRender    = '';
    protected string $faviconsToRender = '';

    protected array $scripts
        = [
            'plugins.bundle'        => 'assets/plugins/global/plugins.bundle.js',
            'datatables.bundle'     => 'assets/plugins/custom/datatables/datatables.bundle.js',
            'scripts.bundle'        => 'assets/js/scripts.bundle.js',
            'maskMoney'             => 'https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js',
            'core.datatable-helper' => 'assets/js/core.helper.js',
            'formrepeater.bundle'   => 'assets/plugins/custom/formrepeater/formrepeater.bundle.js',
            'admin.custom'          => 'assets/js/admins/custom.js',
        ];

    protected string $scriptToRender = '';


    /**
     * @throws CoreException
     */
    public function addStyle(string|array $style, string $id = ''): static
    {
        if (is_array($style)) {
            $this->styles = array_merge($style, $this->styles);
        } else {
            if (empty($id)) {
                throw new CoreException('Style id is required on add script');
            }
            $this->styles[$id] = $style;
        }

        return $this;
    }


    /**
     * @throws CoreException
     */
    public function addScript(string|array $script, string $id = ''): static
    {
        if (is_array($script)) {
            $this->scripts = array_merge($this->scripts, $script);
        } else {
            if (empty($id)) {
                throw new CoreException('Script id is required on add script');
            }
            $this->scripts[$id] = $script;
        }

        return $this;
    }


    private function renderStyle(): void
    {
        foreach ($this->styles as $key => $style) {
            $this->styleToRender .= '<link id="' . $key . '-css" rel="stylesheet" href="' . asset($style) . '" />';
        }
    }

    private function renderFavicon(): void
    {
        foreach ($this->favicons as $key => $favicon) {
            $this->faviconsToRender .= '<link id="' . $key . '-favicon" rel="icon" href="' . asset($favicon) . '" />';
        }
    }

    private function renderScript(): void
    {
        foreach ($this->scripts as $key => $script) {
            $this->scriptToRender .= '<script id="' . $key . '-js" type="text/javascript" src="' . asset($script) . '"></script>';
        }
    }


    public function setPageTitle($title): static
    {
        $this->pageTitle = $title;

        return $this;
    }


    public function getPageTitle(): string
    {
        return $this->pageTitle;
    }


    public function head(): void
    {
        $this->renderStyle();
        $this->renderFavicon();
        echo $this->styleToRender;
        echo $this->faviconsToRender;
    }

    public function footer(): void
    {
        $this->renderScript();
        echo $this->scriptToRender;
    }

    public function homeUrl()
    {
        return env('APP_URL');
    }

    public function getLogo($type = 'dark'): string
    {
        return asset('assets/media/logos/default-' . $type . '.png');
    }

    public function addBreadcrumbItem($item): static
    {
        $this->breadcrumb[] = $item;

        return $this;
    }

    public function getBreadcrumb(): array
    {
        return $this->breadcrumb;
    }

    /**
     * Add page action
     *
     * @param $action array ['title' => 'Button' , 'action' => 'uri', 'btn_class' => '']
     *
     */
    public function addPageAction(array $action): static
    {
        $_action            = new \stdClass();
        $_action->title     = $action['title'] ?? __('Action');
        $_action->uri       = $action['uri'] ?? '/admin';
        $_action->btnClass  = $action['btn_class'] ?? 'btn-primary';
        $_action->icon      = $action['icon'] ?? '';
        $this->pageAction[] = $_action;

        return $this;
    }

    public function hideHeader(): void
    {
        $this->displayHeader = false;
    }


    public function getPageAction(): array
    {
        return $this->pageAction;
    }

    public function isHideBreadcrumb(): bool
    {
        return $this->isHideBreadcrumb;
    }

    public function hideBreadcrumb(): void
    {
        $this->isHideBreadcrumb = true;
    }
}
