<div class="bg-white">
    <style>
        <style>
        #customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #customers td, #customers th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #customers tr:nth-child(even){background-color: #f2f2f2;}

        #customers tr:hover {background-color: #ddd;}

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #04AA6D;
            color: white;
        }
    </style>
    <?php
    $ch = curl_init();
    $headers = array(
        'Authorization: ' . getAuthName(),
        'ApiName: ' . getApiName(),
        'ApiKey: ' . getApiKey(),
    );
    curl_setopt($ch, CURLOPT_URL, env('EPIN_BASE') . '/GetGameList');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_POSTFIELDS, '');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $response = curl_exec($ch);
    $result_game = json_decode($response);
    curl_close($ch);
    ?>
    <table id="customers">
        <thead>
        <tr>
            <th>Oyun Adı</th>
            <th>Paket Adı</th>
            <th>Fiyat</th>
            <th>Percantage</th>
        </tr>
        </thead>
        <tbody>
        @foreach($result_game->GameDto->GameViewModel as $u)
            @foreach($u->GameItemsViewModel as $uu)
                <tr>
                    <td>{{$u->Name}}</td>
                    <td>{{$uu->Name}}</td>
                    <td>₺{{$uu->Price}}</td>
                    <td>%{{$uu->Percentage}}</td>
                </tr>
            @endforeach
        @endforeach
        </tbody>
    </table>
</div>