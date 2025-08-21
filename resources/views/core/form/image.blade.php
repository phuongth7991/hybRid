@php
    $routeName = Route::getCurrentRoute()->getName();
    $isCreate = strpos($routeName, 'create') !== false;
@endphp
<?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
<div <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>
    <?php endif; ?>

    <div>
        <?php /** label rendering section */ ?>
        <?php if ($showLabel && $options['label'] !== false && $options['label_show']): ?>
            <?php if (array_key_exists('label_template', $options) && $options['label_template']): ?>
                <?= view($options['label_template'], get_defined_vars())->render(); ?>
            <?php else: ?>
                <?php include labelBlockPath(); ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <?php if ($showField): ?>
        <!--begin::Image input-->
    <div class="image-input image-input-outline" data-kt-image-input="true"
         style="background-image: url('{{asset('/assets/media/svg/avatars/blank.svg')}}')">
        <div class="image-input-wrapper w-125px h-125px"
             style="background-image: url({{ ($options['value']) ? SystemHelper::asset($options['value']) : asset('/assets/media/svg/avatars/blank.svg')}})"></div>
        <!--begin::Edit button-->
        <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
               data-kt-image-input-action="change"
               data-bs-toggle="tooltip"
               data-bs-dismiss="click"
               title="Đổi {{$name}}">
            <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>
            <input type="file" name="{{$name}}_prefix" accept=".png, .jpg, .jpeg, .gif"/>
            <input type="hidden" name="{{$name}}_remove"/>
            @if(!$isCreate)
                <input type="hidden" id="current-image-{{$name}}" name="{{$name}}" value="{{$options['value']}}">
            @endif
        </label>
        <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
              data-kt-image-input-action="cancel"
              data-bs-toggle="tooltip"
              data-bs-dismiss="click"
              title="Cancel {{$name}}">
            <i class="ki-outline ki-cross fs-3"></i>
        </span>
        @if(!empty($options['value']))
            <span id="{{$name}}-remove-btn"
                  class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                  data-kt-image-input-action="remove"
                  data-image-remove="{{$name}}"
                  data-bs-toggle="tooltip"
                  data-bs-dismiss="click"
                  title="Remove {{$name}}">
                    <i class="ki-outline ki-cross fs-3"></i>
            </span>
        @endif
    </div>

        <?php include helpBlockPath(); ?>
    <?php endif; ?>
    <?php include errorBlockPath(); ?>
    <?php if ($showLabel && $showField): ?>
        <?php if ($options['wrapper'] !== false): ?>
</div>
<?php endif; ?>
<?php endif; ?>

@push('scripts')
    <script>
        $('#{{$name}}-remove-btn').click(function () {
            $('#current-image-{{$name}}').val('')
        })
    </script>
@endpush
