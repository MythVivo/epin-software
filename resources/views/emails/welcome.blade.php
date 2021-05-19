<html>
<head>
    <title>{{getSiteName()}} - {{__('general.mesaj-4')}}</title>
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap');

    body {
        font-family: 'Montserrat', sans-serif;
        margin: 0;
    }

    header {

        display: flex;

        padding: 18px 0 18px 60px;
    }

    header svg {
        width: 50px;
    }

    .page-container {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
    }

    .page-inner {
        display: flex;
        flex-direction: column;
        justify-content: center;
        width: 100%;
        max-width: 600px;
    }

    h1 {
        font-size: 32px;
        font-weight: 700;
        margin-top: 40px;
        text-align: center;
    }

    .btn-inline {
        position: relative;
        display: inline-block;
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        line-height: 19.5px;
        color: #212529;
        text-align: center;
        text-decoration: none;
        vertical-align: middle;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
        background-color: transparent;
        border: 1px solid transparent;
        padding: 10px 50px;
        font-size: 16px;
        border-radius: 40px;
        transition: all cubic-bezier(0.23, 1, 0.32, 1) .3s;
    }

    .color-yellow {
        background-color: #80F1B1;
        color: #343891;
    }

    .color-blue {
        background-color: #343891;
        color: #fff;
    }

    .button-container {
        text-align: center;
    }

    .message {
        width: 100%;
        background: #343891;
        padding: 40px 80px;
    }

    .message h1 {
        color: white;
    }

    figure {
        margin: 0;
        width: 100%
    }

    .text {
        width: 100%;
        padding: 40px 80px;
    }

    hr {
        width: auto;
        background: #D9D9D9;
        border: 0;
        height: 2px;
        margin-bottom: 40px;

        margin-left: 80px;
        margin-right: 80px;
    }

    footer {
        padding: 40px 80px;

    }


    footer p {
        color: #737380;
        font-size: 14px;
        font-weight: 600;
        margin: 0;
    }

    footer ul {
        list-style: none;
        margin: 0;
        padding: 0;
        margin-bottom: 5px;
    }

    footer ul li {
        display: inline-block;

    }

    footer ul.contact li {
        color: #737380;
        font-size: 10px;
        font-weight: 500;
    }

    footer ul.social {
        margin-left: -8px;
    }

    footer ul.social li a {
        display: inline-block;
        width: 16px;
        height: 16px;
        padding: 3px;
        margin-right: 5px;
    }

    footer ul.social li a svg {
        width: 100%;
        height: 100%;
    }

</style>
<body>
<header>
    <img src=" data:image/png;base64,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">
</header>
<div class=page-container>
    <figure>
        <img src="">
    </figure>
    <div class="message">
        <h1>Sayın {{$user}}, {{getSiteName()}} web sitesine hoşgeldiniz. Tüm duyurulardan haberdar olmak için hesap
            ayarlarınızdan sms ve e-posta bildirimlerinizi aktif edin.</h1>
        <div class="button-container">
            <a class="btn-inline color-yellow" href="{{route('active', [$email, $token])}}">Hesabı etkinleştir</a>
        </div>
    </div>


    <div class="text">
        <h3>Açıklama başlığı</h3>
        <p>Açıklama Metni</p>
        <p>Sayın {{$user}}, {{getSiteName()}} web sitesine hoşgeldiniz. Tüm duyurulardan haberdar olmak için hesap
            ayarlarınızdan sms ve e-posta bildirimlerinizi aktif edin.</p>

        <p>Uyarı: Eğer {{getSiteName()}} websitesine siz kayıt olmadıysanız <a
                    href="mailto:{{getSiteContactEmail()}}">buradan</a> bize bildirin.</p>

        <a class="btn-inline color-blue" href="{{route('active', [$email, $token])}}">Hesabı etkinleştir</a>

    </div>


</div><!--page-container END-->


<hr>
<footer>

    <p>&copy;2021 Oyuneks</p>
    <ul class="contact">
        <li><a>Oyuneks.com</a></li>
        <li>000000</li>
    </ul>

    <ul class="social">

        <li>
            <a>
                <svg width="6" height="11" viewBox="0 0 6 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                            d="M4.4959 1.82639H5.5V0.0775586C5.32685 0.0537109 4.73092 0 4.03724 0C2.58931 0 1.59767 0.910723 1.59767 2.58457V4.125H0V6.08029H1.59767V11H3.55646V6.08072H5.08947L5.33286 4.12543H3.55603V2.77836C3.55646 2.21332 3.70856 1.82639 4.4959 1.82639Z"
                            fill="#73737F"/>
                </svg>
            </a>
        </li>

        <li>
            <a>
                <svg width="12" height="10" viewBox="0 0 12 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                            d="M12 1.17036C11.549 1.36602 11.0721 1.49536 10.584 1.55436C11.0989 1.24675 11.4848 0.76283 11.67 0.19236C11.1861 0.480398 10.6565 0.683338 10.104 0.79236C9.73473 0.391903 9.24298 0.125338 8.70589 0.034471C8.16879 -0.0563959 7.61673 0.0335752 7.13627 0.290274C6.65582 0.546972 6.27416 0.955879 6.05114 1.45286C5.82813 1.94984 5.77638 2.50679 5.904 3.03636C4.92566 2.98688 3.96866 2.73213 3.09519 2.28868C2.22172 1.84522 1.45131 1.22298 0.834 0.46236C0.617483 0.840461 0.503712 1.26865 0.504 1.70436C0.503232 2.10899 0.60253 2.50753 0.793053 2.8645C0.983576 3.22147 1.25941 3.52579 1.596 3.75036C1.20479 3.73972 0.821931 3.63474 0.48 3.44436V3.47436C0.482932 4.04129 0.681597 4.58981 1.04239 5.02714C1.40318 5.46446 1.90395 5.76374 2.46 5.87436C2.24595 5.9395 2.02373 5.97384 1.8 5.97636C1.64513 5.97455 1.49065 5.96051 1.338 5.93436C1.49635 6.42205 1.80277 6.84825 2.21464 7.15366C2.62652 7.45906 3.12335 7.62849 3.636 7.63836C2.77032 8.31952 1.70153 8.69127 0.6 8.69436C0.399441 8.69502 0.199041 8.68299 0 8.65836C1.12466 9.38451 2.43529 9.76999 3.774 9.76836C4.69782 9.77795 5.61428 9.60337 6.46987 9.25481C7.32546 8.90624 8.10301 8.39069 8.75712 7.73826C9.41123 7.08582 9.92878 6.30959 10.2795 5.4549C10.6303 4.60021 10.8072 3.6842 10.8 2.76036C10.8 2.65836 10.8 2.55036 10.8 2.44236C11.2708 2.09125 11.6769 1.66081 12 1.17036Z"
                            fill="#737380"/>
                </svg>
            </a>
        </li>

        <li>
            <a>
                <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                          d="M5.50221 2.67502C3.94188 2.67502 2.67588 3.94053 2.67588 5.50073C2.67588 7.06093 3.94188 8.32645 5.50221 8.32645C7.06253 8.32645 8.32854 7.06093 8.32854 5.50073C8.32854 3.94053 7.06275 2.67502 5.50221 2.67502ZM5.50221 7.33367C4.49005 7.33367 3.66887 6.51288 3.66887 5.50073C3.66887 4.48858 4.49005 3.66779 5.50221 3.66779C6.51458 3.66779 7.33554 4.48858 7.33554 5.50073C7.33554 6.51288 6.51458 7.33367 5.50221 7.33367Z"
                          fill="#73737F"/>
                    <path
                            d="M8.44048 3.22365C8.80499 3.22365 9.10039 2.9281 9.10039 2.56388C9.10039 2.19945 8.80499 1.90411 8.44048 1.90411C8.07597 1.90411 7.78057 2.19945 7.78057 2.56388C7.78057 2.92832 8.07618 3.22365 8.44048 3.22365Z"
                            fill="#73737F"/>
                    <path fill-rule="evenodd" clip-rule="evenodd"
                          d="M10.9722 3.23407C10.9464 2.64964 10.8519 2.24774 10.7164 1.89967C10.5767 1.53007 10.3618 1.19921 10.0802 0.924111C9.80505 0.644704 9.47197 0.427722 9.1066 0.29017C8.75629 0.154772 8.35647 0.0602728 7.77191 0.0344416C7.18305 0.00645781 6.99595 0 5.50215 0C4.00836 0 3.82147 0.0064578 3.23476 0.032289C2.65021 0.0581202 2.24823 0.152619 1.90008 0.288018C1.5304 0.427722 1.19947 0.642551 0.92431 0.924111C0.644842 1.19921 0.427814 1.53222 0.290233 1.89752C0.154805 2.24774 0.0602857 2.64748 0.034449 3.23191C0.00645916 3.82065 0 4.00771 0 5.50118C0 6.99466 0.00645919 7.1815 0.032296 7.76809C0.0581327 8.35252 0.152652 8.75441 0.28808 9.10248C0.427814 9.47208 0.644842 9.80294 0.92431 10.078C1.19947 10.3574 1.53255 10.5744 1.89793 10.712C2.24823 10.8474 2.64805 10.9419 3.23261 10.9677C3.81932 10.9935 4.00642 11 5.5 11C6.99358 11 7.18068 10.9935 7.76739 10.9677C8.35195 10.9419 8.75393 10.8474 9.10208 10.712C9.84144 10.4261 10.426 9.84169 10.7119 9.10248C10.8473 8.75226 10.9419 8.35252 10.9677 7.76809C10.9935 7.1815 11 6.99444 11 5.50118C11 4.00793 10.9981 3.82086 10.9722 3.23407ZM9.9816 7.72525C9.95792 8.26254 9.86771 8.5525 9.79235 8.74601C9.6074 9.22518 9.22717 9.60555 8.74768 9.79045C8.55434 9.86558 8.26195 9.95599 7.7267 9.97945C7.14645 10.0053 6.97227 10.0117 5.50431 10.0117C4.03635 10.0117 3.86001 10.0053 3.28191 9.97945C2.74451 9.95577 2.45449 9.86558 2.26093 9.79045C2.02237 9.70241 1.80534 9.56271 1.62901 9.37995C1.44621 9.2015 1.30669 8.98667 1.21842 8.74817C1.14328 8.55486 1.05285 8.26254 1.02938 7.7274C1.00354 7.14728 0.997084 6.97313 0.997084 5.50549C0.997084 4.03785 1.00354 3.86155 1.02938 3.28358C1.05306 2.74629 1.14328 2.45633 1.21842 2.26281C1.30648 2.02431 1.44621 1.80732 1.63116 1.63102C1.80943 1.44827 2.02431 1.30878 2.26308 1.22074C2.45643 1.14561 2.74882 1.0552 3.28407 1.03153C3.86432 1.00569 4.0385 0.999237 5.50646 0.999237C6.97657 0.999237 7.15075 1.00569 7.72885 1.03153C8.26626 1.0552 8.55627 1.1454 8.74984 1.22074C8.98839 1.30878 9.20542 1.44848 9.38176 1.63102C9.56455 1.80948 9.70407 2.02431 9.79235 2.26281C9.86749 2.45612 9.95792 2.74844 9.9816 3.28358C10.0074 3.8637 10.0139 4.03785 10.0139 5.50549C10.0139 6.97313 10.0072 7.14491 9.9816 7.72525Z"
                          fill="#73737F"/>
                </svg>
            </a>
        </li>

        <li>
            <a>
                <svg width="12" height="9" viewBox="0 0 12 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                            d="M11.9972 2.97177C12.0242 2.19153 11.8536 1.41712 11.5012 0.720463C11.2621 0.434607 10.9303 0.241698 10.5636 0.175353C9.04684 0.0377263 7.5238 -0.0186823 6.00102 0.00636868C4.48379 -0.0198193 2.96623 0.034769 1.4548 0.169902C1.15598 0.224258 0.879445 0.36442 0.658938 0.573284C0.168339 1.02573 0.113828 1.79978 0.0593167 2.45392C-0.0197722 3.63003 -0.0197722 4.81012 0.0593167 5.98623C0.0750868 6.3544 0.129905 6.71986 0.22285 7.07645C0.288577 7.35177 0.421555 7.60649 0.609878 7.8178C0.831884 8.03773 1.11486 8.18586 1.42209 8.24299C2.59731 8.38805 3.78145 8.44817 4.96531 8.42287C6.8732 8.45013 8.54669 8.42288 10.5254 8.27024C10.8402 8.21663 11.1312 8.06831 11.3595 7.84506C11.5121 7.69238 11.6261 7.50551 11.692 7.29995C11.8869 6.70175 11.9827 6.07569 11.9754 5.44657C11.9972 5.14131 11.9972 3.29884 11.9972 2.97177ZM4.76907 5.77364V2.3994L7.99613 4.0947C7.09124 4.5962 5.89745 5.16311 4.76907 5.77364Z"
                            fill="#737380"/>
                </svg>
            </a>
        </li>

        <li>
            <a>
                <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                            d="M10.2088 2.75425C9.54428 2.75425 8.93116 2.54596 8.4388 2.19458C7.87411 1.79177 7.4684 1.2009 7.32512 0.519865C7.28964 0.351597 7.27054 0.17795 7.26872 0H5.37045V4.90773L5.36818 7.59592C5.36818 8.31461 4.87354 8.92398 4.18787 9.1383C3.98888 9.20048 3.77397 9.22996 3.55018 9.21834C3.26455 9.2035 2.99687 9.12195 2.76422 8.99026C2.26913 8.7101 1.93346 8.20185 1.92436 7.62045C1.91004 6.71176 2.68645 5.97091 3.64616 5.97091C3.8356 5.97091 4.01753 6.00018 4.18787 6.05332V4.71192V4.22971C4.00821 4.20453 3.82536 4.19141 3.64047 4.19141C2.59002 4.19141 1.60757 4.60455 0.905296 5.34884C0.374499 5.91131 0.0561111 6.62892 0.00698847 7.38139C-0.0573713 8.36991 0.324921 9.30958 1.06631 10.0029C1.17524 10.1047 1.28963 10.1991 1.40926 10.2863C2.04489 10.7491 2.82199 11 3.64047 11C3.82536 11 4.00821 10.9871 4.18787 10.9619C4.95245 10.8548 5.65791 10.5236 6.21463 10.0029C6.89871 9.36316 7.27668 8.51386 7.28077 7.60991L7.271 3.59559C7.59734 3.83379 7.95416 4.03089 8.33714 4.18388C8.93275 4.42165 9.56429 4.54214 10.2143 4.54193V3.23775V2.75382C10.2147 2.75425 10.2093 2.75425 10.2088 2.75425Z"
                            fill="#73737F"/>
                </svg>
            </a>
        </li>
    </ul>

</footer>

</body>

</html>
