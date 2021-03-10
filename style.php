<? // Site CSS parameters
$style_array = [
	
	[
	"css_tags" => 
		[
		"body",
		],
	"css_contents" =>
		[
		"text-align" 		=> "left",
		"font-family"		=> "Times, Serif",
		"font-size"		=> "17px",
		"line-height"		=> "1.3",
		"background"		=> output_rgba($site_info['colors']['background'], 1),
		"margin"		=> "0",
		"padding"		=> "0",
		], ],

	[ // In general, we do not want any special formatting for URLs
	"css_tags" =>
		[
		"a",
		"a:link",
		"a:visited",
		"a:hover",
		],
	"css_contents" =>
		[
		"text-overflow"		=> "ellipsis",
		"white-space"		=> "pre-line",
		"overflow"		=> "hidden",
		"text-decoration"	=> "none",
		"color"			=> output_rgba($site_info['colors']['offset'], 1),
		], ],
	
	[ // But in the articles, we want an underline
	"css_tags" =>
		[
		"article a",
		],
	"css_contents" =>
		[
		"text-decoration"	=> "underline",
		], ],
	
	
	[ // Emphasis is italic
	"css_tags" =>
		[
		"em",
		".emphasis",
		],
	"css_contents" =>
		[
		"font-style"		=> "italic",
		], ],
	
	[ // "Strong" is small caps
	"css_tags" =>
		[
		"strong",
		".strong",
		],
	"css_contents" =>
		[
		"font-style"		=> "normal",
		"text-transform"	=> "uppercase",
		"font-weight"		=> "400",
		"font-size"		=> ".9em",
		], ],	
	
	[
	"css_tags" => 
		[
		".studies",
		],
	"css_contents" =>
		[
		"box-shadow"		=> "0 0 40px -5px rgba(30,30,30,0.15)",
		"background"		=> output_rgba($site_info['colors']['background'], 1),
		"padding"		=> "50px 0",
		"margin"		=> "50px 0",
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
//		"background"		=> output_rgba($site_info['colors']['background'], 1),
		"padding"		=> "10px 0",
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
//		"background"		=> output_rgba($site_info['colors']['background'], 1),
		"display"		=> "inline-block",
		"cursor"		=> "pointer",
		"text-align"		=> "center",
		"color"			=> output_rgba($site_info['colors']['font'], 1),
		"font-weight"		=> "400",
		"font-family"		=> "Arial, Helvetica, 'Sans Serif'",
		], ],
	
	
	[
	"css_tags" => ".sidebar-back",
	"css_contents" =>
		[
		"display"		=> "block",
		"cursor"		=> "pointer",
		"text-align"		=> "right",
		"padding"		=> "3px 0",
		"margin"		=> "10px 20px 0",
		], ],
			
	[
	"css_tags" => "div.sidebar-navigation-item",
	"css_contents" =>
		[
		"display"		=> "block",
		], ],

	
	[
	"css_tags" => 
		[
		"#login-popover-submit",
//		"#logout-popover-submit",
		"#new-popover-submit",
		"#delete-popover-submit",
		"#search-submit",
		],
	"css_contents" =>
		[
		"display"		=> "table",
		"max-width"		=> output_width($site_info['dimensions']['width']/2),
		"margin"		=> "30px 50px",
		"padding"		=> "10px 60px",
		"border"		=> "0",
		"border-radius"		=> "100px",
		"background"		=> output_rgba($site_info['colors']['font'], 1),
		"color"			=> output_rgba($site_info['colors']['background'], 1),
		"box-shadow"		=> "none",
		"box-sizing"		=> "border-box",
		"opacity"		=> "0.8",
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
		"color"			=> output_rgba($site_info['colors']['font'], 1),
		"width"			=> "auto",
		"max-width"		=> output_width($site_info['dimensions']['width']), 
		"border"		=> "0",
		"display"		=> "block",
		"clear"			=> "both",
		"vertical-align"	=> "top",
		"padding"		=> "0",
		"text-align"		=> "left",
		"word-break"		=> "normal",
		"font-family"		=> "Times, Serif",
		], ],
	
//	[
//	"css_tags" => "h1 span",
//	"css_contents" =>
//		[
//		"display"		=> "block",
//		"margin"		=> "10px auto",
//		], ],
	
	[
	"css_tags" => "h1",
	"css_contents" =>
		[
		"margin"		=> "100px 20px 40px",
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
	"css_tags" => "body, article, amp-mathml",
	"css_contents" =>
		[
		"position"		=> "relative",
		"color"			=> output_rgba($site_info['colors']['font'], 1),
		], ],
	
	[
	"css_tags" => "span, div",
	"css_contents" =>
		[
		"background"		=> "inherit",
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
		"background"		=> output_rgba($site_info['colors']['font'], 1),
		"border"		=> "0",
		], ],
	
	[
	"css_tags" => "hr + hr",
	"css_contents" =>
		[
		"display"		=> "none",
		], ],
	
	[ // Text only
	"css_tags" => [
		"p",
		],
	"css_contents" =>
		[
//		"margin"		=> "10px 20px",
		"box-sizing"		=> "border-box",
		"vertical-align"	=> "top",
		"font-weight"		=> "400",
		"text-align"		=> "left",
		"clear"			=> "both",
		"position"		=> "relative",
		"color"			=> output_rgba($site_info['colors']['font'], 1),
		"border"		=> "0",
		"wudth"			=> "auto",
		"overflow"		=> "hidden",
		"text-overflow"		=> "ellipsis",
		"min-width"		=> "0px",
		"max-width"		=> output_width($site_info['dimensions']['width']),
		], ],
	
	[ // Text only
	"css_tags" => [
		"p",
		"table",
		"blockquote",
		"h2",
		"h3",
		"h4",
		"h5",
		"h6",
		],
	"css_contents" =>
		[
		"margin"		=> "20px",
		], ],
	
	[ // Add extra margins
	"css_tags" => [
		"* + table",
		"* + blockquote",
		"* + h2",
		"* + h3",
		"* + h4",
		"* + h5",
		"* + h6",
		"table + *",
		"blockquote + *",
		"h2 + *",
		"h3 + *",
		"h4 + *",
		"h5 + *",
		"h6 + *",
		],
	"css_contents" =>
		[
		"margin-top"		=> "40px",
		], ],

	
	[
	"css_tags" => "dl",
	"css_contents" =>
		[
		"padding"		=> "0 20px",
		"border"		=> "1px dotted ".output_rgba($site_info['colors']['font'], 0.5),
		], ],
	
	[
	"css_tags" => "dd",
	"css_contents" =>
		[
		"border-bottom"		=> "1px dotted ".output_rgba($site_info['colors']['font'], 0.5),
		], ],
	
	[
	"css_tags" => "blockquote",
	"css_contents" =>
		[
		"box-sizing"		=> "border-box",
		"vertical-align"	=> "top",
		"clear"			=> "both",
		"position"		=> "relative",
		"background"		=> output_rgba($site_info['colors']['background'], 1),
		"border-width"		=> "1px",
		"border-style"		=> "solid",
		"border-color"		=> output_rgba($site_info['colors']['font'], 0.8),
		"border-radius"		=> "15px",
		"padding"		=> "15px 0 0",
		"max-width"		=> output_width($site_info['dimensions']['width'],-150),
		], ],
		
	[
	"css_tags" => "cite",
	"css_contents" =>
		[
		"color"			=> output_rgba($site_info['colors']['font'], 1),
		"font-style"		=> "normal",
		"opacity"		=> "0.6",
		], ],
	
	[
	"css_tags" => "cite:before",
	"css_contents" =>
		[
		"content"		=> "'('",
		], ],

	[
	"css_tags" => "cite:after",
	"css_contents" =>
		[
		"content"		=> "')'",
		], ],
	
	[
	"css_tags" => "blockquote blockquote",
	"css_contents" =>
		[
//		"margin"		=> "20px",
		], ],
	
	[
	"css_tags" => "blockquote:before",
	"css_contents" =>
		[
		"content"		=> "'“     ”'",
		"font-weight"		=> "700",
		"box-sizing"		=> "border-box",
		"font-size"		=> "40px",
		"white-space"		=> "pre",
		"line-height"		=> "30px",
		"vertical-align"	=> "middle",
		"text-align"		=> "center",
		"font-family"		=> "Times",
		"background"		=> output_rgba($site_info['colors']['background'], 1),
		"border-bottom"		=> "1px solid ".output_rgba($site_info['colors']['font'], 0.6),
		"color"			=> output_rgba($site_info['colors']['font'], 1),
		"padding"		=> "0 0 0",
		"display"		=> "block",
		"margin"		=> "0 20px 16px",
		], ],

	[
	"css_tags" => "table",
	"css_contents" =>
		[
		"box-sizing"		=> "border-box",
		"table-layout"		=> "auto",
		"border-collapse"	=> "collapse",
		"border"		=> "0",
		"min-width"		=> output_width($site_info['dimensions']['width']),
		], ],	

	[
	"css_tags" => [
		"th",
		"td",
		],
	"css_contents" =>
		[
		"box-sizing"		=> "border-box",
//		"padding"		=> "10px 0",
		"margin"		=> "0",
		"vertical-align"	=> "top",
		"border"		=> "1px solid ".output_rgba($site_info['colors']['font'], 1),
		], ],
	
	[
	"css_tags" => "th",
	"css_contents" =>
		[
		"font-style"		=> "italic",
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
//		"background"		=> output_rgba($site_info['colors']['background'], 1),
		"border"		=> output_rgba($site_info['colors']['font'], 1),
		"border-radius"		=> "100px",
		], ],
	
	[
	"css_tags" => ".entry-metadata-more",
	"css_contents" =>
		[
		"font-style"		=> "normal",
		"margin"		=> "8px 20px",
		"border"		=> "1px solid ".output_rgba($site_info['colors']['font'], 1),
		"cursor"		=> "pointer",
		], ],

	/// EDIT

	[
	"css_tags" => 
		[
		"label",
		"input",
		"textarea",
		"amp-selector",
		".input-button-wrapper",
		],
	"css_contents" =>
		[
		"display"		=> "block",
		"margin"		=> "5px 20px",
		"padding"		=> "8px",
		"width"			=> output_width($site_info['dimensions']['width']),
		"max-width"		=> "91%",
		"text-align"		=> "left",
		"border"		=> "0",
		"border-radius"		=> "6px",
		"box-sizing"		=> "border-box",
		"font-size"		=> "0.9em",
		"line-height"		=> "inherit",
		"outline"		=> "0 none rgba(255,255,255,0)",
		], ],
	
	[
	"css_tags" =>
		[
		"input + .input-button-wrapper",
		"textarea + .input-button-wrapper",
		"amp-selector + .input-button-wrapper",
		],
	"css_contents" =>
		[
		"text-align"		=> "right",
		], ],
	
	[
	"css_tags" => 
		[
		"input",
		"textarea",
		"amp-selector",
		],
	"css_contents" =>
		[
		"font-family"		=> "Arial, Helvetica, 'Sans Serif'",
		"border-radius"		=> "10px",
		"color"			=> output_rgba($site_info['colors']['font'], 0.7),
		"padding"		=> "15px",
		"background"		=> output_rgba($site_info['colors']['background'], 1),
		"border"		=> "1px solid ".output_rgba($site_info['colors']['font'], 1),
		"box-shadow"		=> "3px 12px 15px -9px rgba(50,50,50,0.1)",
		], ],
	
	[
	"css_tags" => "input[type=\"date\"]",
	"css_contents" =>
		[
		"width"			=> "300px",
		], ],
	
	[
	"css_tags" => "label",
	"css_contents" =>
		[
		"font-style"		=> "italic",
		"color"			=> output_rgba($site_info['colors']['font'], 1),
		"padding"		=> "40px 16px 5px",
		"opacity"		=> "0.8",
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
		"color"			=> output_rgba($site_info['colors']['font'], 1),
		"opacity"		=> "0.8",
		"padding"		=> "7px 16px",
		"border"		=> "1px solid ".output_rgba($site_info['colors']['font'], 1),
		"border-radius"		=> "100px",
		"display"		=> "inline-block",
		], ],
	
	[
	"css_tags" => "textarea",
	"css_contents" =>
		[
		"height"		=> "600px",
		"max-height"		=> "80%",
		"overflow-y"		=> "scroll",
		"resize"		=> "none",
		], ],
	
	[
	"css_tags" => ".textarea-small",
	"css_contents" =>
		[
		"height"		=> "250px",
		"max-width"		=> output_width($site_info['dimensions']['width'],-200),
		"max-height"		=> "80%",
		"overflow-y"		=> "scroll",
		"resize"		=> "none",
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
	"css_tags" => 
		[
		"amp-selector span[selected]",
		"amp-selector span[aria-selected=\"true\"]",
		],
	"css_contents" =>
		[
		"background"		=> output_rgba($site_info['colors']['font'], 1),
		"color"			=> output_rgba($site_info['colors']['background'], 1),
		"border"		=> "0 none rgba(255,255,255,0)",
		"outline"		=> "0 none rgba(255,255,255,0)",
		], ],
	
	[
	"css_tags" => "amp-selector span[disabled]",
	"css_contents" =>
		[
		"font-weight"		=> "400",
		"color"			=> output_rgba($site_info['colors']['font'], 1),
		"opacity"		=> "0.6",
		], ],

	[
	"css_tags" => 
		[
		"#admin-page-form-snackbar",
		"#admin-page-form-save",
		"#sidebar-inputs-button",
		".navigation-list-button",
		],
	"css_contents" =>
		[
		"z-index"		=> "100",
		"display"		=> "inline-block",
		"position"		=> "fixed",
		"right"			=> "20px",
		"border-radius"		=> "100px",
		"vertical-align"	=> "middle",
		"cursor"		=> "pointer",
		"border"		=> "1px solid ".output_rgba($site_info['colors']['font'], 0.35),
//		"box-shadow"		=> "3px 3px 20px -3px rgba(50,50,50,0.35)",
		], ],
	
	[
	"css_tags" =>
		[
		".navigation-list-button",
		"#admin-page-form-snackbar",
		],
	"css_contents" =>
		[
		"font-size"		=> "85%",
		"font-family"		=> "Arial, Helvetica, 'San Serif'",
		"bottom"		=> "17px",
		"padding"		=> "7px 30px",
		"background"		=> output_rgba($site_info['colors']['background'], 1),
		"color"			=> output_rgba($site_info['colors']['font'], 1),
		], ],
	
	[
	"css_tags" =>
		[
		".navigation-list-button",
		],
	"css_contents" =>
		[
		"cursor"		=> "pointer",
		], ],
	
	[
	"css_tags" =>
		[
		"#admin-page-form-snackbar",
		],
	"css_contents" =>
		[
		"cursor"		=> "default",
		"font-style"		=> "italic",
		], ],
	
	[
	"css_tags" => "#admin-page-form-save",
	"css_contents" =>
		[
		"bottom"		=> "65px",
		"right"			=> "120px",
		"background"		=> output_rgba($site_info['colors']['font'], 1),
		"color"			=> output_rgba($site_info['colors']['background'], 1),
		"padding"		=> "7px 40px 8px",
		], ],
	
		[
	"css_tags" => "#sidebar-inputs-button",
	"css_contents" =>
		[
		"bottom"		=> "65px",
		"background"		=> output_rgba($site_info['colors']['background'], 1),
		"color"			=> output_rgba($site_info['colors']['font'], 1),
		"padding"		=> "7px 20px 8px",
		], ],
	
	
	[
	"css_tags" => "#sidebar-inputs",
	"css_contents" =>
		[
		"width"			=> output_width($site_info['dimensions']['width']*.4),
		], ],
		
	[
	"css_tags" => "#sidebar-inputs span",
	"css_contents" =>
		[
		"box-sizing"		=> "border-box",
		"border-radius"		=> "100px",
		"display"		=> "inline-block",
		"margin"		=> "0",
//		"float"			=> "right",
		"cursor"		=> "pointer",
		], ],
	
	[
	"css_tags" => "span.sidebar-inputs-toggle-button",
	"css_contents" =>
		[
		"border"		=> "1px solid ".output_rgba($site_info['colors']['font'], 1),
		"padding"		=> "2px 11px",
		"float"			=> "right",
		], ],
	
	[
	"css_tags" => "span.sidebar-inputs-show-button",
	"css_contents" =>
		[
		"border"		=> "1px solid ".output_rgba($site_info['colors']['background'], 1),
		"padding"		=> "2px 0",
		], ],
	
	[
	"css_tags" => "span.sidebar-inputs-hide-button",
	"css_contents" =>
		[
		"border"		=> "1px solid ".output_rgba($site_info['colors']['font'], 1),
		"padding"		=> "2px 11px",
		], ],

	[
	"css_tags" => "#footer-formula",
	"css_contents" =>
		[
		"display"		=> "none",
		"text-direction"	=> "rtl",
		"margin"		=> "20px 0 50px",
		], ],
	

	/// Lists ... ul, ol, amp-list
	
	[ // This simply ensures it has the correct alignment and width...
	"css_tags" => 
		[
		".wrapper-list",
		],
	"css_contents" =>
		[
		"display"		=> "block",
		"max-width"		=> output_width($site_info['dimensions']['width']),
		"position"		=> "relative",
		], ],

	[ // This is the basic design
	"css_tags" =>
		[
		"ul",
		"ol",
		"amp-list",
		],
	"css_contents" =>
		[
		"display"		=> "block",
		"position"		=> "relative",
		"text-align"		=> "left",
		"vertical-align"	=> "top",
		"clear"			=> "both",
//		"width"			=> "auto",
//		"max-width"		=> output_width($site_info['dimensions']['width']),
		"margin"		=> "50px 20px",
		"padding"		=> "0 0 7px 0",
		"list-style"		=> "none",
//		"list-style-position"	=> "inside",
		"line-height"		=> "1.4",
//		"border-left"		=> "1px solid ".output_rgba($site_info['colors']['font'], 0.2),
		"box-sizing"		=> "border-box",
		"counter-reset"		=> "list-counter",
		"border-width"		=> "0 0 1px 0",
		"border-style"		=> "solid",
		"border-color"		=> output_rgba($site_info['colors']['font'], 0.35),		
		], ],

	[ // A table inside a table
	"css_tags" => 
		[
		"ul ul", "ul ol", "ul amp-list",
		"ol ul", "ol ol", "ol amp-list",
		"amp-list ul", "amp-list ol", "amp-list amp-list",
		],
	"css_contents" =>
		[
		"border-width"		=> "0",
//		"display"		=> "block",
//		"width"			=> "auto",
//		"max-width"		=> "none",
		"margin"		=> "7px 0 0 25px",
		"padding"		=> "0",
		], ],

	[
	"css_tags" => "li",
	"css_contents" =>
		[
		"margin"		=> "7px 0 0",
		"padding"		=> "7px 0 0",
		"border-width"		=> "1px 0 0",
		"border-style"		=> "solid",
		"border-color"		=> output_rgba($site_info['colors']['font'], 0.35),
		"counter-increment"	=> "list-counter",
		"list-style-type"	=> "none",
		"width"			=> "auto",
		"display"		=> "block",
		], ],

	[
	"css_tags" => 
		[
		"ul ul li:first-child",
		"ul ol li:first-child",
		"ul amp-list li:first-child",
		"ol ol li:first-child",
		"ol ul li:first-child",
		"ol amp-list li:first-child",
		"amp-list amp-list li:first-child",
		"amp-list ul li:first-child",
		"amp-list ol li:first-child",
		],
	"css_contents" =>
		[
		"margin-left"		=> "-25px",
		"padding-left"		=> "25px",
		"border-style"		=> "dotted",
		], ],
	
		[
	"css_tags" => 
		[
		"ul ul li:last-child",
		"ul ol li:last-child",
		"ul amp-list li:last-child",
		"ol ol li:last-child",
		"ol ul li:last-child",
		"ol amp-list li:last-child",
		"amp-list amp-list li:last-child",
		"amp-list ul li:last-child",
		"amp-list ol li:last-child",
		],
	"css_contents" =>
		[
		], ],
	
	[ // If we have back-to-back tables, especially inside of another tables
	"css_tags" =>
		[
		"ul + ul", "ul + ol", "ul + amp-list",
		"ol + ul", "ol + ol", "ol + amp-list",
		"amp-list + ul", "amp-list + ol", "amp-list + amp-list",
		],
	"css_contents" =>
		[
//		"display"		=> "block",
		"margin-top"		=> "7px",
		"padding-top"		=> "0",
		"border-width"		=> "1px 0 0 0",
		], ],
	
	[
	"css_tags" =>
		[
		"ul + ul li:first-child", "ul + ol li:first-child", "ul + amp-list li:first-child",
		"ol + ul li:first-child", "ol + ol li:first-child", "ol + amp-list li:first-child",
		"amp-list + ul li:first-child", "amp-list + ol li:first-child", "amp-list + amp-list li:first-child",
		],
	"css_contents" =>
		[
		"padding-top"		=> "0",
		"border-width"		=> "0",
		], ],
	
	[
	"css_tags" => 
		[
		"ol li",
		".ordered-list li",
		],
	"css_contents" =>
		[
		"padding-top"		=> "3px",
		], ],
	
	[
	"css_tags" => 
		[
		"ol li::before",
		".ordered-list li::before",
		],
	"css_contents" =>
		[
		"font-weight"		=> "700",
		"display"		=> "block",
		"text-align"		=> "left",
		"font-size"		=> "70%",
		"padding"		=> "0 3px 3px 5px",
		"margin"		=> "0 0 6px 0",
		"border-bottom"		=> "1px dotted ".output_rgba($site_info['colors']['font'], 0.35),
		"color"			=> output_rgba($site_info['colors']['font'], 0.5),
		"content"		=> "counter(list-counter, decimal)",
		], ],
	
	[
	"css_tags" => 
		[
		"ol ol li::before",
		"ol .ordered-list li::before",
		".ordered-list .ordered-list li::before",
		".ordered-list ol li::before",
		],
	"css_contents" =>
		[
		"content"		=> "counter(list-counter, lower-roman)",
		], ],

	[
	"css_tags" =>
		[
		"ol ol ol li::before",
		"ol ol .ordered-list li::before",
		"ol .ordered-list ol li::before",
		".ordered-list ol ol li::before",
		"ol .ordered-list .ordered-list li::before",
		".ordered-list .ordered-list ol li::before",
		".ordered-list .ordered-list .ordered-list li::before",
		],
	"css_contents" =>
		[
		"content"		=> "counter(list-counter, lower-alpha)",
		], ],
	
	[ // Just to confirm that there is nothing for an unordered list...
	"css_tags" => 
		[
		"ul li::before",
		".unordered-list li::before",
		],
	"css_contents" =>
		[
		"display"		=> "none",
		"padding"		=> "0",
		"margin"		=> "0",
		"border-bottom"		=> "0",
		"content"		=> "",
		], ],
	

	[ // Just to confirm that there is nothing for an unordered list...
	"css_tags" => 
		[
		"li p",
		],
	"css_contents" =>
		[
		"margin"		=> "0",
		], ],
	
	[ // Just to confirm that there is nothing for an unordered list...
	"css_tags" => 
		[
		"li * + p",
		],
	"css_contents" =>
		[
		"margin-top"		=> "10px",
		], ],

	
	[ // These are just a few basic formatting specs for some significant tables...
	"css_tags" => ".navigation-list, .navigation-list > .wrapper-list",
	"css_contents" =>
		[
		"display"		=> "block",
		"margin"		=> "15px 0 0 0",
		"max-width"		=> output_width($site_info['dimensions']['width']*.7),
		], ],

	[
	"css_tags" => 
		[
		".navigation-list ul",
		".navigation-list ol",
		".navigation-list amp-list",
		],
	"css_contents" =>
		[
		"margin-top"		=> "0",
		], ],
	
	[
	"css_tags" => 
		[
		".navigation-list p",
		],
	"css_contents" =>
		[
		"padding"		=> "1px 0 0 0",
		"font-family"		=> "Arial, Helvetica, 'Sans Serif'",
		"font-size"		=> "0.85em",
		], ],

	[
	"css_tags" => 
		[
		".navigation-list p",
		".navigation-list a",
		],
	"css_contents" =>
		[
		"text-overflow"		=> "ellipsis",
		"white-space"		=> "nowrap",
		"overflow"		=> "hidden",
		], ],	
	

	[
	"css_tags" => 
		[
		".amp-img-large-wrapper",
		],
	"css_contents" =>
		[
		"display"		=> "block",
		"position"		=> "relative",
		"width"			=> output_width($site_info['dimensions']['width']*.7),
		"max-width"		=> "100%",
		"height"		=> output_width($site_info['dimensions']['width']*.7),
		"max-height"		=> "70%",
//		"margin"		=> "20px",
		"box-sizing"		=> "border-box",
		"text-align"		=> "left",
		], ],
	
	[
	"css_tags" => 
		[
		"amp-img",
		],
	"css_contents" =>
		[
		"display"		=> "inline-block",
		], ],
	
	[
	"css_tags" => 
		[
		"amp-img.amp-img-large img",
		],
	"css_contents" =>
		[
		"object-fit"		=> "contain",
		], ],
			

		[
	"css_tags" => "amp-lightbox, amp-sidebar",
	"css_contents" =>
		[
		"margin"		=> "0",
		"background"		=> output_rgba($site_info['colors']['background'], 1),
		"text-align"		=> "left",
		"box-sizing"		=> "border-box",
		"position"		=> "relative",
//		"white-space"		=> "normal",
//		"display"		=> "block",
		"padding"		=> "10px 0 20px",
		], ],

	[
	"css_tags" => "amp-lightbox",
	"css_contents" =>
		[
		"width"			=> "auto",
		], ],
	
	[
	"css_tags" => "amp-sidebar",
	"css_contents" =>
		[
		"max-width"		=> "90%",
		"width"			=> output_width($site_info['dimensions']['width']*.55),
		], ],
	
	[
	"css_tags" => "amp-sidebar label",
	"css_contents" =>
		[
		"padding-top"		=> "10px",
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
	
	[
	"css_tags" => ".no-border",
	"css_contents" =>
		[
		"border-color"		=> "rgba(255,255,255,0)",
		], ],	
	
	[
	"css_tags" => "@media only screen and (min-width: 650px)",
	"css_contents" =>
		[
			
		[
		"css_tags" => ".categories-item-button",
		"css_contents" =>
			[
			"display"	=> "inline-block",
			], ],
			
		], ],
	
	[
	"css_tags" => "@media only screen and (max-width: ".output_width($site_info['dimensions']['width'],40).")",
	"css_contents" => 
		[

		[
		"css_tags" => [
			"table",
			],
		"css_contents" =>
			[
			"display"	=> "block",
			"min-width"	=> "0px",
			"width"		=> "auto",
			], ],
			
		[
		"css_tags" => [
			"th",
			"td",
			],
		"css_contents" =>
			[
			"display"	=> "block",
			"width"		=> "100%",
			], ],

		[
		"css_tags" =>
			[
			"th + th",
			"td + td",
			"th + td",
			"td + th",
			],
		"css_contents" =>
			[
//			"border-left"	=> "none",
			"border-top"	=> "0",
			], ],
		], ],
	
	[
	"css_tags" => "@media print",
	"css_contents" =>
		[ 
			
		[
		"css_tags" => "#navigation-header, #footer-formula",
		"css_contents" =>
			[
			"display"	=> "none",
			], ],
			
		], ],
	
	];

function output_width($default, $difference=0) {
	return round(($default + $difference),0) ."px";
	}

function output_rgba($rgba_array, $opacity) {
	$output_rgba = "rgba(";
	$output_rgba .= implode(",",$rgba_array);
	$output_rgba .= ",";
	$output_rgba .= $opacity;
	$output_rgba .= ")";
	return $output_rgba;
	}

function output_css ($array) {
	
	global $site_info;
	
//	if (isset($array['css_tags'])): $array = [ $array ]; endif;
	
	// First, check 
	foreach ($array as $sub_array_temp):
	
		if (is_array($sub_array_temp['css_tags'])):
			$sub_array_temp['css_tags'] = implode(",", $sub_array_temp['css_tags']);
			endif;
		
		echo trim($sub_array_temp['css_tags']) ." {";
	
		$echo_temp = null;
	
		foreach ($sub_array_temp['css_contents'] as $property_temp => $value_temp):
	
			if (isset($value_temp['css_tags'])):
				output_css($sub_array_temp['css_contents']);
				$echo_temp = null;
				break; endif;
	
			$echo_temp .= trim($property_temp) .":". trim($value_temp) ."; ";
	
			endforeach;
	
		echo $echo_temp;

		echo "} ";
	
		endforeach;
	}

output_css($style_array);

?>
