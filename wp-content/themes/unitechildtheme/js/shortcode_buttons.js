(function() {
    tinymce.PluginManager.add('true_mce_button', function( editor, url ) {
        editor.addButton('true_mce_button', {
            text: '[lastmovies count=5]',
            title: translation_obj.ins_shortcode + ' [lastmovies count=5]',
            icon: false,
            onclick: function() {
                editor.insertContent('[lastmovies count=5]');
            }
        });
    });

    tinymce.PluginManager.add('actors_scode_button', function( editor, url ) {
        editor.addButton('actors_scode_button', {
            text: '[actors per_page=30]',
            title: translation_obj.ins_shortcode + ' [actors per_page=30]',
            icon: false,
            onclick: function() {
                editor.insertContent('[actors per_page=30]');
            }
        });
    });
})();