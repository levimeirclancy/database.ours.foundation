<? echo "<br><br><br>";

echo "<amp-carousel type='slides' width='450' heifght='300' loop autoplay delay='2000'>";

echo "<span>".count($information_array)." entries</span>";

	foreach ($header_array as $header_backend => $header_frontend):
		
		if (empty($type_counts_array[$header_backend])): continue; endif;

		echo "<amp-lightbox class='categories-list-popover-thread' id='categories-list-popover-thread-".$header_backend."' on='lightboxClose:categories-popover-close.show;lightboxOpen:categories-popover-close.hide' layout='nodisplay' scrollable>";

			echo "<div role='button' tabindex='0' on='tap:categories-list-popover-thread-".$header_backend.".close' class='popover-close'>Back</div>";	
	
			echo "<p>".number_format($type_counts_array[$header_backend])." ".$header_frontend."</p>";
	

echo "<span>".count($information_array)." entries</span>";

echo "</amp-carousel>"; ?>
