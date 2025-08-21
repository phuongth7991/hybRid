<?php

namespace Core\Controllers;

use Core\Interfaces\Admin;
use Core\Interfaces\CRUDController;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Kris\LaravelFormBuilder\FormBuilder;
use Yajra\DataTables\Html\Builder;

abstract class AbstractController extends BaseController implements CRUDController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected Admin $admin;

    protected ?Request $request = null;

    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
    }

    private function getView(string $name, $data)
    {
        \File::exists(resource_path('views/admins/' . $this->admin->getRoutePath() . '/' . $name . '.blade.php')) ?
            $view = 'admins.' . $this->admin->getRoutePath() . '.' . $name :
            $view = 'core.crud.' . $name;

        return view($view, $data);
    }

    public function index(Builder $builder, Request $request)
    {
        $this->admin->setRequest($request);

        if ($request->input('export')) {
            $fileName = \Str::slug($this->admin->getAdminName()) . '-' . now()->format('Y-m-d-H-i-s');

            return \Excel::download($this->admin->export($request), sprintf('%s.xlsx', $fileName));
        }

        if (request()->ajax()) {
            return $this->admin->datatables($request);
        }

        $title = __('Danh sách') . ' ' . $this->admin->getAdminName();
        isset($this->admin->hideListAction) && $this->admin->hideListAction ?
            \Page::setPageTitle($title)->addBreadcrumbItem(['title' => $title,])
            :
            \Page::setPageTitle($title)->addPageAction(
                [
                    'title' => __('Thêm mới'),
                    'uri'   => route($this->admin->getRouteName() . '.create')
                ]
            )->addBreadcrumbItem(['title' => $title]);
        $tableClass    = $this->admin->getDataTable();
        $table         = new $tableClass($builder, $this->admin);
        $dataTable     = $table->build($request);
        $filter        = $this->generateFormFilter($this->admin->configFilter());
        $enabledExport = $this->admin->enableExport;
        if (!empty($this->admin->getPageActions())) {
            \Page::addPageAction($this->admin->getPageActions());
        }

        return $this->getView('index', compact('dataTable', 'filter', 'enabledExport'));
    }

    protected function generateFormFilter($config): string
    {
        $html = '';
        if (!empty($config)) {
            foreach ($config as $key => $attribute) {
                $attribute['name'] = $key;
                $html              .= view('core.data-filter.' . $attribute['type'], compact('attribute'));
            }
        }

        return $html;
    }

    private function addFileTmp(Request $request): void
    {
        foreach ($request->file() as $key => $file) {
            if (!is_array($file)) {
                $request->merge(
                    [
                        str_replace('_prefix', '', $key) => $file->getClientOriginalName()
                    ]
                );
            } else {
                $data = [];
                foreach ($file as $newFile) {
                    $data[] = $newFile->getClientOriginalName();
                }
                $request->merge(
                    [
                        str_replace('_prefix', '', $key) => $data
                    ]
                );
            }
        }
    }

    public function create(FormBuilder $builder)
    {
        \Page::setPageTitle(__('Thêm mới') . ' ' . $this->admin->getAdminName())
             ->addPageAction(
                 [
                     'title'     => __('Quay lại'),
                     'uri'       => route($this->admin->getRouteName() . '.index'),
                     'icon'      => 'fa-arrow-circle-left',
                     'btn_class' => 'btn-danger'
                 ]
             )->addBreadcrumbItem(
                [
                    'title' => __('Thêm mới'),
                ]
            );
        \Menu::setActivePath('/admin/' . $this->admin->getRoutePath());
        $form = $builder->create($this->admin->getForm(), [
            'method'  => 'POST',
            'enctype' => 'multipart/form-data',
            'url'     => route($this->admin->getRouteName() . '.store')
        ]);

        return $this->getView('create', compact('form'));
    }

    public function store(FormBuilder $builder, Request $request)
    {
        $this->admin->setRequest($request);
        $this->addFileTmp($request);
        $this->admin->automationStoreFile();
        $form = $builder->create($this->admin->getForm());
        try {
            DB::transaction(function () use ($form, $request) {
                $this->admin->beforeCreate();
                $this->admin->beforeCommit();
                $form->redirectIfNotValid();
                $data = $request->except('_method', '_token');
                $item = $this->admin->create($data);
                $this->admin->afterCreate($item);
                $this->admin->afterCommit($item);
            });

            return redirect(route($this->admin->getRouteName() . '.index'))
                ->with(['type' => 'success', 'message' => __('Thêm mới bản ghi thành công')]);
        } catch (Exception $exception) {
            return redirect(route($this->admin->getRouteName() . '.create'))
                ->withInput()
                ->with(['type' => 'danger', 'message' => __('Thêm mới bản ghi thất bại') . $exception->getMessage()]);
        } catch (\Throwable $exception) {
            return redirect(route($this->admin->getRouteName() . '.create'))
                ->withInput()
                ->with(['type' => 'danger', 'message' => __('Thêm mới bản ghi thất bại') . $exception->getMessage()]);
        }
    }

    public function show($id, Request $request)
    {
        $this->admin->setRequest($request);
        $repository = $this->admin->getRepository();
        $item       = $repository->find($id);
        if (!$item) {
            abort(404);
        }
        \Page::setPageTitle($this->admin->getAdminName() . '#' . $item->getKey());
        \Menu::setActivePath('/admin/' . $this->admin->getRoutePath());

        return $this->getView('show', compact('item'));
    }

    public function edit($id, Request $request, FormBuilder $builder)
    {
        $item = $this->admin->getRepository()->find($id);
        if (!$item) {
            abort(404);
        }
        \Page::setPageTitle(__('Chỉnh sửa') . ' #' . $item->getKey())
             ->addPageAction(
                 [
                     'title'     => __('Quay lại'),
                     'uri'       => route($this->admin->getRouteName() . '.index'),
                     'icon'      => 'fa-arrow-circle-left',
                     'btn_class' => 'btn-danger'
                 ]
             )
             ->addBreadcrumbItem(
                 [
                     'title' => __('Chỉnh sửa') . ' #' . $item->getKey(),
                 ]
             );
        \Menu::setActivePath('/admin/' . $this->admin->getRoutePath());
        $item = $this->admin->beforeEdit($item);
        $form = $builder->create($this->admin->getForm(), [
            'method'  => 'PUT',
            'enctype' => 'multipart/form-data',
            'url'     => route($this->admin->getRouteName() . '.update',
                               [$this->admin->getResourceParam() => $item->getKey()]),
            'model'   => $item
        ]);

        return $this->getView('edit', compact('item', 'form'));
    }


    public function update(FormBuilder $builder, $id, Request $request)
    {
        $this->admin->setRequest($request);
        $this->addFileTmp($request);
        $this->admin->automationStoreFile($id);
        $form = $builder->create($this->admin->getForm());
        $form->redirectIfNotValid();
        $request->merge(['id' => $id]);
        try {
            DB::transaction(function () use ($id, $request) {
                $this->admin->beforeUpdate();
                $this->admin->beforeCommit();
                $data               = $request->except('_method', '_token');
                $data['updated_by'] = \Auth::guard('admin')->id();
                $data['updated_at'] = now();
                $item               = $this->admin->update($id, $data);
                $this->admin->afterUpdate($item);
                $this->admin->afterCommit($item);
            });

            return redirect()->back()->with(['type' => 'success', 'message' => __('Chỉnh sửa bản ghi thành công')]);
        } catch (Exception $exception) {
            return redirect(route($this->admin->getRouteName() . '.edit', [$this->admin->getResourceParam() => $id]))
                ->withInput($request->all())->with(['type' => 'danger', 'message' => $exception->getMessage()]);
        } catch (\Throwable $exception) {
            return redirect(route($this->admin->getRouteName() . '.edit', [$this->admin->getResourceParam() => $id]))
                ->withInput($request->all())->with(['type' => 'danger', 'message' => $exception->getMessage()]);
        }
    }


    public function destroy(int $id, Request $request)
    {
        $this->admin->setRequest($request);
        $item = $this->admin->getRepository()->find($id);
        $this->admin->beforeDelete($item);
        $itemArray = $item->toArray();
        $delete    = $this->admin->delete($id);
        if ($delete) {
            $this->admin->afterDelete($itemArray);

            return true;
        }
    }

    public function export(Request $request)
    {
        $this->admin->setRequest($request);

        return $this->admin->export($request);
    }
}
