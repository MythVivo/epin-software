<html>
<head>
    <title>{{getSiteName()}} - {{__('general.mesaj-1')}}</title>
<<<<<<< Updated upstream
    <style>
        table {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            padding: 0px;
            position: absolute;
            width: 600px;
            height: 541px;
            left: 0px;
            top: 0px;
            background: #FFFFFF;
        }

        .header {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 0px 167px;
            position: static;
            width: 600px;
            height: 156px;
            left: 0px;
            top: 0px;
        }

        .body {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            padding: 40px 0px 0px;
            position: static;
            width: 600px;
            height: 385px;
            left: calc(50% - 600px / 2);
            top: 156px;
            flex: none;
            order: 1;
            flex-grow: 0;
            margin: 0px 0px;
        }

        .body td {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            padding: 0px 80px;
            position: static;
            width: 600px;
            height: 168px;
            left: 0px;
            top: 40px;
            flex: none;
            order: 0;
            flex-grow: 0;
            margin: 40px 0px;
        }

        .body h1 {
            position: static;
            width: 440px;
            height: 100px;
            left: calc(50% - 440px / 2);
            top: 0px;
            font-family: Montserrat;
            font-style: normal;
            font-weight: bold;
            font-size: 36px;
            line-height: 140%;
            text-align: center;
            color: #000505;
            flex: none;
            order: 0;
            flex-grow: 0;
            margin: 0px 10px;
        }

        .body label {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 0px;
            position: static;
            width: 440px;
            height: 44px;
            left: 80px;
            top: 124px;
            flex: none;
            order: 1;
            flex-grow: 0;
            margin: 24px 0px;
        }

        .body button {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            padding: 12px 50px;
            position: static;
            width: 230px;
            height: 44px;
            left: 0px;
            top: 0px;
            background: #80F1B1;
            border-radius: 37px;
            flex: none;
            order: 0;
            flex-grow: 0;
            margin: 24px 0px;
        }

        .body hr {
            position: static;
            width: 440px;
            height: 2px;
            left: 80px;
            top: 0px;
            background: #D9D9D9;
            border-radius: 10px;
            flex: none;
            order: 0;
            flex-grow: 0;
            margin: 10px 0px;
        }

        .footer {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0px;
            position: static;
            width: 600px;
            height: 96px;
            left: 0px;
            top: 289px;
            flex: none;
            order: 2;
            align-self: stretch;
            flex-grow: 0;
            margin: 40px 0px;
        }

        .footer td:nth-child(1) {
            position: static;
            width: 440px;
            height: 16px;
            left: 0px;
            top: 0px;
            font-family: Montserrat;
            font-style: normal;
            font-weight: 600;
            font-size: 14px;
            line-height: 140%;
            display: flex;
            align-items: center;
            color: #737380;
            flex: none;
            order: 0;
            flex-grow: 0;
            margin: 4px 0px;
        }

        .footer td:nth-child(2) {
            position: static;
            width: 440px;
            height: 14px;
            left: 0px;
            top: 20px;
            font-family: Montserrat;
            font-style: normal;
            font-weight: 500;
            font-size: 10px;
            line-height: 140%;
            display: flex;
            align-items: center;
            color: #737380;
            flex: none;
            order: 1;
            flex-grow: 0;
            margin: 4px 0px;
        }
        table tr td img {
            width: 100%;
        }
=======


    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap');
        body {
    font-family: 'Montserrat', sans-serif;
}
        header{
            background-color:#343891;
            display:flex;
            justify-content:center;
            padding:40px 0;
        }

        header svg{
            width:50px;
        }
        .info-container {
	display: flex;
	justify-content: center;
}
        .info {
	display: flex;
	flex-direction: column;
	justify-content: center;
}
h1{
    font-size:32px;
    font-weight:700;
    margin-top:40px;
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

.button-container{
    text-align:center;
}
>>>>>>> Stashed changes
    </style>
</head>
<body>
<header>
<svg width="48" height="70" viewBox="0 0 48 70" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M47.6949 23.0099V21.6099H38.9249L38.8849 22.9699C38.6549 31.0799 32.1149 37.4299 23.9949 37.4299C15.8749 37.4299 9.33493 31.0799 9.10493 22.9699L9.06493 21.6099H0.304932V23.0099C0.304932 33.7099 7.44493 42.7799 17.2049 45.6999C7.44493 48.6199 0.304932 57.6899 0.304932 68.3999V69.7899H9.06493L9.10493 68.4399C9.33493 60.3299 15.8749 53.9699 23.9949 53.9699C32.1149 53.9699 38.6549 60.3299 38.8849 68.4399L38.9249 69.7899H47.6949V68.3999C47.6949 57.6899 40.5549 48.6199 30.7849 45.6999C40.5549 42.7799 47.6949 33.7099 47.6949 23.0099ZM44.8549 66.9999H41.5849C41.1549 62.8999 39.3249 59.1099 36.3349 56.1999C33.0149 52.9599 28.6349 51.1799 23.9949 51.1799C19.3649 51.1799 14.9749 52.9599 11.6549 56.1999C8.67493 59.1099 6.83493 62.8999 6.40493 66.9999H3.13493C3.85493 56.1199 12.9349 47.4899 23.9949 47.4899C35.0549 47.4899 44.1349 56.1199 44.8549 66.9999ZM23.9949 43.9099C12.9349 43.9099 3.85493 35.2799 3.13493 24.3999H6.40493C6.83493 28.4999 8.67493 32.2999 11.6549 35.2099C14.9749 38.4399 19.3649 40.2199 23.9949 40.2199C28.6349 40.2199 33.0149 38.4399 36.3349 35.2099C39.3249 32.2999 41.1549 28.4999 41.5849 24.3999H44.8549C44.1349 35.2799 35.0549 43.9099 23.9949 43.9099Z" fill="#80F1B1"/>
<path d="M36.305 9.44996C34.765 4.10996 29.825 0.209961 24.005 0.209961C18.175 0.209961 13.245 4.10996 11.705 9.44996C8.21497 10.26 6.59497 11.37 6.59497 12.91C6.59497 14.44 8.20497 15.55 11.645 16.36C13.105 21.8 18.095 25.83 24.005 25.83C29.915 25.83 34.905 21.8 36.365 16.36C39.795 15.55 41.405 14.44 41.405 12.91C41.405 11.37 39.785 10.26 36.305 9.44996ZM11.225 13.88C9.92497 13.5 9.26497 13.13 9.00497 12.91C9.26497 12.69 9.92497 12.33 11.245 11.94C11.205 12.29 11.195 12.65 11.195 13.02C11.195 13.31 11.205 13.6 11.225 13.88ZM13.495 13.02C13.495 7.21996 18.215 2.50996 24.005 2.50996C29.805 2.50996 34.515 7.22996 34.515 13.02C34.515 13.49 34.485 13.97 34.415 14.43C31.535 14.97 27.875 15.27 23.995 15.27C20.125 15.27 16.465 14.97 13.575 14.43C13.525 13.97 13.495 13.5 13.495 13.02ZM24.005 23.54C19.575 23.54 15.755 20.78 14.225 16.87C17.065 17.32 20.455 17.56 24.005 17.56C27.555 17.56 30.945 17.32 33.785 16.87C32.255 20.78 28.435 23.54 24.005 23.54ZM36.785 13.88C36.805 13.59 36.815 13.31 36.815 13.02C36.815 12.66 36.805 12.29 36.765 11.94C38.085 12.32 38.745 12.69 39.005 12.91C38.735 13.13 38.085 13.5 36.785 13.88Z" fill="#80F1B1"/>
</svg>
</header>
<div class=info-container>
<div class="info">
<h1>Hesabınızı onaylamak için</h1>
<div class="button-container">
<a class="btn-inline color-yellow" href="{{route('active', [$email, $token])}}">Hesabı etkinleştir</a>
</div>
</div>
</div>

<<<<<<< Updated upstream
<table>
    <tr class="header">
        <td><img src="images/lfNFADVDuTFyiS4vblx6UCRVaW1aiL.png"></td>
    </tr>
    <tr class="body">
        <td>
            <h1> Buraya açılış mesajı gelecektir. </h1>
            <label>
                <button>Etkinleştir</button>
            </label>
            <hr>
        </td>
    </tr>
    <tr class="footer">
        <td>©2020 Oyuneks</td>
        <td>oyuneks.com — +90 XXX XXX XX XX — Adres</td>
        <td></td>
    </tr>
</table>

<p>Hesabınızı onaylamak için <a href="{{route('active', [$email, $token])}}">buraya</a> tıklayın</p>
=======
>>>>>>> Stashed changes

<p>Uyarı: Eğer {{getSiteName()}} websitesine siz kayıt olmadıysanız <a
        href="mailto:{{getSiteContactEmail()}}">buradan</a> bize bildirin.</p>

</body>

</html>
