<!DOCTYPE html>
<html lang="{{ get_default_language_code() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $basic_settings->sitename(__($page_title??'')) }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- favicon -->
    <link rel="shortcut icon" href="{{ get_fav_agent($basic_settings) }}" type="image/x-icon">
    <!-- fontawesome css link -->
    <link rel="stylesheet" href="{{ asset('public/frontend/css/fontawesome-all.css') }}">
    <!-- bootstrap css link -->
    <link rel="stylesheet" href="{{ asset('public/frontend/css/bootstrap.css') }}">
    <!-- swipper css link -->
    <link rel="stylesheet" href="{{ asset('public/frontend/css/swiper.css') }}">
    <!-- lightcase css links -->
    <link rel="stylesheet" href="{{ asset('public/frontend/css/lightcase.css') }}">
    <!-- line-awesome-icon css -->
    <link rel="stylesheet" href="{{ asset('public/frontend/css/line-awesome.css') }}">
    <!-- animate.css -->
    <link rel="stylesheet" href="{{ asset('public/frontend/css/animate.css') }}">
    <!-- Aos CSS -->
    <link rel="stylesheet" href="{{ asset('public/frontend/css/aos.css') }}">
    <!-- nice-select -->
    <link rel="stylesheet" href="{{ asset('public/frontend/css/nice-select.css') }}">
    <!-- country-select -->
    <link rel="stylesheet" href="{{ asset('public/frontend/css/countrySelect.css') }}">
    <!-- select2-select css link -->
    <link rel="stylesheet" href="{{ asset('public/backend/css/select2.css') }}">
    <!-- main style css link -->
    <link rel="stylesheet" href="{{ asset('public/frontend/css/style.css') }}">
    <!-- Fileholder CSS CDN -->
    <link rel="stylesheet" href="https://appdevs.cloud/cdn/fileholder/v1.0/css/fileholder-style.css" type="text/css">

    @php
        $color = @$basic_settings->agent_base_color ?? '#723eeb';
    @endphp

    <style>
        :root {
            --primary-color: {{$color}};
        }

    </style>

    @stack('css')
</head>
<body class="{{ selectedLangDir() ?? "ltr"}}">
    
@yield("content")
<!-- jquery -->
<script src="{{ asset('public/frontend/js/jquery-3.6.0.js') }}"></script>
<!-- bootstrap js -->
<script src="{{ asset('public/frontend/js/bootstrap.bundle.js') }}"></script>
<!-- swipper js -->
<script src="{{ asset('public/frontend/js/swiper.js') }}"></script>
<!-- lightcase js-->
<script src="{{ asset('public/frontend/js/lightcase.js') }}"></script>
<!-- smooth scroll js -->
<script src="{{ asset('public/frontend/js/smoothscroll.js') }}"></script>
<!-- aos -->
<script src="{{ asset('public/frontend/js/aos.js') }}"></script>
<!-- country select -->
<script src="{{ asset('public/frontend/js/countrySelect.js') }}"></script>
<!-- Select 2 JS -->
<script src="{{ asset('public/backend/js/select2.js') }}"></script>
<!-- nice select -->
<script src="{{ asset('public/frontend/js/jquery.nice-select.js') }}"></script>

<script>
    var fileHolderAfterLoad = {};
</script>

<script src="https://appdevs.cloud/cdn/fileholder/v1.0/js/fileholder-script.js" type="module"></script>
<script type="module">
    import { fileHolderSettings } from "https://appdevs.cloud/cdn/fileholder/v1.0/js/fileholder-settings.js";
    import { previewFunctions } from "https://appdevs.cloud/cdn/fileholder/v1.0/js/fileholder-script.js";

    var inputFields = document.querySelector(".file-holder");
    fileHolderAfterLoad.previewReInit = function(inputFields){
        previewFunctions.previewReInit(inputFields)
    };

    fileHolderSettings.urls.uploadUrl = "{{ setRoute('fileholder.upload') }}";
    fileHolderSettings.urls.removeUrl = "{{ setRoute('fileholder.remove') }}";

</script>

<script>
    function fileHolderPreviewReInit(selector) {
        var inputField = document.querySelector(selector);
        fileHolderAfterLoad.previewReInit(inputField);
    }
</script>

<!-- main -->
<script src="{{ asset('public/frontend/js/main.js') }}"></script>
 <script>
    function laravelCsrf() {
    return $("head meta[name=csrf-token]").attr("content");
  }
//for popup
function openAlertModal(URL,target,message,actionBtnText = "Remove",method = "DELETE"){
    if(URL == "" || target == "") {
        return false;
    }

    if(message == "") {
        message = "Are you sure to delete ?";
    }
    var method = `<input type="hidden" name="_method" value="${method}">`;
    openModalByContent(
        {
            content: `<div class="card modal-alert border-0">
                        <div class="card-body">
                            <form method="POST" action="${URL}">
                                <input type="hidden" name="_token" value="${laravelCsrf()}">
                                ${method}
                                <div class="head mb-3">
                                    ${message}
                                    <input type="hidden" name="target" value="${target}">
                                </div>
                                <div class="foot d-flex align-items-center justify-content-between">
                                    <button type="button" class="modal-close btn btn--info rounded text-light">{{ __('Close') }}</button>
                                    <button type="submit" class="alert-submit-btn btn btn--danger btn-loading rounded text-light">${actionBtnText}</button>
                                </div>
                            </form>
                        </div>
                    </div>`,
        },

    );
  }
function openModalByContent(data = {
content:"",
animation: "mfp-move-horizontal",
size: "medium",
}) {
$.magnificPopup.open({
    removalDelay: 500,
    items: {
    src: `<div class="white-popup mfp-with-anim ${data.size ?? "medium"}">${data.content}</div>`, // can be a HTML string, jQuery object, or CSS selector
    },
    callbacks: {
    beforeOpen: function() {
        this.st.mainClass = data.animation ?? "mfp-move-horizontal";
    },
    open: function() {
        var modalCloseBtn = this.contentContainer.find(".modal-close");
        $(modalCloseBtn).click(function() {
        $.magnificPopup.close();
        });
    },
    },
    midClick: true,
});
}

</script>

 @include('admin.partials.notify')
@include('frontend.partials.extensions.tawk-to')

@stack('script')

</body>
</html>
