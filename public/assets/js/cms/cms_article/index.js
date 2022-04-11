$(document).ready(function() {
    $('label[for=cms_article_articleIntroImage]').remove();


$("#cms_article_articleIntroImage").fileinput({
    theme: "fa",
    overwriteInitial: true,
    maxFileSize: 800,
    showClose: false,
    showCaption: false,
    showBrowse: false,
    browseOnZoneClick: true,
    removeLabel: '',
    defaultPreviewContent: '<h6 class="text-muted">Click to upload intro image</h6>',
    layoutTemplates: {main2: '{preview} {remove} {browse}'},
    allowedFileExtensions: ["jpg", "png"]
});

    $('label[for=cms_article_articleImage]').remove();


    $("#cms_article_articleImage").fileinput({
        theme: "fa",
        overwriteInitial: true,
        maxFileSize: 800,
        showClose: false,
        showCaption: false,
        showBrowse: false,
        browseOnZoneClick: true,
        removeLabel: '',
        defaultPreviewContent: '<h6 class="text-muted">Click to upload content primary image</h6>',
        layoutTemplates: {main2: '{preview} {remove} {browse}'},
        allowedFileExtensions: ["jpg", "png"]
    });

});