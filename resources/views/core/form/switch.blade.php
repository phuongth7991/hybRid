<?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
<div <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>
    <?php endif; ?>
    <?php if ($showField): ?>
    <div class="form-check form-switch form-check-custom form-check-success form-check-solid">
            <input name="{{$name}}" class="form-check-input  h-40px w-60px" type="checkbox" value="1"
               {{(isset($options['value']) && $options['value']) ? 'checked' : ''}} id="{{$name}}"/>
            <?php if ($showLabel && $options['label'] !== false && $options['label_show']): ?>
            <?php if (array_key_exists('label_template', $options) && $options['label_template']): ?>
            <label class="form-check-label" for="{{$name}}">
                {{$options['label']}}
            </label>
        <?php else: ?>
            <?php include labelBlockPath(); ?>
        <?php endif; ?>
    </div>
    <?php endif; ?>

        <?php include helpBlockPath(); ?>
    <?php endif; ?>

    <?php include errorBlockPath(); ?>

    <?php if ($showLabel && $showField): ?>
        <?php if ($options['wrapper'] !== false): ?>
</div>
<?php endif; ?>
<?php endif; ?>