<?php

namespace Core\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;
use Yajra\DataTables\Html\Builder;

interface CRUDController
{
    public function index(Builder $builder, Request $request);

    public function create(FormBuilder $builder);

    public function store(FormBuilder $builder, Request $request);

    public function show($id, Request $request);

    public function edit($id, Request $request, FormBuilder $builder);

    public function update(FormBuilder $builder, $id, Request $request);

    public function destroy(int $id, Request $request);

    public function export(Request $request);
}
