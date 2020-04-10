<? 

echo "<h1>".ucfirst($domain)."</h1>";

echo "<amp-carousel id='home-carousel' type='slides' width='400' height='400' layout='fixed-height' loop autoplay delay='2000'>";

echo "<span class='home-carousel-slide'>".count($information_array)." entries</span>";

foreach ($header_array as $header_backend => $header_frontend):
	if (empty($type_counts_array[$header_backend])): continue; endif;
	echo "<span class='home-carousel-slide'>".$type_counts_array[$header_backend]." ".$header_frontend."</span>";
	endforeach;

if (!(empty($coordinate_counts))):
	echo "<span class='home-carousel-slide'>".$coordinate_counts." map points</span>";
	endif;

echo "</amp-carousel>"; ?>
