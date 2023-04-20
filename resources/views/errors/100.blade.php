<!doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bir Hata Oluştu (100)</title>    
    <style>
        body{
            display:flex;
            justify-content: center;
			    background-color: cadetblue;
        }

        .error{            
    font-family: "verdana";
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: 50px;
    border: solid 3px;
    border-radius: 20px;
    box-shadow: 10px 10px 30px;
    background-color: darkcyan;
}
        
        .head{
         margin: 50px;
    font-size: 20px;
    color: white;
	    align-self: flex-start;

        }

        .mens{
            margin: 20px 50px 50px 50px;
            font-size: 90px;
			    background-color: aquamarine;
				    box-shadow: 10px 10px 30px;

        }

        .desc{
               margin: 20px 50px 50px 50px;
    font-size: 18px;
    text-decoration: none;
    color: white;
        }
    </style>
</head>
<body>
<div class="error">
    
	<div class="head"><img src="https://oyuneks.com/public/front/site/oyuneks-1-1630332428.png" width="200px"></div>
    <div class="mens">
    <img src="/404.png">
    </div>
    <div class="desc"  ><a style="text-decoration: none;color: white;" href="{{route('homepage')}}">Anladım anasayfaya geri dön</a></div>
</div>
</body>
</html>

