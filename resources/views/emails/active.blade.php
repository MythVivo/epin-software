<html>
<head>
    <title>{{getSiteName()}} - {{__('general.mesaj-1')}}</title>
</head>
<body>

<p>Hesabınızı onaylamak için <a href="{{route('active', [$email, $token])}}">buraya</a> tıklayın</p>

<p>Uyarı: Eğer {{getSiteName()}} websitesine siz kayıt olmadıysanız <a href="mailto:{{getSiteContactEmail()}}">buradan</a> bize bildirin.</p>

</body>

</html>
