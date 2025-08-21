<?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
<div <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>
    <?php endif; ?>

    <?php /** label rendering section */ ?>
    <?php if ($showLabel && $options['label'] !== false && $options['label_show']): ?>
        <?php if (array_key_exists('label_template', $options) && $options['label_template']): ?>
            <?= view($options['label_template'], get_defined_vars())->render(); ?>
        <?php else: ?>
            <?php include labelBlockPath(); ?>
        <?php endif; ?>
    <?php endif; ?>
    <?php if ($showField): ?>
        <?= Form::textarea($name, $options['value'], array_merge($options['attr'], ['id' => 'editor-' . md5($name)])) ?>

        <?php include helpBlockPath(); ?>
    <?php endif; ?>

    <?php include errorBlockPath(); ?>

    <?php if ($showLabel && $showField): ?>
        <?php if ($options['wrapper'] !== false): ?>
</div>
<?php endif; ?>
<?php endif; ?>

@push('scripts')
    <script src="{{asset('assets/js/ckeditor/ckeditor.js')}}"></script>
    <script>
        CKEDITOR.replace('editor-{{md5($name)}}',
            {
                filebrowserBrowseUrl: '/filemanager?type=Files',
                filebrowserUploadUrl: '/filemanager/upload?type=Files&_token={{ csrf_token() }}',
                filebrowserImageBrowseUrl: '/filemanager?type=Images',
                filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token={{ csrf_token() }}'
            })
        {{--ClassicEditor.create(document.querySelector('#editor-{{md5($name)}}'), {--}}
        {{--    toolbar: {--}}
        {{--        items: [--}}
        {{--            'undo', 'redo',--}}
        {{--            '|', 'heading',--}}
        {{--            '|', 'fontfamily', 'fontsize', 'fontColor', 'fontBackgroundColor',--}}
        {{--            '|', 'bold', 'italic', 'strikethrough', 'subscript', 'superscript', 'code',--}}
        {{--            '|', 'link', 'uploadImage', 'blockQuote', 'codeBlock',--}}
        {{--            '|', 'bulletedList', 'numberedList', 'todoList', 'outdent', 'indent'--}}
        {{--        ],--}}
        {{--        shouldNotGroupWhenFull: false--}}
        {{--    }--}}
        {{--})--}}
    </script>
@endpush
