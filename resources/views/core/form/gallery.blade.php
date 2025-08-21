<?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
<div <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>
    <?php endif; ?>

    <div>
        <?php if ($showLabel && $options['label'] !== false && $options['label_show']): ?>
            <?php if (array_key_exists('label_template', $options) && $options['label_template']): ?>
                <?= view($options['label_template'], get_defined_vars())->render(); ?>
            <?php else: ?>
                <?php include labelBlockPath(); ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <div class="input-gallery-wrap" id="gallery-input-{{$name}}">
        <div class="upload__box">
            <div class="upload__img-wrap">
                @if(!empty($options['value']))
                    @foreach($options['value'] as $index => $img)
                        <div class='upload__img-box'>
                            <div style='background-image: url({{SystemHelper::asset($img)}})' data-number='{{$index}}' class='img-bg'>
                                <div data-path="{{$img}}" class='upload__img-close'></div>
                            </div>
                            <input type="hidden" name="{{$name}}[]" value="{{$img}}" class="gallery-item-value" />
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="upload__btn-box">
                <label class="upload__btn">
                    Chọn file ảnh
                    <input type="file" name="{{$name}}_prefix[]" multiple class="upload__inputfile">
                    <input type="hidden" name="{{$name}}_remove" class="gallery-remove-list">
                </label>
            </div>
        </div>
    </div>
    <?php if ($showField): ?>
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
