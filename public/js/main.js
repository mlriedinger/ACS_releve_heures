function include(file, integrity="", crossorigin="") {
    var script = document.createElement('script');
    script.src = file;
    script.type = "text/javascript";
    if(integrity !== "") {
        script.integrity = integrity;
    }
    if(crossorigin !== "") {
        script.crossOrigin = crossorigin;
    }

    document.getElementById('mainScript').after(script);
}

include("public/js/ajaxRequests.js");
include("public/js/updateData.js");
include("public/js/buttonManagement.js");
include("https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js", "sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0", "anonymous");
include("https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js");
