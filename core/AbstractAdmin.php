<?php

namespace Core;

use Core\Exceptions\CoreException;
use Core\Interfaces\Admin;
use Core\Interfaces\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

abstract class AbstractAdmin implements Admin
{
    public string      $route            = '';
    public string      $routePath        = '';
    public string      $resourceParam    = '';
    public bool        $showPassword     = false;
    public bool        $formBuilder      = true;
    public bool        $dataTable        = true;
    public bool        $hideListAction   = false;
    public bool        $hideButtonAction = false;
    protected bool     $autoStoreFile    = false;
    public bool        $enableExport     = false;
    protected ?Request $request          = null;

    protected bool $isActEdit = true;
    protected bool $isActDel  = true;

    protected bool $isSeo = false;

    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @throws CoreException
     */
    public function getForm(): string
    {
        throw new CoreException('The admin ' . $this->getAdminName() . ' need build form class. Please create or set $formBuilder = false');
    }

    abstract public function getAdminName(): string;

    public function getRepository(): Repository
    {
        return $this->repository;
    }

    public function getRouteName(): string
    {
        return $this->route;
    }

    public function getTransformer(): ?string
    {
        return null;
    }

    public function configFilter(): ?array
    {
        return null;
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    public function storeFile(string $fieldName): string|bool
    {
        $request = $this->getRequest();
        if ($request->file($fieldName . '_' . 'prefix')) {
            $path = $this->request->file($fieldName . '_' . 'prefix')
                                  ->store('public/' . $this->createStoreFilePath($this->route));
            $this->request->merge([$fieldName => str_replace('public/', '', $path)]);

            return $path;
        }

        return '';
    }

    private function createStoreFilePath($path): string
    {
        return str_replace(['.', '-'], ['_', '_'], $path);
    }

    /**
     * @return bool
     */
    private function isAutoStoreFile(): bool
    {
        return $this->autoStoreFile;
    }

    public function automationStoreFile(int $id = 0): void
    {
        $item = null;
        if ($id > 0) {
            $item = $this->getRepository()->find($id);
        }
        if ($this->isAutoStoreFile()) {
            $request = $this->getRequest();
            $files   = $request->file();
            foreach ($files as $fieldName => $file) {
                $fieldName = str_replace('_prefix', '', $fieldName);
                if (!is_array($file)) {
                    if ($this->request->get($fieldName . "_remove")) {
                        if ($this->removeFile($item->$fieldName)) {
                            $this->request->merge([$fieldName => '']);
                        }
                    }
                    $path = $file->store('public/' . $this->createStoreFilePath($this->route));
                    $this->request->merge([$fieldName => str_replace('public/', '', $path)]);

                    if (!empty($path) && !empty($item)) {
                        $this->removeFile($item->$fieldName);
                    }
                } else {
                    $files = [];
                    foreach ($file as $newFile) {
                        $_path   = $newFile->store('public/' . $this->createStoreFilePath($this->route));
                        $files[] = str_replace('public/', '', $_path);
                    }
                    $listRemove = [];
                    if ($this->request->get($fieldName . "_remove")) {
                        $listRemove = json_decode($this->request->get($fieldName . "_remove"), true);
                        if (!empty($listRemove)) {
                            foreach ($listRemove as $filePath) {
                                $this->removeFile($filePath);
                            }
                        }
                    }
                    if (!empty($item) && !empty($item->$fieldName)) {
                        $diff  = array_diff($item->$fieldName, $listRemove);
                        $files = array_merge($diff, $files);
                    }
                    $this->request->merge([$fieldName => $files]);
                }
            }
        }
    }


    public function removeFile(?string $filePath): bool
    {
        if (!empty($filePath)) {
            return Storage::delete('public/' . $filePath);
        }

        return false;
    }

    /**
     * @throws CoreException
     */
    public function getDataTable(): string
    {
        throw new CoreException('The admin ' . $this->getAdminName() . ' need datatable class. Please create or set $dataTable = false');
    }


    public function datatables(Request $request)
    {
        return \DataTables::of($this->getRepository()->getModel()->query())->toJson();
    }

    public function create($data): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->getRepository()->create($data);
    }

    public function update($id, $data): \Illuminate\Database\Eloquent\Model
    {
        $item = $this->getRepository()->find($id);
        if (!$item)
            throw new ModelNotFoundException(__('Không tìm thấy bản ghi được yêu cầu'));
        $item->fill($data);
        $item->save();

        return $item;
    }

    public function delete($id)
    {
        return $this->getRepository()->delete($id);
    }

    public function beforeCreate(): void
    {
        $this->prepareSeoRequest();
    }

    /**
     * Command hook run when resource is created
     * @return void
     */
    public function afterCreate(Model $entity): void
    {
        $this->saveSeo($entity);
    }

    public function beforeUpdate(): void
    {
        $this->prepareSeoRequest();
    }

    public function afterUpdate(Model $entity): void
    {
        $this->saveSeo($entity);
    }

    /**
     * Call when before update or create action
     *
     * @param Model $entity
     *
     * @return void
     */
    public function beforeCommit(): void
    {

    }

    /**
     * Call when update and create action
     *
     * @param Model $entity
     *
     * @return void
     */
    public function afterCommit(Model $entity): void
    {

    }

    /**
     * Call when before delete item
     * @return void
     */
    public function beforeDelete(Model $entity): void
    {

    }

    public function afterDelete(array $entity): void
    {

    }

    public function beforeEdit(Model $entity): Model
    {
        return $entity;
    }

    public function afterEdit(Model $entity): Model
    {
        return $entity;
    }

    public function getRoutePath(): string
    {
        if (!empty($this->routePath))
            return ltrim($this->routePath, '/');

        return $this->route;
    }

    public function getResourceParam(): string
    {
        if (!empty($this->resourceParam))
            return $this->resourceParam;

        return $this->route;
    }

    /**
     * This is return array action on listing page
     * @return array|string
     */
    public function getPageActions(): array
    {
        return [];
    }

    /**
     * @param Request $request
     *
     * @return void
     */
    public function export(Request $request)
    {
        throw new \Exception('Export method Not implement');
    }

    public function getIsActEdit(): bool
    {
        return $this->isActEdit;
    }

    public function getIsActDel(): bool
    {
        return $this->isActDel;
    }

    public function prepareSeoRequest(): void
    {
        if (!$this->isSeo) {
            return;
        }
        $seo                     = [];
        $seo['focus_keyword']    = $this->request->get('seo_focus_keyword', '');
        $seo['meta_title']       = $this->request->get('seo_meta_title', '');
        $seo['meta_description'] = $this->request->get('seo_meta_description', '');
//        $seo['meta_keywords']       = $this->request->get('seo_meta_keywords', '');
        $seo['og_image']            = $this->request->get('seo_og_image', '');
        $seo['og_title']            = $this->request->get('seo_og_title', '');
        $seo['og_description']      = $this->request->get('seo_og_description', '');
        $seo['canonical_url']       = $this->request->get('seo_canonical_url', '');
        $seo['twitter_title']       = $this->request->get('seo_twitter_title', '');
        $seo['twitter_description'] = $this->request->get('seo_twitter_description', '');
        $seo['twitter_image']       = $this->request->get('seo_twitter_image', '');
//        $seo['robots']              = $this->request->get('seo_robots', '');
        $seo['json_ld']   = $this->request->get('seo_json_ld', '');
        $seo['is_index']  = $this->request->get('seo_is_index', false);
        $seo['is_follow'] = $this->request->get('seo_is_follow', false);

        $seo['robots'] = ($seo['is_index'] ? 'index' : 'noindex') . ', ' . ($seo['is_follow'] ? 'follow' : 'nofollow');


        $this->request->merge(['seo' => $seo]);
        $seoAnalysis              = $this->_analyzeSeo();
        $seo['seo_score']         = $seoAnalysis['score'];
        $seo['readability_score'] = 0; // Placeholder for readability score, can be implemented later
        $this->request->merge(['seo' => $seo]);
    }

    public function saveSeo(Model $model): void
    {
        $seo = $this->request->get('seo');
        if (!$this->isSeo || empty($seo)) {
            return;
        }

        if ($model->seo) {
            $model->seo->update($seo);
        } else {
            $model->seo()->create($seo);
        }
    }

    private function _analyzeSeo(): array
    {
        $score       = 0;
        $issues      = [];
        $seo         = $this->request->get('seo', []);
        $content     = $this->request->get('content_vi', '');
        $description = $this->request->get('description_vi', '');

        $focusKeyword     = strtolower($seo['focus_keyword'] ?? '');
        $metaTitle        = strtolower($seo['meta_title'] ?? '');
        $metaDescription  = strtolower($seo['meta_description'] ?? '');

        // Title chứa keyword?
        if (str_contains(strtolower($metaTitle), strtolower($focusKeyword))) {
            $score += 20;
        } else {
            $issues[] = "Tiêu đề không chứa từ khóa chính.";
        }

        // Description chứa keyword?
        if (str_contains(strtolower($metaDescription), strtolower($focusKeyword))) {
            $score += 15;
        } else {
            $issues[] = "Meta description không chứa từ khóa.";
        }

        // Mật độ từ khóa trong nội dung?
        if ($content && $description && $focusKeyword){
            $content = strip_tags($content . ' ' . $description);
            $kwCount = substr_count(strtolower($content), strtolower($focusKeyword));
            $density = $kwCount / max(1, str_word_count($content));

            if ($density > 0.01 && $density < 0.03) {
                $score += 25;
            } else {
                $issues[] = "Mật độ từ khóa trong nội dung không tối ưu (khuyến nghị 1%–3%).";
            }
        } else {
            $issues[] = "Nội dung hoặc mô tả không được cung cấp.";
        }

        // Độ dài title, description?
        $score += (strlen($metaTitle) <= 60) ? 10 : 0;
        $score += (strlen($metaDescription) <= 160) ? 10 : 0;

        return [
            'score'  => $score,
            'issues' => $issues
        ];
    }
}
