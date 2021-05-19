<html>
<head>
    <title>{{getSiteName()}} - {{__('general.mesaj-1')}}</title>
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
    </style>
</head>
<body>

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

<p>Uyarı: Eğer {{getSiteName()}} websitesine siz kayıt olmadıysanız <a
        href="mailto:{{getSiteContactEmail()}}">buradan</a> bize bildirin.</p>

</body>

</html>
