<?php

    // Functie voor een wilde banner

    $banners = [
        "brixcrm" => "http://gumbo-millennium.nl/site/images/banner/banner_brixcrm_inhouse.gif",
        "topicus" => "http://gumbo-millennium.nl/site/images/banner/banner_topicus.gif"
        ];
    $key = array_rand($banners);
?>


<div class="support-banner">
    <div class="support-banner__inner">
        <img src="{{ $banners[$key] }}"/>
    </div>
</div>
