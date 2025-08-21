@if(isset($attribute['label']))
    <div class="col-auto">
        <label for="{{$attribute['id'] ?? $attribute['name']}}" class="col-form-label">{{$attribute['label']}}</label>
    </div>
@endif
<div class="col-auto">
    <input type="text" id="{{$attribute['id'] ?? $attribute['name']}}" placeholder="{{$attribute['placeholder'] ?? ''}}" name="{{ $attribute['name']}}"
           value="{{request()->get($attribute['name'])}}"
           class="form-control date-picker"/>
</div>
