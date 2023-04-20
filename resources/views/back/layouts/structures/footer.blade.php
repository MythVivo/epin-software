@include('back.layouts.structures.footer-text')
@include('back.layouts.structures.footer-static-area')
<!-- jQuery  -->
<script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'js/jquery.min.js')}}"></script>
<script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'js/metismenu.min.js')}}"></script>
<script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'js/waves.js')}}"></script>
<script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'js/feather.min.js')}}"></script>
<script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'js/jquery.slimscroll.min.js')}}"></script>
<script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'js/jquery-ui.min.js')}}"></script>

<!-- App js -->

<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'js/app.js')}}"></script>


{{--<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>--}}
{{--<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js"></script>--}}
{{--<script>--}}
{{--    //import {firebase} from "https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js";--}}
{{--    //import {getMessaging, getToken} from "https://www.gstatic.com/firebasejs/9.0.1/firebase-messaging.js";--}}
{{--    // https://firebase.google.com/docs/web/setup#available-libraries--}}
{{--    var firebaseConfig = {--}}
{{--        apiKey: "AIzaSyBdFC2zS1eGdmuXdwxn7HwTNCV8ZpSB2w8",--}}
{{--        authDomain: "oyuneks-001.firebaseapp.com",--}}
{{--        projectId: "oyuneks-001",--}}
{{--        storageBucket: "oyuneks-001.appspot.com",--}}
{{--        messagingSenderId: "524101383276",--}}
{{--        appId: "1:524101383276:web:26668f3c052c28cb921449",--}}
{{--        measurementId: "G-S30GS614Y7"--}}
{{--    };--}}
{{--    firebase.initializeApp(firebaseConfig);--}}
{{--    const messaging = firebase.messaging();--}}
{{--    messaging.getToken({--}}
{{--        vapidKey: "BIi_Rzt_QDjF2CZnl8NAzgCtjnNHsM_r4zWEczLXpoKa489H4BpF-OaWIwOHuoGkUrgCjSCoFwHJU-XgaUPxuyE"--}}
{{--    });--}}

{{--    function startFCM() {--}}
{{--        messaging--}}
{{--            .requestPermission()--}}
{{--            .then(function() {--}}
{{--                return messaging.getToken()--}}
{{--            })--}}
{{--            .then(function(response) {--}}
{{--                $.ajaxSetup({--}}
{{--                    headers: {--}}
{{--                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
{{--                    }--}}
{{--                });--}}
{{--                $.ajax({--}}
{{--                    url: '{{ route("deviceTokenRegister") }}',--}}
{{--                    type: 'POST',--}}
{{--                    data: {--}}
{{--                        token: response--}}
{{--                    },--}}
{{--                    dataType: 'JSON',--}}
{{--                    success: function(response) {--}}
{{--                        alert('Bildirimlere izin verildi');--}}
{{--                    },--}}
{{--                    error: function(error) {--}}
{{--                        alert(error);--}}
{{--                    },--}}
{{--                });--}}

{{--            }).catch(function(error) {--}}
{{--                alert(error);--}}
{{--            });--}}
{{--    }--}}

{{--    messaging.onMessage(function(payload) {--}}
{{--        const title = payload.notification.title;--}}
{{--        const options = {--}}
{{--            body: payload.notification.body,--}}
{{--            icon: payload.notification.icon,--}}
{{--            click_action: payload.notification.click_action,--}}
{{--        };--}}
{{--        new Notification(title, options);--}}
{{--    });--}}
{{--    /*--}}
{{--    console.log(messaging);--}}
{{--    messaging.firebaseDependencies.installations.getToken('BIi_Rzt_QDjF2CZnl8NAzgCtjnNHsM_r4zWEczLXpoKa489H4BpF-OaWIwOHuoGkUrgCjSCoFwHJU-XgaUPxuyE').then((currentToken) => {--}}
{{--        if (currentToken) {--}}
{{--            $.ajaxSetup({--}}
{{--                headers: {--}}
{{--                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
{{--                }--}}
{{--            });--}}
{{--            $.ajax({--}}
{{--                url: '{{ route("deviceTokenRegister") }}',--}}
{{--                type: 'POST',--}}
{{--                data: {--}}
{{--                    token: currentToken--}}
{{--                },--}}
{{--                success: function (response) {--}}
{{--                },--}}
{{--                error: function (error) {--}}
{{--                },--}}
{{--            });--}}
{{--        } else {--}}
{{--            console.log('No registration token available. Request permission to generate one.');--}}
{{--        }--}}
{{--    }).catch((err) => {--}}
{{--        console.log('An error occurred while retrieving token. ', err);--}}
{{--    });--}}
{{--    */--}}
{{--</script>--}}

@yield('js')
<script>
    $(".change-theme").on("change", function(event) {
        if (event.target.checked) {
            $("body").addClass("dark")
            document.cookie = "theme=dark; expires=Thu, 18 Dec 2025 12:00:00 UTC; path=/";
            $("#dark").attr("rel", "stylesheet");
            $("#dark1").attr("rel", "stylesheet");
            $("#light").attr("rel", "stylesheet alternate");
            $("#light1").attr("rel", "stylesheet alternate");
        } else {
            document.cookie = "theme=light; expires=Thu, 18 Dec 2025 12:00:00 UTC; path=/";
            $("body").removeClass("dark")
            $("#light").attr("rel", "stylesheet");
            $("#light1").attr("rel", "stylesheet");
            $("#dark").attr("rel", "stylesheet alternate");
            $("#dark1").attr("rel", "stylesheet alternate");
        }
    });

    if ($("#flexSwitchCheckDefault").is(':checked')) {
        $("body").addClass("dark")
        document.cookie = "theme=dark; expires=Thu, 18 Dec 2025 12:00:00 UTC; path=/";
        $("#dark").attr("rel", "stylesheet");
        $("#dark1").attr("rel", "stylesheet");
        $("#light").attr("rel", "stylesheet alternate");
        $("#light1").attr("rel", "stylesheet alternate");
    } else {
        document.cookie = "theme=light; expires=Thu, 18 Dec 2025 12:00:00 UTC; path=/";
        $("body").removeClass("dark")
        $("#light").attr("rel", "stylesheet");
        $("#light1").attr("rel", "stylesheet");
        $("#dark").attr("rel", "stylesheet alternate");
        $("#dark1").attr("rel", "stylesheet alternate");
    }

    $(document).on('focusin', function(e) {
        if ($(event.target).closest(".mce-window").length) {
            e.stopImmediatePropagation();
        }
    });
    var notifySound = new Audio('https://widget-v4.tidiochat.com//tururu.mp3');
    notifySound.loop = false;

    function getWaitAll() {
        const apiAdapter = [{
                dom: $("#ilan-siparisleri"),
                api: "getWaitIlanSiparisler",
                head: "İlan Sip : "
            },
            {
                dom: $("#alis-ilanlari"),
                api: "getWaitAlisIlanlari",
                head: "Alış İlan : "
            },
            {
                dom: $("#oyun-parasi-siparisleri"),
                api: "getWaitOyunParasiSiparisleri",
                head: "Oyun Parası : "
            },
            {
                dom: $("#para-cekim-talepleri"),
                api: "getWaitParaCekimTalepleri",
                head: "Para Çekim : "
            },
            {
                dom: $("#twitch-para-cekim"),
                api: "getWaitTwitchParaCekim",
                head: "Tw Çekim : "
            },
            {
                dom: $("#twitch-kesintisiz"),
                api: "getWaitTwitchKesintisiz",
                head: "Tw Kes : "
            },
            {
                dom: $("#kimlik-onaylari"),
                api: "getWaitKimlikOnaylari",
                head: "Kimlik Onay : "
            },
            {
                dom: $("#ilan-yorumlari"),
                api: "getWaitIlanYorumlari",
                head: "İlan Yorum : "
            },
            {
                dom: $("#yorumlar"),
                api: "getWaitYorumlar",
                head: "Ürün Yorum : "
            },
            {
                dom: $("#siparisler"),
                api: "getWaitSiparisler",
                head: "Epin Sip : "
            },
            {
                dom: $("#odeme_onay"),
                api: "getWaitodeme_onay",
                head: "Ödeme Onay : "
            },
            {
                dom: $("#razer"),
                api: "getWaitRazer",
                head: "Steam Sip : "
            }
        ];


        $.get("{{route('getWaitAll')}}", function(data) {
            apiAdapter.forEach(item => {
                item.dom && item.dom.html(item.head + data[item.api]);
                data[item.api] > 0 ? item.dom.addClass("btn-danger") : item.dom.removeClass("btn-danger");
                if (data[item.api] > 0 && (
                        item.api == 'getWaitOyunParasiSiparisleri' ||
                        item.api == 'getWaitKimlikOnaylari' ||
                        item.api == 'getWaitIlanYorumlari' ||
                        item.api == 'getWaitYorumlar')) {
                    notifySound.play();
                }
            })
            /*  $("#ilan-siparisleri") && $("#ilan-siparisleri").html("İlan Sip : " + data.getWaitIlanSiparisler);
             data.getWaitIlanSiparisler > 0 ? $("#ilan-siparisleri").addClass("btn-danger") : $("#ilan-siparisleri").removeClass("btn-danger");
             $("#alis-ilanlari") && $("#alis-ilanlari").html("Alış İlan : " + data.getWaitAlisIlanlari);
             $("#oyun-parasi-siparisleri") && $("#oyun-parasi-siparisleri").html("Alış İlan : " + data.getWaitOyunParasiSiparisleri);
             $("#para-cekim-talepleri") && $("#para-cekim-talepleri").html("Para Çekim : " + data.getWaitParaCekimTalepleri);
             $("#twitch-para-cekim") && $("#twitch-para-cekim").html("Tw Çekim : " + data.getWaitTwitchParaCekim);
             $("#twitch-kesintisiz") && $("#twitch-kesintisiz").html("Tw Kes. " + data.getWaitTwitchKesintisiz);
             $("#kimlik-onaylari") && $("#kimlik-onaylari").html("Kimlik Onay : " + data.getWaitKimlikOnaylari);
             $("#ilan-yorumlari") && $("#ilan-yorumlari").html("İlan Yorum : " + data.getWaitIlanYorumlari);
             $("#yorumlar") && $("#yorumlar").html("Ürün Yorum : " + data.getWaitYorumlar);
             $('#siparisler') && $('#siparisler').html("Epin Sip : " + data.getWaitSiparisler); */
        });
    }
    /*
     * İlan Siparişleri
     */
    function ilanSiparisler() {
        $.get("{{route('getWaitIlanSiparisler')}}", function(data) {
            $("#ilan-siparisleri").html("İlan Sip.: " + data);
            if (data > 0) {
                $("#ilan-siparisleri").addClass("btn-danger");
                // notifySound.play();
            } else {
                $("#ilan-siparisleri").removeClass("btn-danger");
            }
        });
    }

    /*
     * Alış İlanları
     */
    function alisIlanlari() {
        $.get("{{route('getWaitAlisIlanlari')}}", function(data) {
            $("#alis-ilanlari").html("Alış İlan : " + data);
            if (data > 0) {
                $("#alis-ilanlari").addClass("btn-danger");
                //notifySound.play();
            } else {
                $("#alis-ilanlari").removeClass("btn-danger");
            }
        });
    }

    /*
     * Oyun Parası Siparişleri
     */
    function oyunParasiSiparisleri() {
        $.get("{{route('getWaitOyunParasiSiparisleri')}}", function(data) {
            $("#oyun-parasi-siparisleri").html("Oyun Parası : " + data);
            if (data > 0) {
                $("#oyun-parasi-siparisleri").addClass("btn-danger");
                notifySound.play();
            } else {
                $("#oyun-parasi-siparisleri").removeClass("btn-danger");
            }
        });
    }

    /*
     * Para Çekim Talepleri
     */
    function paraCekimTalepleri() {
        $.get("{{route('getWaitParaCekimTalepleri')}}", function(data) {
            $("#para-cekim-talepleri").html("Para Çekim : " + data);
            if (data > 0) {
                $("#para-cekim-talepleri").addClass("btn-danger");
                //notifySound.play();
            } else {
                $("#para-cekim-talepleri").removeClass("btn-danger");
            }
        });
    }

    /*
     * Twitch Para Çekim
     */
    function twitchParaCekim() {
        $.get("{{route('getWaitTwitchParaCekim')}}", function(data) {
            $("#twitch-para-cekim").html("Tw Çekim : " + data);
            if (data > 0) {
                $("#twitch-para-cekim").addClass("btn-danger");
                //notifySound.play();
            } else {
                $("#twitch-para-cekim").removeClass("btn-danger");
            }
        });
    }

    /*
     * Twitch Kesintisiz
     */
    function twitchKesintisiz() {
        $.get("{{route('getWaitTwitchKesintisiz')}}", function(data) {
            $("#twitch-kesintisiz").html("Tw Kes. : " + data);
            if (data > 0) {
                $("#twitch-kesintisiz").addClass("btn-danger");
                //notifySound.play();
            } else {
                $("#twitch-kesintisiz").removeClass("btn-danger");
            }
        });
    }

    /*
     * Kimlik Onayları
     */
    function kimlikOnaylari() {
        $.get("{{route('getWaitKimlikOnaylari')}}", function(data) {
            $("#kimlik-onaylari").html("Kimlik Onay : " + data);
            if (data > 0) {
                $("#kimlik-onaylari").addClass("btn-danger");
                notifySound.play();
            } else {
                $("#kimlik-onaylari").removeClass("btn-danger");
            }
        });
    }

    /*
     * İlan yorumları
     */
    function ilanYorumlari() {
        $.get("{{route('getWaitIlanYorumlari')}}", function(data) {
            $("#ilan-yorumlari").html("İlan Yorum : " + data);
            if (data > 0) {
                $("#ilan-yorumlari").addClass("btn-danger");
                notifySound.play();
            } else {
                $("#ilan-yorumlari").removeClass("btn-danger");
            }
        });
    }

    function Yorumlar() {
        $.get("{{route('getWaitYorumlar')}}", function(data) {
            $("#yorumlar").html("Ürün Yorum : " + data);
            if (data > 0) {
                $("#yorumlar").addClass("btn-danger");
                notifySound.play();
            } else {
                $("#yorumlar").removeClass("btn-danger");
            }
        });
    }

    function esiparisler() {
        $.get("{{route('getWaitSiparisler')}}", function(data) {
            $('#siparisler').html("Epin Sip : " + data);
            if (data > 0) {
                $('#siparisler').addClass("btn-danger");
                //notifySound.play();
            } else {
                $('#siparisler').removeClass("btn-danger");
            }
        });
    }

    function odeme_onay() {
        $.get("{{route('getWaitodeme_onay')}}", function(data) {
            $('#odeme_onay').html("Ödeme Onay : " + data);
            if (data > 0) {$('#odeme_onay').addClass("btn-danger");} else {$('#odeme_onay').removeClass("btn-danger");
            }
        });
    }

    function razer() {
        $.get("{{route('getWaitRazer')}}", function(data) {
            $('#razer').html("Razer : " + data);
            if (data > 0) {$('#razer').addClass("btn-danger");} else {$('#razer').removeClass("btn-danger");
            }
        });
    }


    getWaitAll();

    $(document).ready(function() {
        setInterval(getWaitAll, 10000);
    });
    {{--document.title = "Time :{{ round(microtime(true) - LARAVEL_START, 3) }} sn"--}}
</script>
</body>

</html>
