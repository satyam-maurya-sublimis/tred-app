$("document").ready(function() {

    jQuery("#crm_lead_mstCountry").change(function () {
        var data = {};
        data['q'] = jQuery(this).val();
        jQuery.ajax({
            url: "/location/state_list",
            data: data,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                var result = $("#crm_lead_mstState");
                result.html('');
                // add options
                result.append('<option value="" >Select State..</option>');
                $.each(data, function (id, name) {
                    result.append('<option value="' + name.id + '">' + name.name + '</option>');
                });
            }
        });
    });

    jQuery("#crm_lead_mstState").change(function () {
        var data = {};
        data['q'] = jQuery(this).val();
        jQuery.ajax({
            url: "/location/city_list",
            data: data,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                var result = $("#crm_lead_mstCity");
                result.html('');
                // add options
                result.append('<option value="" >Select City..</option>');
                $.each(data, function (id, name) {
                    result.append('<option value="' + name.id + '">' + name.name + '</option>');
                });
            }
        });
    });



});
