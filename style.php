<? if (empty($background_color)): $background_color = "rgba(255,255,255,1)"; endif;
if (empty($font_color)): $font_color = "rgba(20,20,20,1)"; endif;

$style_array = [
	
	[
	"css_tags" => "body",
	"css_properties" =>
		[
		"text-align" 		=> "left",
		"font-family"		=> "Times, Serif",
		"font-size"		=> "17px",
		"line-height"		=> "1.3",
		"background"		=> $background_color,
		"margin"		=> "0",
		"padding"		=> "0",
		], ],

	[
	"css_tags" => "a, a:link, a:visited, a:hover",
	"css_contents" =>
		[
		"text-decoration"	=> "none",
		"color"			=> $font_color,
		"white-space"		=> "break-spaces",
		], ],
	
	[
	"css_tags" => ".studies",
	"css_contents" =>
		[
		"box-shadow"		=> "0 0 40px -5px rgba(30,30,30,0.15)",
		"background"		=> "rgba(255,255,255,1)",
		"padding"		=> "50px 0",
		"margin"		=> "150px 0",
		], ],
	
	[
	"css_tags" => ".float_left",
	"css_contents" =>
		[
		"float"			=> "left",	
		], ],
	
	[
	"css_tags" => ".float_right",
	"css_contents" =>
		[
		"float"			=> "right",	
		], ],
	
	[
	"css_tags" => "#navigation-header",
	"css_contents" =>
		[
		"box-sizing"		=> "border-box",
		"width"			=> "100%",
		"background"		=> "rgba(255,255,255,0)",
		"padding"		=> "10px",
		"z-index"		=> "100",
		"margin"		=> "0 0 50px 0",
		], ],
	
	[
	"css_tags" => ".navigation-header-item, .sidebar-back",
	"css_contents" =>
		[
		"font-size"		=> "85%",
		"line-height"		=> "1em",
		"margin"		=> "5px 10px",
		"padding"		=> "8px 10px",
		"border-radius"		=> "100px",
		"background"		=> "rgba(255,255,255,0)",
		"display"		=> "inline-block",
		"cursor"		=> "pointer",
		"text-align"		=> "center",
		"color"			=> "rgba(0,0,0,1)",
		"font-weight"		=> "400",
		"font-family"		=> "Arial, Helvetica, 'Sans Serif'",
		], ],
	
	[
	"css_tags" => ".navigation-header-item::first-letter, .sidebar-back::first-letter",
	"css_contents" =>
		[
		"vertical-align"	=> "top",
		], ],
	
	[
	"css_tags" => "#entries-list-alphabetical .navigation-header-item, #entries-list-hierarchical .navigation-header-item",
	"css_contents" =>
		[
		"top"			=> "0",
		"right"			=> "0",
		"position"		=> "absolute",
		], ],
	
	[
	"css_tags" => "amp-lightbox",
	"css_contents" =>
		[
		"margin"		=> "0",
		"padding"		=> "80px 20px 20px",
		"background"		=> "rgba(255,255,255,1)",
		"box-shadow"		=> "0 0 30px 0 rgba(30,30,30,0.3)",
		"text-align"		=> "left",
		"box-sizing"		=> "border-box",
		"position"		=> "relative",
		"width"			=> "auto",
		], ],
	
	[
	"css_tags" => ".sidebar-back",
	"css_contents" =>
		[
		"display"		=> "block",
		"cursor"		=> "pointer",
		"text-align"		=> "right",
		"padding"		=> "3px 15px",
		"margin"		=> "10px 0 20px 0",
		], ],
	
	[
	"css_tags" => ".sidebar-back:before",
	"css_contents" =>
		[
		"content"		=> "'\293A'",
		"font-size"		=> "1.3em",
		"margin"		=> "0 5px 0 0",
		"display"		=> "inline-block",
		], ],
	
	[
	"css_tags" => "amp-lightbox, amp-sidebar",
	"css_contents" =>
		[
		"margin"		=> "0",
		"padding"		=> "10px 5px 10px 0",
		"background"		=> "rgba(255,255,255,1)",
		"text-align"		=> "left",
		"box-sizing"		=> "border-box",
		"position"		=> "relative",
		"white-space"		=> "normal",
		], ],
	
	[
	"css_tags" => "amp-sidebar",
	"css_contents" =>
		[
		"width"			=> "650px",
		], ],
	
	[
	"css_tags" => "div.sidebar-navigation-item",
	"css_contents" =>
		[
		"display"		=> "block",
		], ],

	[
	"css_tags" => "amp-sidebar label",
	"css_contents" =>
		[
		"padding-top"		=> "10px",
		], ],
	
	[
	"css_tags" => "#login-popover-submit, #logout-popover-submit, #new-popover-submit, #delete-popover-submit, #search-submit",
	"css_contents" =>
		[
		"display"		=> "table",
		"max-width"		=> "300px",
		"margin"		=> "30px 50px",
		"padding"		=> "10px 60px",
		"border"		=> "0",
		"border-radius"		=> "100px",
		"background-image"	=> "linear-gradient(80deg, rgba(50,50,50,0.5), rgba(20,20,20,0.5))",
		"color"			=> "rgba(255,255,255,1)",
		"box-shadow"		=> "none",
		"box-sizing"		=> "border-box",
		], ],
	
	[
	"css_tags" => "#search-submit",
	"css_contents" =>
		[
		"margin"		=> "20px auto",
		], ],
	
	[
	"css_tags" => ".form-feedback",
	"css_contents" =>
		[
		"margin"		=> "15px auto 15px",
		"padding"		=> "10px",
		"text-align"		=> "center",
		], ],
	
	[
	"css_tags" => "@media only screen and (min-width: 650px)",
	"css_contents" =>
		[
		"css_tags" => ".categories-item-button",
		"css_contents" =>
			[
			"display"	=> "inline-block",
			]
		], ],
	
	[
	"css_tags" => ".categories-item-indent-wrapper",
	"css_contents" =>
		[
		"display"		=> "inline",
		"max-width"		=> "400px",
		"overflow"		=> "hidden",
		], ],
	
	[
	"css_tags" => ".categories-item-indent",
	"css_contents" =>
		[
		"float"			=> "left",
		"display"		=> "inline",
		"height"		=> "100%",
		"width"			=> "30px",
		"height"		=> "30px",
		], ],
	
	[
	"css_tags" => ".categories-item-indent::before",
	"css_contents" =>
		[
		"content"		=> "' '",
		], ],

	/// FORMS

	[
	"css_tags" => "*:focus",
	"css_contents" =>
		[
		"outline"		=> "none",
		"outline-width"		=> "0",
		], ],
	
	[
	"css_tags" => "form",
	"css_contents" =>
		[
		"display"		=> "inline",
		], ],

	/// MISCELLANEOUS

	[
	"css_tags" => ".material-icons",
	"css_contents" =>
		[
		"vertical-align"	=> "middle",
		], ],
	
	[
	"css_tags" => ".fadeout",
	"css_contents" =>
		[ 
		"opacity"		=> "0.25",
		], ],
	
	/// ARTICLE

	[
	"css_tags" => "header",
	"css_contents" =>
		[
		"display"		=> "contents",
		], ],
	
	[
	"css_tags" => "h1, h2, h3, h4, h5, h6",
	"css_contents" =>
		[
		"width"			=> "auto",
		"max-width"		=> "850px", 
		"border"		=> "0",
		"display"		=> "block",
		"clear"			=> "both",
		"vertical-align"	=> "top",
		"margin"		=> "30px 0",
		"padding"		=> "20px",
		"text-align"		=> "left",
		"word-break"		=> "normal",
		"font-family"		=> "Times, Serif",
		], ],
	
	[
	"css_tags" => "h1 span",
	"css_contents" =>
		[
		"display"		=> "block",
		"margin"		=> "10px auto",
		], ],
	
	[
	"css_tags" => "h1",
	"css_contents" =>
		[
		"margin"		=> "130px 0 30px",
		"font-size"		=> "2em",
		"line-height"		=> "140%",
		"text-align"		=> "left",
		"font-weight"		=> "400",
		], ],
	
	[
	"css_tags" => "h2",
	"css_contents" =>
		[
		"font-size"		=> "1.5em",
		"line-height"		=> "120%",
		"font-weight"		=> "400",
		], ],

	[
	"css_tags" => "h3",
	"css_contents" =>
		[
		"font-size"		=> "1.3em",
		"font-weight"		=> "400",
		], ],
	
	[
	"css_tags" => "h4, h5, h6",
	"css_contents" =>
		[
		"font-size"		=> "1.1em",
		"font-weight"		=> "400",
		"text-align"		=> "left",
		"font-style"		=> "italic",
		], ],
	
	[
	"css_tags" => "#edit-entry",
	"css_contents" =>
		[
		"float"			=> "right",
		"border"		=> "1px solid #333",
		"padding"		=> "8px 12px",
		], ],
	
	[
	"css_tags" => "body, article",
	"css_contents" =>
		[
		"position"		=> "relative",
		], ],
	
	[
	"css_tags" => "span, div",
	"css_contents" =>
		[
		"background"		=> "inherit",
		], ],
	
	[
	"css_tags" => "article a",
	"css_contents" =>
		[
		"color"			=> $font_color,
		], ],
	
	[
	"css_tags" => "article a:hover",
	"css_contents" =>
		[
		"color"			=> $font_color,
		], ],
	
	[
	"css_tags" => "hr",
	"css_contents" =>
		[
		"display"		=> "block",
		"clear"			=> "both",
		"text-align"		=> "center",
		"margin"		=> "60px 20px 70px",
		"height"		=> "2px",
		"background"		=> "rgba(200,200,200,0.5)",
		"border"		=> "0",
		], ],
	
	[
	"css_tags" => "hr + hr",
	"css_contents" =>
		[
		"display"		=> "none",
		], ],
	
	[
	"css_tags" => "p, summary, ul, ol, blockquote",
	"css_contents" =>
		[
		"width"			=> "auto",
		"max-width"		=> "850px", 
		"border"		=> "0",
		"display"		=> "block",
		"clear"			=> "both",
		"vertical-align"	=> "top",
		"margin"		=> "0",
		"padding"		=> "20px",
		"text-align"		=> "left",
		"position"		=> "relative",
		], ],
	
	[
	"css_tags" => "article, article th, article td, p, summary, blockquote",
	"css_contents" =>
		[
		"font-weight"		=> "400",
		"text-align"		=> "left",
		"font-family"		=> "Times, Serif",
		], ],
	
	[
	"css_tags" => "blockquote",
	"css_contents" =>
		[
		"background"		=> "rgba(255,255,255,1)",
		"border-width"		=> "2px",
		"border-style"		=> "dotted",
		"border-color"		=> "rgba(50,50,50,1)",
		"border-radius"		=> "15px",
		"padding"		=> "20px",
		"margin"		=> "50px 20px",
		"max-width"		=> "700px",
		], ],
	
	[
	"css_tags" => "blockquote cite",
	"css_contents" =>
		[
		"text-align"		=> "left",
		"display"		=> "block",
		"width"			=> "60%",
		"max-width"		=> "750px",
		], ],
	
	[
	"css_tags" => "blockquote cite, blockquote cite a",
	"css_contents" =>
		[
		"color"			=> "rgba(0,0,0,0.7)",
		"font-style"		=> "normal",
		], ],
	
	[
	"css_tags" => "blockquote blockquote",
	"css_contents" =>
		[
		"margin"		=> "20px",
		], ],
	
	[
	"css_tags" => "blockquote:before",
	"css_contents" =>
		[
		"content"		=> "'”'",
		"font-weight"		=> "700",
		"position"		=> "absolute",
		"top"			=> "-13px",
		"box-sizing"		=> "border-box",
		"left"			=> "50%",
		"font-size"		=> "50px",
		"line-height"		=> "20px",
		"vertical-align"	=> "middle",
		"text-align"		=> "center",
		"font-family"		=> "Times",
		"background"		=> "rgba(255,255,255,1)",
		"border"		=> "2px dotted rgba(50,50,50,1)",
		"color"			=> "rgba(50,50,50,1)",
		"padding"		=> "25px 0 0",
		"width"			=> "50px",
		"height"		=> "50px",
		"margin"		=> "-15px",
		"border-radius"		=> "100px",
		], ],
	
	[
	"css_tags" => "p",
	"css_contents" =>
		[
		"text-overflow"		=> "ellipsis",
		"overflow"		=> "hidden",
		], ],
	
	[
	"css_tags" => "article table",
	"css_contents" =>
		[
		"margin"		=> "20px auto",
		"box-sizing"		=> "border-box",
		"table-layout"		=> "auto",
		"border-collapse"	=> "collapse",
		"overflow"		=> "auto",
		"border"		=> "2px solid rgba(245,245,245,1)",
		], ],
	
	[
	"css_tags" => "article table table",
	"css_contents" =>
		[
		"margin"		=> "20px 0",
		], ],
	
	[
	"css_tags" => "article tbody tr:nth-child(odd) td",
	"css_contents" =>
		[
		"background"		=> "rgba(255,255,255,1)",
		], ],
	
	[
	"css_tags" => "article tbody tr:nth-child(even) td",
	"css_contents" =>
		[
		"background"		=> "rgba(250,250,250,1)",
		], ],


	[
	"css_tags" => "article th, article td",
	"css_contents" =>
		[
		"padding"		=> "10px",
		"vertical-align"	=> "top",
		"font-weight"		=> "400",
		"text-align"		=> "left",
		"margin"		=> "0",
		"border"		=> "2px solid rgba(245,245,245,1)",
		], ],
	
	[
	"css_tags" => "article th",
	"css_contents" =>
		[
		"font-style"		=> "italic",
		], ],
	
	[
	"css_tags" => 
		[
		"article th p",
		"article td p",
		"article li p",
		],
	"css_contents" =>
		[
		"margin"		=> "0",
		"padding"		=> "0",
		], ],
	
	[
	"css_tags" => 
		[
		"article th p + p"
		"article td p + p",
		"article li p + p",
		],
	"css_contents" =>
		[
		"margin-top"		=> "10px",
		], ],
	
	[
	"css_tags" => ".entry-metadata-wrapper",
	"css_contents" =>
		[
		"display"		=> "block",
		"text-align"		=> "left",
		"margin"		=> "0 0 100px",
		"opacity"		=> "0.8",
		], ],
	
	[
	"css_tags" => ".entry-metadata, .entry-metadata-more",
	"css_contents" =>
		[
		"display"		=> "inline-block",
		"font-style"		=> "normal",
		"margin"		=> "0",
		"font-size"		=> "1em",
		"padding"		=> "6px 20px",
		"background"		=> "rgba(255,255,255,0.3)",
		"border"		=> "1px solid rgba(100,100,100,0)",
		"border-radius"		=> "100px",
		], ],
	
	[
	"css_tags" => ".entry-metadata-more",
	"css_contents" =>
		[
		"font-style"		=> "normal",
		"margin"		=> "8px 20px",
		"border"		=> "1px solid rgba(100,100,100,0.8)",
		"cursor"		=> "pointer",
		], ],

	/// EDIT

	[
	"css_tags" => "label, input, textarea, amp-selector, .input-button-wrapper",
	"css_contents" =>
		[
		"display"		=> "block",
		"margin"		=> "5px 8px",
		"padding"		=> "8px",
		"width"			=> "95%",
		"text-align"		=> "left",
		"border"		=> "0",
		"border-radius"		=> "6px",
		"box-sizing"		=> "border-box",
		"font-size"		=> "0.9em",
		"line-height"		=> "inherit",
		"outline"		=> "0 none rgba(255,255,255,0)",
		], ],
	
	[
	"css_tags" => "input + .input-button-wrapper, textarea + .input-button-wrapper, amp-selector + .input-button-wrapper",
	"css_contents" =>
		[
		"text-align"		=> "right",
		"max-width"		=> "850px",
		], ],
	
	[
	"css_tags" => "input, textarea, amp-selector",
	"css_contents" =>
		[
		"font-family"		=> "Arial, Helvetica, 'Sans Serif'",
		"margin"		=> "5px 13px",
		"border-radius"		=> "10px",
		"color"			=> "rgba(0,0,0,1)",
		"padding"		=> "15px",
		"max-width"		=> "850px",
		"background"		=> "rgba(255,255,255,1)",
		"border"		=> "1px solid rgba(100,100,100,0.5)",
		"box-shadow"		=> "3px 12px 15px -9px rgba(50,50,50,0.1)",
		], ],
	
	[
	"css_tags" => "input[type=\"date\"]",
	"css_contents" =>
		[
		"max-width"		=> "300px",
		], ],
	
	[
	"css_tags" => "label",
	"css_contents" =>
		[
		"font-style"		=> "italic",
		"color"			=> "rgba(100,100,100,0.8)",
		"padding"		=> "40px 16px 5px",
		], ],
	
	[
	"css_tags" => ".admin-page-input",
	"css_contents" =>
		[
		"display"		=> "container",
		], ],
	
	[
	"css_tags" => ".input-button",
	"css_contents" =>
		[
		"cursor"		=> "pointer",
		"color"			=> "rgba(100,100,100,0.8)",
		"padding"		=> "7px 16px",
		"border"		=> "1px solid rgba(100,100,100,0.8)",
		"border-radius"		=> "100px",
		"display"		=> "inline-block",
		], ],
	
	[
	"css_tags" => "textarea",
	"css_contents" =>
		[
		"height"		=> "600px",
		"max-height"		=> "none",
		], ],
	
	[
	"css_tags" => "amp-selector",
	"css_contents" =>
		[
		"padding"		=> "0",
		"max-height"		=> "365px",
		"overflow-y"		=> "auto",
		"overflow-x"		=> "hidden",
		"position"		=> "relative",
		"outline"		=> "0 none rgba(255,255,255,0)",
		], ],
	
	[
	"css_tags" => "amp-selector span",
	"css_contents" =>
		[
		"padding"		=> "7px 10px",
		"margin"		=> "0",
		"display"		=> "block",
		"text-overflow"		=> "ellipsis",
		"white-space"		=> "nowrap",
		"overflow"		=> "hidden",
		"border-radius"		=> "0",
		"border"		=> "0 none rgba(255,255,255,0)",
		"outline"		=> "0 none rgba(255,255,255,0)",
		"border-radius"		=> "0",
		], ],
	
	[
	"css_tags" => "amp-selector span[selected], amp-selector span[aria-selected=\"true\"]",
	"css_contents" =>
		[
		"background"		=> "rgba(100,100,100,1)",
		"color"			=> "rgba(255,255,255,1)",
		"border"		=> "0 none rgba(255,255,255,0)",
		"outline"		=> "0 none rgba(255,255,255,0)",
		], ],
	
	[
	"css_tags" => "amp-selector span[disabled]",
	"css_contents" =>
		[
		"font-weight"		=> "400",
		"color"			=> "rgba(200,200,200,1)",
		"opacity"		=> "0.9",
		], ],
	
	[
	"css_tags" => "#admin-page-form-snackbar",
	"css_contents" =>
		[
		"position"		=> "fixed",
		"font-family"		=> "Arial, Helvetica, 'San Serif'",
		"bottom"		=> "0",
		"left"			=> "0",
		"right"			=> "0",
		"padding"		=> "10px 90px 10px 10px",
		"vertical-align"	=> "middle",
		"background"		=> "rgba(255,255,255,1)",
		"color"			=> "rgba(0,0,0,1)",
		"box-shadow"		=> "0 -5px 35px -12px rgba(30,30,30,0.3)",
		], ],
	
	[
	"css_tags" => "#admin-page-form-save",
	"css_contents" =>
		[
		"position"		=> "fixed",
		"bottom"		=> "0",
		"right"			=> "0",
		"background"		=> "rgba(150,150,150,1)",
		"color"			=> "rgba(255,255,255,1)",
		"padding"		=> "10px 25px",
		"vertical-align"	=> "middle",
		"border-radius"		=> "0",
		], ],
	
	[
	"css_tags" => "#footer-formula",
	"css_contents" =>
		[
		"display"		=> "none",
		"text-direction"	=> "rtl",
		"margin"		=> "20px 0 50px",
		], ],

	/// Table breaks for mobile

	[
	"css_tags" => "@media only screen and (max-width: 500px)",
	"css_contents" => 

		[
		"css_tags" => "blockquote cite",
		"css_contents" =>
			[
			"width"		=> "100%",
			], ],

		[
		"css_tags" => "article th, article td",
		"css_contents" =>
			[
			"display"	=> "block",
			], ],

		[
		"css_tags" => "article th + th, article td + td, article th + td, article td + th",
		"css_contents" =>
			[
			"border-left"	=> "none",
			"border-top"	=> "2px solid rgba(200,200,200,0.12)",
			], ],
		], ],
	
	[
	"css_tags" => "@media print",
	"css_contents" =>
		[ 
		"#navigation-header, #footer-formula" =>
			[
			"display"	=> "none",
			], ],
		], ],


	/// Lists ... ul, ol, amp-list

	[
	"css_tags" => "ul, ol, amp-list",
	"css_contents" =>
		[
		"position"		=> "relative",
		"text-align"		=> "left",
		"vertical-align"	=> "top",
		"clear"			=> "both",
		"width"			=> "auto",
		"max-width"		=> "850px",
		"margin"		=> "30px",
		"padding"		=> "0",
		"list-style-position"	=> "inside",
		"line-height"		=> "1.4",
		"border-left"		=> "2px solid rgba(200,200,200,0.5)",
		"box-sizing"		=> "border-box",
		], ],
	
	[
	"css_tags" => "ul ul, ul ol, ul amp-list, ol ul, ol ol, ol amp-list, amp-list ul, amp-list ol, amp-list amp-list",
	"css_contents" =>
		[
		"width"			=> "auto",
		"max-width"		=> "none",
		"margin"		=> "3px 5px 3px 1px",
		"padding"		=> "0",
		], ],
	
	[
	"css_tags" => "li",
	"css_contents" =>
		[
		"padding"		=> "5px 5px 5px 6px",
		"margin"		=> "0",
		"border-bottom"		=> "2px dotted rgba(200,200,200,0.5)",
		"position"		=> "relative",
		], ],
	
	[
	"css_tags" => "li:first-child",
	"css_contents" =>
		[
		"padding"		=> "4px 5px 5px 6px",
		], ],
	
	[
	"css_tags" => "li:last-child",
	"css_contents" =>
		[
		"border-bottom"		=> "2px solid rgba(200,200,200,0.5)",
		], ],
	
	[
	"css_tags" => "ul > li, amp-list li",
	"css_contents" =>
		[
		"list-style-type"	=> "none",
		], ],
	
	[
	"css_tags" => "ol",
	"css_contents" =>
		[
		"list-style-type"	=> "decimal",
		], ],
	
	[
	"css_tags" => "ol ol",
	"css_contents" =>
		[
		"list-style-type"	=> "lower-roman",
		], ],
	
	[
	"css_tags" => "ol ol ol",
	"css_contents" =>
		[
		"list-style-type"	=> "lower-alpha",
		], ],
	
	[
	"css_tags" => "amp-sidebar ul, amp-sidebar ol, amp-sidebar amp-list",
	"css_contents" =>
		[
		"font-family"		=> "Arial, Helvetica, 'Sans Serif'",
		"font-size"		=> "0.9em",
		], ],
	
	[
	"css_tags" => 
		[
		"amp-sidebar ul ul",
		"amp-sidebar ol ul",
		"amp-sidebar amp-list ul",
		"amp-sidebar ul ol",
		"amp-sidebar ol ol",
		"amp-sidebar amp-list ol",
		"amp-sidebar ul amp-list",
		"amp-sidebar ol amp-list",
		"amp-sidebar amp-list amp-list",
		],
	"css_contents" =>
		[
		"font-size"		=> "1em",
		], ],
	
	[
	"css_tags" => 
		[
		"amp-sidebar ul li",
		"amp-sidebar ol li",
		"amp-sidebar amp-list li",
		],
	"css_contents" =>
		[
		"text-overflow"		=> "ellipsis",
		"white-space"		=> "nowrap",
		"overflow"		=> "hidden",
		], ],
	
	[
	"css_tags" => ".unnested-list",
	"css_contents" =>
		[
		"border"		=> "0",
		], ],
	
	[
	"css_tags" => ".unnested-list li",
	"css_contents" =>
		[
		"margin"		=> "0",
		"padding"		=> "10px",
		"border-bottom"		=> "2px solid rgba(200,200,200,0.8)",
		"border-left"		=> "0",
		"text-overflow"		=> "ellipsis",
		"white-space"		=> "nowrap",
		"overflow"		=> "hidden",
		], ],
	
	[
	"css_tags" => ".unnested-list li:before",
	"css_contents" =>
		[
		"display"		=> "none",
		], ],


	/// Toggle classes


	[
	"css_tags" => ".hide",
	"css_contents" =>
		[
		"display"		=> "none",
		], ],
	
	[
	"css_tags" => ".show",
	"css_contents" =>
		[
		"display"		=> "inline-block",
		], ],

	
	];

output_css($style_array)

function output_css ($array) {
	
	// First, check 
	foreach ($array as $sub_array_temp):
	
		if (is_array($sub_array_temp['css_tags'])):
			$sub_array_temp['css_tags'] = implode(",", $sub_array_temp['css_tags']);
			endif;
		
		echo $sub_array_temp['css_tags']." {";
	
		if (isset($sub_array_temp['css_contents']['css_tags'])): 
			output_css($sub_array_temp['css_contents']);
			echo "} ";
			continue;
			endif;
	
		foreach ($sub_array_temp['css_contents'] as $property_temp => $value_temp):
			echo $property_temp .":". $value_temp .";"
			endforeach;

		echo "} ";
	
		endforeach;
	}

?>
