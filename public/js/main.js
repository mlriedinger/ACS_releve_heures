function include(file) {
    var script = document.createElement('script');
    script.src = file;
    script.type = "text/javascript";

    $('#mainScript').after(script);
}

include('public/js/buttonManagement.js');
include('public/js/updateData.js');
include('public/js/ajaxRequests.js');