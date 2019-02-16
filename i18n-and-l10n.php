<?php
/*
==================================================
INTERNATIONALIZATION (i18n)
==================================================
https://developer.wordpress.org/plugins/internationalization/
https://developer.wordpress.org/themes/functionality/internationalization/
https://developer.wordpress.org/themes/functionality/localization/
https://developer.wordpress.org/plugins/internationalization/
https://developer.wordpress.org/plugins/internationalization/localization/

i18n means preparing a theme/plugin to be easily translated into other languages

DEFINE THE 'TEXT DOMAIN' ON THE THEME/PLUGIN INFO
Text Domain: text-domain
Domain Path: /languages                                     // Only required if folder name is not "languages"

LOADING TEXT DOMAINS
load_theme_textdomain( 'text-domain', get_template_directory().'/languages' );                          // For themes, triggered on the 'after_setup_theme' action
load_child_theme_textdomain( 'text-domain', get_stylesheet_directory().'/languages' );                  // Same but for child themes
load_plugin_textdomain( 'text-domain', false, dirname( plugin_basename( __FILE__ ) ).'/languages/' );   // For plugins, triggered on the 'plugins_loaded' action. Here __FILE__ is the main plugin file

BASIC FUNCTIONS
__( 'my string', 'text-domain' )                            // Returns a translated string
_e( 'my string', 'text-domain' )                            // Echoes a translated string
printf(                                                     // Use printf and sprintf for when the string will hold variable values
    __( 'number of seconds: %s', 'text-domain' ),
    $count
)

PLURALIZATION
_n( 'comment', 'comments', $count, 'text-domain' )          // Define a singular and a plural string, passing the value to calculate which one to use
_n_noop( 'comment', 'comments' )                            // Saves the singular/plural strings to be used later (or reused)
translate_nooped_plural( $n_noop, $count, 'text-domain' )   // Same as _n() but you pass a stored variable instead of the first two arguments

DISAMBIGUATION (CONTEXTS)
_x( 'my string', 'my context', 'text-domain' )
_ex( 'my string', 'my context', 'text-domain' )
_nx( 'comment', 'comments', $count, 'my context', 'text-domain' )
_nx_noop( 'comment', 'comments', 'my context' )

SHORTCUTS FOR ESCAPING CODE
esc_html__( $text, 'text-domain' )
esc_html_e( $text, 'text-domain' )
esc_html_x( $text, $context, 'text-domain' )
esc_attr__( $text, 'text-domain' )
esc_attr_e( $text, 'text-domain' )
esc_attr_x( $text, $context, 'text-domain' )

READABLE NUMBERS
number_format_i18n( $number, $decimals=0 )                                  // Converts a number into a user-readable string (adds commas, points, etc depending on the locale)
date_i18n( $date_format, $unix_timestamp=current_time, $use_gmt=false )     // Converts a timestamp into a user-readable date

ADDING INSTRUCTIONS FOR TRANSLATORS
If there's a php comment preceding a i18n function call, and the comment begins with 'translators:' then that comment will be used to provide context and instructions for translators
// translators: (example) '%s' is a placeholder for the total number of comments

SECURITY (WHEN WORKING WITH EXTERNAL TRANSLATORS)
- Check for spam or malicious words
- Check for malicious code, and always escape strings
- Use placeholders for urls and variables
- Compile your own .mo binaries



==================================================
LOCALIZATION (l10n)
==================================================
Localization refers to the process of actually translating the content

- .pot files contain the original strings as used on the theme/plugin files
- .po the new files with the translated strings for other languages, one file for each language
- .mo are the same as .po but converted to binary code to be easily consumed by machines, these are the ones actually used by the system
- For a list of useful tools, check out https://developer.wordpress.org/plugins/internationalization/localization/
- Helpful plugin: https://wordpress.org/plugins/loco-translate/

RECOMMENDED PLUGINS
Polylang            https://en-au.wordpress.org/plugins/polylang/
Loco Translate      https://en-au.wordpress.org/plugins/loco-translate/
