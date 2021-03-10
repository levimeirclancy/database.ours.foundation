<? // mySQL login
$server = "localhost";
$username = "";
$password = "";
$database = "";

$google_analytics_code = "";

$domain = "";

$publisher = $author = "";

$color = "";

// This is normally not changed,
$site_info = [
	"languages"		=> 
		[
		"english", "hebrew", "sorani", "arabic"
		],
	"colors"		=>
		[
		"background"	=> [255,255,255],
		"font"		=> [20,20,20],
		"offset"	=> [50,115,165],
		],
	"dimensions"		=>
		[
		"width"		=> 850,
		],
	"category_array"	=> 
		[
		"offices-units"	=> "Offices and units",
		"demographic"	=> "Demographics",
		"party"		=> "Parties",
		"regions"	=> "Regions",
		"settlements"	=> "Settlements",
		"place"		=> "Places",
		"person"	=> "People",
		"article"	=> "Articles",
//		"bibliography"	=> "Bibliography",
		],
	"appendix_array"	=>
		[
		"regions"	=>
			[
			"unit"		=> "amp-selector-multiple",
			],
		"settlements"	=>
			[
			"latitude"	=> "input-text", 
			"longitude"	=> "input-text",
			],
		"place"		=> 
			[
			"latitude"	=> "input-text", 
			"longitude"	=> "input-text", 
			],
		"person"	=>
			[
			"unit"		=> "amp-selector-multiple",
			"birthday"	=> "date", 
			"email"		=> "input-text", 
			"telephone"	=> "input-text", 
			"website"	=> "input-text", 
			"facebook"	=> "input-text", 
			"twitter"	=> "input-text", 
			],
		],
	]; ?>
