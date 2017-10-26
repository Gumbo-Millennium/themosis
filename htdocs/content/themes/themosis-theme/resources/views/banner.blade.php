<?php

	// Functie voor een wilde banner

	$banners = [
		"brixcrm" => "http://gumbo-millennium.nl/site/images/banner/banner_brixcrm_inhouse.gif",
		"topicus" => "http://gumbo-millennium.nl/site/images/banner/banner_topicus.gif"
		];
	$key = array_rand($banners);
?>


<div class="banner">
	<img src="{{ $banners[$key] }}"/>
</div>
