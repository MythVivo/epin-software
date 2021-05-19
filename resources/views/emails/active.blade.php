<html>
<head>
    <title>{{getSiteName()}} - {{__('general.mesaj-1')}}</title>


    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
        }

        header {
            background-color: #343891;
            display: flex;
            justify-content: center;
            padding: 40px 0;
        }

        header svg {
            width: 50px;
        }

        .page-container {
            display: flex;
            justify-content: center;
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

        .button-container {
            text-align: center;
        }

        hr {
            width: 100%;
            background-color: #D9D9D9;
            border: 0;
            height: 2px;
            margin: 40px 0;
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
</head>
<body>
<header>
    <svg width="48" height="70" viewBox="0 0 48 70" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path
            d="M47.6949 23.0099V21.6099H38.9249L38.8849 22.9699C38.6549 31.0799 32.1149 37.4299 23.9949 37.4299C15.8749 37.4299 9.33493 31.0799 9.10493 22.9699L9.06493 21.6099H0.304932V23.0099C0.304932 33.7099 7.44493 42.7799 17.2049 45.6999C7.44493 48.6199 0.304932 57.6899 0.304932 68.3999V69.7899H9.06493L9.10493 68.4399C9.33493 60.3299 15.8749 53.9699 23.9949 53.9699C32.1149 53.9699 38.6549 60.3299 38.8849 68.4399L38.9249 69.7899H47.6949V68.3999C47.6949 57.6899 40.5549 48.6199 30.7849 45.6999C40.5549 42.7799 47.6949 33.7099 47.6949 23.0099ZM44.8549 66.9999H41.5849C41.1549 62.8999 39.3249 59.1099 36.3349 56.1999C33.0149 52.9599 28.6349 51.1799 23.9949 51.1799C19.3649 51.1799 14.9749 52.9599 11.6549 56.1999C8.67493 59.1099 6.83493 62.8999 6.40493 66.9999H3.13493C3.85493 56.1199 12.9349 47.4899 23.9949 47.4899C35.0549 47.4899 44.1349 56.1199 44.8549 66.9999ZM23.9949 43.9099C12.9349 43.9099 3.85493 35.2799 3.13493 24.3999H6.40493C6.83493 28.4999 8.67493 32.2999 11.6549 35.2099C14.9749 38.4399 19.3649 40.2199 23.9949 40.2199C28.6349 40.2199 33.0149 38.4399 36.3349 35.2099C39.3249 32.2999 41.1549 28.4999 41.5849 24.3999H44.8549C44.1349 35.2799 35.0549 43.9099 23.9949 43.9099Z"
            fill="#80F1B1"/>
        <path
            d="M36.305 9.44996C34.765 4.10996 29.825 0.209961 24.005 0.209961C18.175 0.209961 13.245 4.10996 11.705 9.44996C8.21497 10.26 6.59497 11.37 6.59497 12.91C6.59497 14.44 8.20497 15.55 11.645 16.36C13.105 21.8 18.095 25.83 24.005 25.83C29.915 25.83 34.905 21.8 36.365 16.36C39.795 15.55 41.405 14.44 41.405 12.91C41.405 11.37 39.785 10.26 36.305 9.44996ZM11.225 13.88C9.92497 13.5 9.26497 13.13 9.00497 12.91C9.26497 12.69 9.92497 12.33 11.245 11.94C11.205 12.29 11.195 12.65 11.195 13.02C11.195 13.31 11.205 13.6 11.225 13.88ZM13.495 13.02C13.495 7.21996 18.215 2.50996 24.005 2.50996C29.805 2.50996 34.515 7.22996 34.515 13.02C34.515 13.49 34.485 13.97 34.415 14.43C31.535 14.97 27.875 15.27 23.995 15.27C20.125 15.27 16.465 14.97 13.575 14.43C13.525 13.97 13.495 13.5 13.495 13.02ZM24.005 23.54C19.575 23.54 15.755 20.78 14.225 16.87C17.065 17.32 20.455 17.56 24.005 17.56C27.555 17.56 30.945 17.32 33.785 16.87C32.255 20.78 28.435 23.54 24.005 23.54ZM36.785 13.88C36.805 13.59 36.815 13.31 36.815 13.02C36.815 12.66 36.805 12.29 36.765 11.94C38.085 12.32 38.745 12.69 39.005 12.91C38.735 13.13 38.085 13.5 36.785 13.88Z"
            fill="#80F1B1"/>
    </svg>
</header>
<div class=page-container>
    <div class="page-inner">
        <h1>Hesabınızı onaylamak için</h1>
        <div class="button-container">
            <a class="btn-inline color-yellow" href="{{route('active', [$email, $token])}}">Hesabı etkinleştir</a>

            <hr>
        </div>
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

    </div><!--page-inner END-->

</div><!--page-container END-->


<p>Uyarı: Eğer {{getSiteName()}} websitesine siz kayıt olmadıysanız <a
        href="mailto:{{getSiteContactEmail()}}">buradan</a> bize bildirin.</p>

</body>

</html>
