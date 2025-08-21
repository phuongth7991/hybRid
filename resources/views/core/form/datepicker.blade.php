<?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
<div <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>
    <?php endif; ?>
    <?php /** label rendering section */ ?>
    <?php if ($showLabel && $options['label'] !== false && $options['label_show']): ?>
        <?php if(array_key_exists('label_template', $options) && $options['label_template']): ?>
            <?= view($options['label_template'], get_defined_vars())->render(); ?>
        <?php else: ?>
            <?php include labelBlockPath(); ?>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($showField): ?>
        <?php
          $value = Carbon\Carbon::parse($options['value'])->format('d/m/Y');
        ?>
        <?= Form::input('text', $name, $value, $options['attr']) ?>
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
        $("#{{$name}}").flatpickr({
            enableTime: {{(isset($options['enableTime']) && $options['enableTime'])  ? 'true' : 'false'}},
            dateFormat: {!! (isset($options['enableTime']) && $options['enableTime'])  ? '"d/m/Y H:i"' : '"d/m/Y"' !!},
        });
    </script>
@endpush
