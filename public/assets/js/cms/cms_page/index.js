var $collectionHolder;
var $pageURL = $(location).attr("href");
jQuery(function () {
    $collectionHolder = jQuery('#pageContent').parent();
    $collectionHolder.data('index', jQuery('#pageContent').find('.row').length);

});
jQuery(document).on('click', '.btn-danger', function (e) {
    jQuery(this).closest(".cmsPageContent").remove();
});
jQuery("#addContent").on('click', function (e) {
    addRowContentForm($collectionHolder);
});

function addRowContentForm($collectionHolder) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');
    var newForm = prototype;
    newForm = newForm.replace(/__name__/g, index);
    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    jQuery("#pageContent").append(newForm)
    jQuery("#cms_page_cmsPageContent_"+index+"_position").val(index+1);
    // Select2 for selectbox
    $('select').select2({
        theme: 'bootstrap4'
    })
    $(function () {
        // Summernote
        $('.textarea').summernote();
    });
}
function slugify(string) {
    return string
        .toString()
        .trim()
        .toLowerCase()
        .replace(/\s+/g, "-")
        .replace(/[^\w\-]+/g, "")
        .replace(/--+/g, "-")
        .replace(/^-+/, "")
        .replace(/-+$/, "");
}
$("document").ready(function() {

    $("#cms_page_pageTitle").keyup(function(){
        var Text = $(this).val();
        $("#cms_page_pageSlugName").val(slugify(Text));
    });
    if ($pageURL.search(/add/i) > 0) {
        jQuery("#addContent").trigger("click");
        //$('.removebutton').hide();
    }
});
