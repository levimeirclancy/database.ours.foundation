<? if (empty($background_color)): $background_color = [255,255,255]; endif;
if (empty($font_color)): $font_color = [20,20,20]; endif;
if (empty($default_width)): $default_width = 850; endif;

function output_width($default, $difference=0) {
	return ($default + $difference) ."px";
	}

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
		"background"		=> output_rgba($background_color, 1),
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
		"text-decoration"	=> "none",
		"color"			=> output_rgba($font_color, 01),
		"white-space"		=> "break-spaces",
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
	
	[
	"css_tags" => 
		[
		".studies",
		],
	"css_contents" =>
		[
		"box-shadow"		=> "0 0 40px -5px rgba(30,30,30,0.15)",
		"background"		=> output_rgba($background_color, 1),
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
//		"background"		=> output_rgba($background_color, 1),
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
//		"background"		=> output_rgba($background_color, 1),
		"display"		=> "inline-block",
		"cursor"		=> "pointer",
		"text-align"		=> "center",
		"color"			=> output_rgba($font_color, 1),
		"font-weight"		=> "400",
		"font-family"		=> "Arial, Helvetica, 'Sans Serif'",
		], ],
	
//	[
//	"css_tags" => ".navigation-header-item::first-letter, .sidebar-back::first-letter",
//	"css_contents" =>
//		[
//		"vertical-align"	=> "top",
//		], ],
	
	[
	"css_tags" => 
		[
		"#entries-list-alphabetical .navigation-header-item",
		"#entries-list-hierarchical .navigation-header-item",
		],
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
		"background"		=> output_rgba($background_color, 1),
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
		"padding"		=> "3px 0",
		"margin"		=> "10px 0 0 0",
		], ],
	
//	[
//	"css_tags" => ".sidebar-back:before",
//	"css_contents" =>
//		[
//		"content"		=> "⤺",
//		"font-size"		=> "1.3em",
//		"margin"		=> "0 5px 0 0",
//		"display"		=> "inline-block",
//		], ],
	
	[
	"css_tags" => "amp-lightbox, amp-sidebar",
	"css_contents" =>
		[
		"margin"		=> "0",
		"padding"		=> "10px 20px 10px",
		"background"		=> output_rgba($background_color, 1),
		"text-align"		=> "left",
		"box-sizing"		=> "border-box",
		"position"		=> "relative",
		"white-space"		=> "normal",
		], ],
	
	[
	"css_tags" => 
		[
		"amp-sidebar ul",
		"amp-sidebar amp-list",
		"amp-sidebar label",
		"amp-sidebar input",
		"amp-sidebar .input-button-wrapper",
		],
	"css_contents" =>
		[
		"margin-left"		=> "auto",
		"margin-right"		=> "auto",
		], ],
	
	[
	"css_tags" => "amp-lightbox label, amp-lightbox input, amp-lightbox .input-button-wrapper",
	"css_contents" =>
		[
		"margin-left"		=> "0",
		"margin-right"		=> "0",
		], ],
	
	[
	"css_tags" => "amp-sidebar",
	"css_contents" =>
		[
		"max-width"		=> output_width($default_width,-200),
		], ],
	
	[
	"css_tags" => "#sidebar-search",
	"css_contents" =>
		[
		"width"			=> "auto",
		"max-width"		=> output_width($default_width-200),
		], ],
	
	[
	"css_tags" => "#sidebar-navigation, #sidebar-entry-info",
	"css_contents" =>
		[
		"width"			=> "auto",
		"max-width"		=> output_width($default_width,-200),
		], ],
	
		[
	"css_tags" => "#sidebar-entry-info .wrapper-list",
	"css_contents" =>
		[
		"max-width"		=> output_width($default_width/2),
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
		"max-width"		=> output_width($default_width/2),
		"margin"		=> "30px 50px",
		"padding"		=> "10px 60px",
		"border"		=> "0",
		"border-radius"		=> "100px",
		"background"		=> output_rgba($font_color, 1),
		"color"			=> output_rgba($background_color, 1),
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

	/// MISCELLANEOUS

//	[
//	"css_tags" => ".material-icons",
//	"css_contents" =>
//		[
//		"vertical-align"	=> "middle",
//		], ],
	
//	[
//	"css_tags" => ".fadeout",
//	"css_contents" =>
//		[ 
//		"opacity"		=> "0.25",
//		], ],
	
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
		"color"			=> output_rgba($font_color, 1),
		"width"			=> "auto",
		"max-width"		=> output_width($default_width), 
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
	"css_tags" => "body, article, amp-mathml",
	"css_contents" =>
		[
		"position"		=> "relative",
		"color"			=> output_rgba($font_color, 1),
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
		"background"		=> output_rgba($font_color, 1),
		"border"		=> "0",
		], ],
	
	[
	"css_tags" => "hr + hr",
	"css_contents" =>
		[
		"display"		=> "none",
		], ],
	
	[ // Text only
	"css_tags" => "article, summary, p, ul, ol, blockquote, table, dt, dd, th, td, details, summary",
	"css_contents" =>
		[
		"vertical-align"	=> "top",
		"font-weight"		=> "400",
		"text-align"		=> "left",
		"color"			=> output_rgba($font_color, 1),
		], ],
	
	[ // The organizational elements, except for tables
	"css_tags" => "p, ul, ol, blockquote, dl, dt, dd",
	"css_contents" =>
		[
		"margin"		=> "0",
		"border"		=> "0",
		"display"		=> "block",
		"clear"			=> "both",
		"position"		=> "relative",
		"width"			=> "auto",
		"max-width"		=> output_width($default_width), 
		], ],
	
	[
	"css_tags" => "p, details",
	"css_contents" =>
		[
		"padding"		=> "20px",
		], ],

	
	[
	"css_tags" => "dl",
	"css_contents" =>
		[
		"margin"		=> "20px",
		"padding"		=> "0 20px",
		"border"		=> "1px dotted ".output_rgba($font_color, 0.5),
		], ],
	
	[
	"css_tags" => "dd",
	"css_contents" =>
		[
		"border-bottom"		=> "1px dotted ".output_rgba($font_color, 0.5),
		], ],
	
	[
	"css_tags" => "blockquote",
	"css_contents" =>
		[
		"background"		=> output_rgba($background_color, 1),
		"border-width"		=> "2px",
		"border-style"		=> "dotted",
		"border-color"		=> output_rgba($font_color, 1),
		"border-radius"		=> "15px",
		"padding"		=> "20px",
		"margin"		=> "50px 20px",
		"max-width"		=> output_width($default_width,-150),
		], ],
	
	[
	"css_tags" => "blockquote cite",
	"css_contents" =>
		[
		"text-align"		=> "left",
		"display"		=> "block",
		"width"			=> "60%",
//		"max-width"		=> output_width($default_width),
		"color"			=> output_rgba($font_color, 1),
		"font-style"		=> "normal",
		"opacity"		=> "0.6",
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
		"background"		=> output_rgba($background_color, 1),
		"border"		=> "2px dotted ".output_rgba($font_color, 1),
		"color"			=> output_rgba($font_color, 1),
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
	"css_tags" => "summary p:first-child",
	"css_contents" =>
		[
		"margin-top"		=> "0",
		"padding-top"		=> "0",
		], ],

	[
	"css_tags" => "table",
	"css_contents" =>
		[
		"margin"		=> "20px",
		"display"		=> "table",
		"box-sizing"		=> "border-box",
		"table-layout"		=> "auto",
		"border-collapse"	=> "collapse",
		"overflow"		=> "auto",
		"border"		=> "0",
		"min-width"		=> output_width($default_width),
//		"border-radius"		=> "7px",
		], ],
	
	[
	"css_tags" => "table table",
	"css_contents" =>
		[
		"margin"		=> "20px 0",
		], ],
	
	[
	"css_tags" => "article tbody tr:nth-child(odd) td",
	"css_contents" =>
		[
//		"background"		=> output_rgba($font_color, 0),
		], ],
	
	[
	"css_tags" => "article tbody tr:nth-child(even) td",
	"css_contents" =>
		[
//		"background"		=> output_rgba($font_color, 0.02),
		], ],


	[
	"css_tags" => "article th, article td",
	"css_contents" =>
		[
		"padding"		=> "10px",
		"margin"		=> "0",
		"border"		=> "1px solid ".output_rgba($font_color, 1),
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
		"article th p + p",
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
//		"background"		=> output_rgba($background_color, 1),
		"border"		=> output_rgba($font_color, 1),
		"border-radius"		=> "100px",
		], ],
	
	[
	"css_tags" => ".entry-metadata-more",
	"css_contents" =>
		[
		"font-style"		=> "normal",
		"margin"		=> "8px 20px",
		"border"		=> "1px solid ".output_rgba($font_color, 1),
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
		"width"			=> "85%",
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
		"max-width"		=> output_width($default_width),
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
		"margin"		=> "5px 20px",
		"border-radius"		=> "10px",
		"color"			=> output_rgba($font_color, 0.7),
		"padding"		=> "15px",
		"max-width"		=> output_width($default_width),
		"background"		=> output_rgba($background_color, 1),
		"border"		=> "1px solid ".output_rgba($font_color, 1),
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
		"color"			=> output_rgba($font_color, 1),
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
		"color"			=> output_rgba($font_color, 1),
		"opacity"		=> "0.8",
		"padding"		=> "7px 16px",
		"border"		=> "1px solid ".output_rgba($font_color, 1),
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
		"max-width"		=> output_width($default_width,-200),
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
		"background"		=> output_rgba($font_color, 1),
		"color"			=> output_rgba($background_color, 1),
		"border"		=> "0 none rgba(255,255,255,0)",
		"outline"		=> "0 none rgba(255,255,255,0)",
		], ],
	
	[
	"css_tags" => "amp-selector span[disabled]",
	"css_contents" =>
		[
		"font-weight"		=> "400",
		"color"			=> output_rgba($font_color, 1),
		"opacity"		=> "0.6",
		], ],

	[
	"css_tags" => 
		[
		"#admin-page-form-snackbar",
		"#admin-page-form-save",
		"#sidebar-inputs-button",
		],
	"css_contents" =>
		[
		"display"		=> "inline-block",
		"position"		=> "fixed",
		"right"			=> "20px",
		"border-radius"		=> "100px",
		"vertical-align"	=> "middle",
		"cursor"		=> "pointer",
		"border"		=> "1px solid ".output_rgba($font_color, 0.35),
//		"box-shadow"		=> "3px 3px 20px -3px rgba(50,50,50,0.35)",
		], ],
	
	[
	"css_tags" => "#admin-page-form-snackbar",
	"css_contents" =>
		[
		"cursor"		=> "default",
		"font-size"		=> "85%",
		"font-family"		=> "Arial, Helvetica, 'San Serif'",
		"bottom"		=> "17px",
		"font-style"		=> "italic",
		"padding"		=> "7px 30px",
		"background"		=> "rgba(255,255,255,1)",
		"color"			=> output_rgba($font_color, 1),
		], ],
	
	[
	"css_tags" => "#admin-page-form-save",
	"css_contents" =>
		[
		"bottom"		=> "65px",
		"right"			=> "120px",
		"background"		=> output_rgba($font_color, 1),
		"color"			=> output_rgba($background_color, 1),
		"padding"		=> "7px 40px 8px",
		], ],
	
		[
	"css_tags" => "#sidebar-inputs-button",
	"css_contents" =>
		[
		"bottom"		=> "65px",
		"background"		=> output_rgba($background_color, 1),
		"color"			=> output_rgba($font_color, 1),
		"padding"		=> "7px 20px 8px",
		], ],
	
	
	[
	"css_tags" => "#sidebar-inputs ul",
	"css_contents" =>
		[
		"width"			=> output_width($default_width/3),
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
		"border"		=> "1px solid ".output_rgba($font_color, 1),
		"padding"		=> "2px 11px",
		"float"			=> "right",
		], ],
	
	[
	"css_tags" => "span.sidebar-inputs-show-button",
	"css_contents" =>
		[
		"border"		=> "1px solid ".output_rgba($background_color, 1),
		"padding"		=> "2px 0",
		], ],
	
	[
	"css_tags" => "span.sidebar-inputs-hide-button",
	"css_contents" =>
		[
		"border"		=> "1px solid ".output_rgba($font_color, 1),
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
	[
	"css_tags" => 
		[
		"wrapper-list",
		],
	"css_contents" =>
		[
		"display"		=> "table",
		"max-width"		=> output_width($default_width),
		], ],

	[
	"css_tags" => "ul, ol, amp-list",
	"css_contents" =>
		[
		"display"		=> "block",
		"position"		=> "relative",
		"text-align"		=> "left",
		"vertical-align"	=> "top",
		"clear"			=> "both",
		"width"			=> "auto",
		"max-width"		=> output_width($default_width),
		"margin"		=> "20px 20px 0 20px",
		"padding"		=> "0",
		"list-style"		=> "none",
//		"list-style-position"	=> "inside",
		"line-height"		=> "1.4",
//		"border-left"		=> "1px solid ".output_rgba($font_color, 0.2),
		"box-sizing"		=> "border-box",
		"counter-reset"		=> "list-counter",
		"border-width"		=> "0 0 1px 0",
		"border-style"		=> "solid",
		"border-color"		=> output_rgba($font_color, 0.35),		
		], ],
	
	[
	"css_tags" =>
		[
		"ul + ul", "ul + ol", "ul + amp-list",
		"ol + ul", "ol + ol", "ol + amp-list",
		"amp-list + ul", "amp-list + ol", "amp-list + amp-list",
		],
	"css_contents" =>
		[
//		"display"		=> "block",
		"margin-top"		=> "0",
		"border-width"		=> "0",
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
		"border-width"		=> "0",
		], ],

	[
	"css_tags" => 
		[
		"ul ul", "ul ol", "ul amp-list",
		"ol ul", "ol ol", "ol amp-list",
		"amp-list ul", "amp-list ol", "amp-list amp-list",
		],
	"css_contents" =>
		[
		"border-width"		=> "0",
		"display"		=> "block",
		"width"			=> "auto",
		"max-width"		=> "none",
		"margin"		=> "0 0 0 25px",
		"padding"		=> "0",
		], ],
	

	[
	"css_tags" => "li",
	"css_contents" =>
		[
		"margin"		=> "0",
		"padding"		=> "7px 0",
		"border-width"		=> "1px 0 0",
		"border-style"		=> "solid",
		"border-color"		=> output_rgba($font_color, 0.35),
		"counter-increment"	=> "list-counter",
		"list-style-type"	=> "none",
		], ],
	
	[
	"css_tags" => "li:first-child",
	"css_contents" =>
		[
		"border-width"		=> "1px 0 0",
//		"border-top-left-radius"	=> "8px",
//		"border-top-right-radius"	=> "8px",
		], ],
	
	[
	"css_tags" => 
		[
		"li:last-child",
		],
	"css_contents" =>
		[
		"border-width"		=> "1px 0 0 0",
//		"padding-bottom"	=> "0",
//		"margin-bottom"		=> "0",
//		"border-bottom"		=> "1px solid ".output_rgba($font_color, 0.6),
//		"border-bottom-left-radius"	=> "8px",
//		"border-bottom-right-radius"	=> "8px",
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
//		"border"		=> "0",
		"border-style"		=> "dotted",
		"margin-top"		=> "8px",
//		"padding-bottom"	=> "0",
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
		"padding-bottom"	=> "0",
//		"margin-top"		=> "0",
//		"padding-bottom"	=> "0",
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
//		"margin"		=> "0 10px 0 0",
		"padding"		=> "2px 3px 3px 5px",
		"margin"		=> "0 0 6px 0",
//		"background"		=> output_rgba($background_color, 1),
		"border-bottom"		=> "1px dotted ".output_rgba($font_color, 0.35),
		"color"			=> output_rgba($font_color, 0.5),
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
	
	[
	"css_tags" => 
		[
		"ul li::before",
		".unordered-list li::before",
		],
	"css_contents" =>
		[
//		"font-weight"		=> "700",
		"display"		=> "none",
//		"text-align"		=> "left",
//		"font-size"		=> "70%",
//		"margin"		=> "0 10px 0 0",
		"padding"		=> "0",
		"margin"		=> "0",
//		"background"		=> output_rgba($background_color, 1),
		"border-bottom"		=> "0",
//		"color"			=> output_rgba($font_color, 0.5),
		"content"		=> "",
		], ],

	[
	"css_tags" => ".navigation-list",
	"css_contents" =>
		[
		"font-family"		=> "Arial, Helvetica, 'Sans Serif'",
		"font-size"		=> "0.85em",
		"width"			=> output_width($default_width/2),
		], ],
	
	[
	"css_tags" => "#entries-list-hierarchical, #entries-list-alphabetical, .home-list",
	"css_contents" =>
		[
		"display"		=> "block",
		"width"			=> "auto",
		"max-width"		=> output_width($default_width,-150),
		"font-size"		=> ".9em",
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
		"font-size"		=> "1em",
		], ],

	[
	"css_tags" => 
		[
		".navigation-list li",
		],
	"css_contents" =>
		[
//		"padding"		=> "10px 0",
		"text-overflow"		=> "ellipsis",
		"white-space"		=> "nowrap",
		"overflow"		=> "hidden",
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
	"css_tags" => "@media only screen and (max-width: ".output_width($default_width,40).")",
	"css_contents" => 
		[

		[
		"css_tags" => [
			"table",
			],
		"css_contents" =>
			[
			"display"	=> "block",
			"min-width"	=> "0",
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

function output_rgba($rgba_array, $opacity) {
	$output_rgba = "rgba(";
	$output_rgba .= implode(",",$rgba_array);
	$output_rgba .= ",";
	$output_rgba .= $opacity;
	$output_rgba .= ")";
	return $output_rgba;
	}

function output_css ($array) {
	
	global $background_color;
	global $font_color;
	
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
