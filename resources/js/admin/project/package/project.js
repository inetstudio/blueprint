window.Admin = window.Admin || {
    options: {
        tinymce: {}
    }
};

$.extend(window.Admin.options.tinymce, {
    plugins: [
        'images autolink lists link charmap print preview',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table contextmenu paste images project'
    ],
    toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | link | images | media | code | fullscreen',
    media_dimensions: false,
    media_filter_html: false
});
