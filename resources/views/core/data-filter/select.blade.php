@if(isset($attribute['label']))
    <div class="col-auto">
        <label for="{{$attribute['id'] ?? $attribute['name']}}" class="col-form-label">{{$attribute['label']}}</label>
    </div>
@endif
<div class="col-auto">
    {!! Form::select($attribute['name'],$attribute['choices'], request()->input($attribute['name']), ['class' => 'form-select', 'data-control' => 'select2']) !!}
</div>
