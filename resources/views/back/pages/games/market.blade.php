<?php
$oyun = \App\Models\Games::where('link', $oyun)->first();
$u = \App\Models\GamesTitles::where('link', $market)->first();
?>
@include('back.pages.games.GamesTitlesTypes.market')