<? echo "<br><br><br>";

echo "<amp-carousel id='home-carousel' type='slides' width='450' heifght='300' layout='responsive' loop autoplay delay='2000'>";

echo "<span>".count($information_array)." entries</span>";

foreach ($header_array as $header_backend => $header_frontend):
	if (empty($type_counts_array[$header_backend])): continue; endif;
	echo "<span>".$type_counts_array[$header_backend]." ".$header_frontend."</span>";
	endforeach;

if (!(empty($coordinate_counts))):
	echo "<span>".$coordinate_counts." map points</span>";
	endif;

echo "</amp-carousel>"; ?>
