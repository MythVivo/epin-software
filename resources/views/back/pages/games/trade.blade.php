<?php
$oyun = \App\Models\Games::where('link', $oyun)->first();
$u = \App\Models\GamesTitles::where('link', $trade)->first();
?>
@include('back.pages.games.GamesTitlesTypes.trade')
