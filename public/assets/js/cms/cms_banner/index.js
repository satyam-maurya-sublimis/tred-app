$(document).ready(function() {
    $('label[for=cms_banner_bannerImage]').remove();


$("#cms_banner_bannerImage").fileinput({
    theme: "fa",
    overwriteInitial: true,
    maxFileSize: 800,
    showClose: false,
    showCaption: false,
    showBrowse: false,
    browseOnZoneClick: true,
    removeLabel: '',
    defaultPreviewContent: '<h6 class="text-muted">Click to upload banner</h6>',
    layoutTemplates: {main2: '{preview} {remove} {browse}'},
    allowedFileExtensions: ["jpg", "png"]
});

});