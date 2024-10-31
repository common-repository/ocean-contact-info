<?php
/**
 * Plugin Name:         Ocean Contact Info
 * Plugin URI:          https://studio7ph.art/portfolio/ocean-contact-info/
 * Description:         Add some contact information about your company or product in after header inner
 * Version:             1.0.2
 * Author:              Studio 7pH
 * Author URI:          https://studio7ph.art/
 * Requires at least:   5.3
 * Tested up to:        6.1
 *
 * Text Domain: ocean-contact-info
 *
 * @package Ocean_Contact_Info
 * @category Core
 * @author Studio 7pH
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the main instance of Ocean_Contact_Info to prevent the need to use globals.
 *
 * @since  1.0.2
 * @return object Ocean_Contact_Info
 */
function Ocean_Contact_Info() {
	return Ocean_Contact_Info::instance();
} // End Ocean_Contact_Info()

Ocean_Contact_Info();

/**
 * Main Ocean_Contact_Info Class
 *
 * @class Ocean_Contact_Info
 * @version 1.0.2
 * @since 1.0.2
 * @package Ocean_Contact_Info
 */
final class Ocean_Contact_Info {
	/**
	 * Ocean_Contact_Info The single instance of Ocean_Contact_Info.
	 *
	 * @var     object
	 * @access  private
	 * @since   1.0.2
	 */
	private static $_instance = null;

	/**
	 * The token.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.2
	 */
	public $token;

	/**
	 * The version number.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.2
	 */
	public $version;

	// Admin - Start
	/**
	 * The admin object.
	 *
	 * @var     object
	 * @access  public
	 * @since   1.0.2
	 */
	public $admin;

	public $plugin_url;
	public $plugin_path;

	/**
	 * Constructor function.
	 *
	 * @access  public
	 * @since   1.0.2
	 * @return  void
	 */
	public function __construct() {
		$this->token       = 'ocean-contact-info';
		$this->plugin_url  = plugin_dir_url( __FILE__ );
		$this->plugin_path = plugin_dir_path( __FILE__ );
		$this->version     = '2.0.1';

		register_activation_hook( __FILE__, array( $this, 'install' ) );

		add_filter( 'ocean_register_tm_strings', array( $this, 'register_tm_strings' ) );
	}

	public function init() {
		add_action( 'init', array( $this, 'setup' ) );
	}

	/**
	 * Main Ocean_Contact_Info Instance
	 *
	 * Ensures only one instance of Ocean_Contact_Info is loaded or can be loaded.
	 *
	 * @since 1.0.2
	 * @static
	 * @see Ocean_Contact_Info()
	 * @return Ocean_Contact_Info Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	} // End instance()

	/**
	 * Installation.
	 * Runs on activation. Logs the version number and assigns a notice message to a WordPress option.
	 *
	 * @access  public
	 * @since   1.0.2
	 * @return  void
	 */
	public function install() {
		$this->_log_version_number();
	}

	/**
	 * Log the plugin version number.
	 *
	 * @access  private
	 * @since   1.0.2
	 * @return  void
	 */
	private function _log_version_number() {
		// Log the version number.
		update_option( $this->token . '-version', $this->version );
	}

	/**
	 * Register translation strings.
	 */
	public static function register_tm_strings( $strings ) {

		if ( is_array( $strings ) ) {
			$strings['ciah_contactinfo_text']       = 'Important information about your company could be always visible for your customers.';
			$strings['ciah_contactinfo_button_url'] = '#';
			$strings['ciah_contactinfo_button_txt'] = 'Call To Action';
		}

		return $strings;

	}

	/**
	 * Setup all the things.
	 * Only executes if OceanWP or a child theme using OceanWP as a parent is active and the extension specific filter returns true.
	 *
	 * @return void
	 */
	public function setup() {
		$theme = wp_get_theme();

		if ( 'OceanWP' == $theme->name || 'oceanwp' == $theme->template ) {

			add_action( 'customize_register', array( $this, 'customize_register' ) );
			add_action( 'customize_preview_init', array( $this, 'customize_preview_js' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ), 999 );
			add_action( 'ocean_after_header_inner', array( $this, 'contact_info' ), 10, 1);
			add_filter( 'ocean_head_css', array( $this, 'head_css' ) );
		}
	}

	/**
	 * Customizer Controls and settings
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function customize_register( $wp_customize ) {

		/**
		 * Add a new section
		 */
		$wp_customize->add_section(
			'ciah_section',
			array(
				'title'    => esc_html__( 'Contact Info', 'ocean-contact-info' ),
				'priority' => 210,
			)
		);

		/**
		 * On Contact Info
		 */
		$wp_customize->add_setting(
			'ciah_contactinfo_on_heading',
			array(
				'sanitize_callback' => 'wp_kses',
			)
		);

		$wp_customize->add_control(
			new OceanWP_Customizer_Heading_Control(
				$wp_customize,
				'ciah_contactinfo_on_heading',
				array(
					'label'    => esc_html__( 'On Contact Info', 'ocean' ),
					'section'  => 'ciah_section',
					'priority' => 10,
				)
			)
		);

		$wp_customize->add_setting(
			'ciah_on',
			array(
				'default'           => true,
				'sanitize_callback' => 'oceanwp_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'ciah_on',
				array(
					'label'    => esc_html__( 'On Contact Info', 'ocean-contact-info' ),
					'section'  => 'ciah_section',
					'settings' => 'ciah_on',
					'type'     => 'checkbox',
					'priority' => 10,
				)
			)
		);

		if (!get_theme_mod('ciah_on', true)){
			return;
		}

		/**
		 * Only Home Page
		 */
		$wp_customize->add_setting(
			'ciah_contactinfo_only_home_page_heading',
			array(
				'sanitize_callback' => 'wp_kses',
			)
		);

		$wp_customize->add_control(
			new OceanWP_Customizer_Heading_Control(
				$wp_customize,
				'ciah_contactinfo_only_home_page_heading',
				array(
					'label'    => esc_html__( 'Only Home Page', 'ocean' ),
					'section'  => 'ciah_section',
					'priority' => 10,
				)
			)
		);

		$wp_customize->add_setting(
			'ciah_only_home_page',
			array(
				'default'           => true,
				'sanitize_callback' => 'oceanwp_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'ciah_only_home_page',
				array(
					'label'    => esc_html__( 'Only Home Page', 'ocean-contact-info' ),
					'section'  => 'ciah_section',
					'settings' => 'ciah_only_home_page',
					'type'     => 'checkbox',
					'priority' => 10,
				)
			)
		);

		/**
		 * Contact Close Button
		 */

		$wp_customize->add_setting(
			'ciah_contactinfo_close_button_heading',
			array(
				'sanitize_callback' => 'wp_kses',
			)
		);

		$wp_customize->add_control(
			new OceanWP_Customizer_Heading_Control(
				$wp_customize,
				'ciah_contactinfo_close_button_heading',
				array(
					'label'    => esc_html__( 'Close Button', 'ocean' ),
					'section'  => 'ciah_section',
					'priority' => 10,
				)
			)
		);

		$wp_customize->add_setting(
			'ciah_contactinfo_close_button',
			array(
				'default'           => true,
				'sanitize_callback' => 'oceanwp_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'ciah_contactinfo_close_button',
				array(
					'label'    => esc_html__( 'Close Button', 'ocean-contact-info' ),
					'section'  => 'ciah_section',
					'settings' => 'ciah_contactinfo_close_button',
					'type'     => 'checkbox',
					'priority' => 10,
				)
			)
		);

		/**
		 * Contact Info text
		 */
		$wp_customize->add_setting(
			'ciah_contactinfo_text',
			array(
				'default'           => 'Important information about your company could be always visible for your customers.',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_kses_post',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'ciah_contactinfo_text',
				array(
					'label'    => esc_html__( 'Content', 'ocean-contact-info' ),
					'section'  => 'ciah_section',
					'settings' => 'ciah_contactinfo_text',
					'type'     => 'textarea',
					'priority' => 10,
				)
			)
		);

		/**
		 * Contact Info text Typography
		 */
		$wp_customize->add_setting(
			'ciah_contactinfo_text_typo_font_family',
			array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_setting(
			'ciah_contactinfo_text_typo_font_size',
			array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_setting(
			'ciah_contactinfo_text_typo_font_weight',
			array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_key',
			)
		);
		$wp_customize->add_setting(
			'ciah_contactinfo_text_typo_font_style',
			array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_key',
			)
		);
		$wp_customize->add_setting(
			'ciah_contactinfo_text_typo_transform',
			array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_key',
			)
		);
		$wp_customize->add_setting(
			'ciah_contactinfo_text_typo_line_height',
			array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_setting(
			'ciah_contactinfo_text_typo_spacing',
			array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new OceanWP_Customizer_Typo_Control(
				$wp_customize,
				'ciah_contactinfo_text_typo',
				array(
					'label'    => esc_html__( 'Typography', 'ocean-contact-info' ),
					'section'  => 'ciah_section',
					'settings' => array(
						'family'      => 'ciah_contactinfo_text_typo_font_family',
						'size'        => 'ciah_contactinfo_text_typo_font_size',
						'weight'      => 'ciah_contactinfo_text_typo_font_weight',
						'style'       => 'ciah_contactinfo_text_typo_font_style',
						'transform'   => 'ciah_contactinfo_text_typo_transform',
						'line_height' => 'ciah_contactinfo_text_typo_line_height',
						'spacing'     => 'ciah_contactinfo_text_typo_spacing',
					),
					'priority' => 10,
					'l10n'     => array(),
				)
			)
		);

		/**
		 * Contact Info button heading
		 */
		$wp_customize->add_setting(
			'ciah_contactinfo_button_heading',
			array(
				'sanitize_callback' => 'wp_kses',
			)
		);

		$wp_customize->add_control(
			new OceanWP_Customizer_Heading_Control(
				$wp_customize,
				'ciah_contactinfo_button_heading',
				array(
					'label'    => esc_html__( 'Button', 'ocean' ),
					'section'  => 'ciah_section',
					'priority' => 10,
				)
			)
		);

		/**
		 * Contact Info button
		 */
		$wp_customize->add_setting(
			'ciah_contactinfo_button',
			array(
				'default'           => true,
				'sanitize_callback' => 'oceanwp_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'ciah_contactinfo_button',
				array(
					'label'    => esc_html__( 'Enable Button', 'ocean-contact-info' ),
					'section'  => 'ciah_section',
					'settings' => 'ciah_contactinfo_button',
					'type'     => 'checkbox',
					'priority' => 10,
				)
			)
		);

		/**
		 * Contact Info button URL
		 */
		$wp_customize->add_setting(
			'ciah_contactinfo_button_url',
			array(
				'default'           => '#',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'ciah_contactinfo_button_url',
				array(
					'label'    => esc_html__( 'Button URL', 'ocean-contact-info' ),
					'section'  => 'ciah_section',
					'settings' => 'ciah_contactinfo_button_url',
					'type'     => 'text',
					'priority' => 10,
				)
			)
		);

		/**
		 * Contact Info button text
		 */
		$wp_customize->add_setting(
			'ciah_contactinfo_button_txt',
			array(
				'default'           => esc_html__( 'Call To Action', 'ocean-contact-info' ),
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'ciah_contactinfo_button_txt',
				array(
					'label'    => esc_html__( 'Button Text', 'ocean-contact-info' ),
					'section'  => 'ciah_section',
					'settings' => 'ciah_contactinfo_button_txt',
					'type'     => 'text',
					'priority' => 10,
				)
			)
		);

		/**
		 * Contact Info button target
		 */
		$wp_customize->add_setting(
			'ciah_contactinfo_button_target',
			array(
				'default'           => 'blank',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'oceanwp_sanitize_select',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'ciah_contactinfo_button_target',
				array(
					'label'    => esc_html__( 'Button Target', 'ocean-contact-info' ),
					'section'  => 'ciah_section',
					'settings' => 'ciah_contactinfo_button_target',
					'type'     => 'select',
					'choices'  => array(
						'blank' => esc_html__( 'Blank', 'ocean-contact-info' ),
						'self'  => esc_html__( 'Self', 'ocean-contact-info' ),
					),
					'priority' => 10,
				)
			)
		);

		/**
		 * Contact Info button rel
		 */
		$wp_customize->add_setting(
			'ciah_contactinfo_button_rel',
			array(
				'default'           => '',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'oceanwp_sanitize_select',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'ciah_contactinfo_button_rel',
				array(
					'label'    => esc_html__( 'Button Rel', 'ocean-contact-info' ),
					'section'  => 'ciah_section',
					'settings' => 'ciah_contactinfo_button_rel',
					'type'     => 'select',
					'choices'  => array(
						''           => esc_html__( 'None', 'ocean-contact-info' ),
						'nofollow'   => esc_html__( 'Nofollow', 'ocean-contact-info' ),
						'noopnoref'  => esc_html__( 'Noopener Noreferrer', 'ocean-contact-info' ),
						'nofnopnorr' => esc_html__( 'Nofollow Noopener Noreferrer', 'ocean-contact-info' ),
					),
					'priority' => 10,
				)
			)
		);

		/**
		 * Contact Info button custom classes
		 */
		$wp_customize->add_setting(
			'ciah_contactinfo_button_classes',
			array(
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'ciah_contactinfo_button_classes',
				array(
					'label'    => esc_html__( 'Button Custom Classes', 'ocean-contact-info' ),
					'section'  => 'ciah_section',
					'settings' => 'ciah_contactinfo_button_classes',
					'type'     => 'text',
					'priority' => 10,
				)
			)
		);

		/**
		 * Contact Info button Typography
		 */
		$wp_customize->add_setting(
			'ciah_contactinfo_button_typo_font_family',
			array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_setting(
			'ciah_contactinfo_button_typo_font_size',
			array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_setting(
			'ciah_contactinfo_button_typo_font_weight',
			array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_key',
			)
		);
		$wp_customize->add_setting(
			'ciah_contactinfo_button_typo_font_style',
			array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_key',
			)
		);
		$wp_customize->add_setting(
			'ciah_contactinfo_button_typo_transform',
			array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_key',
			)
		);
		$wp_customize->add_setting(
			'ciah_contactinfo_button_typo_line_height',
			array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_setting(
			'ciah_contactinfo_button_typo_spacing',
			array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new OceanWP_Customizer_Typo_Control(
				$wp_customize,
				'ciah_contactinfo_button_typo',
				array(
					'label'    => esc_html__( 'Typography', 'ocean-contact-info' ),
					'section'  => 'ciah_section',
					'settings' => array(
						'family'      => 'ciah_contactinfo_button_typo_font_family',
						'size'        => 'ciah_contactinfo_button_typo_font_size',
						'weight'      => 'ciah_contactinfo_button_typo_font_weight',
						'style'       => 'ciah_contactinfo_button_typo_font_style',
						'transform'   => 'ciah_contactinfo_button_typo_transform',
						'line_height' => 'ciah_contactinfo_button_typo_line_height',
						'spacing'     => 'ciah_contactinfo_button_typo_spacing',
					),
					'priority' => 10,
					'l10n'     => array(),
				)
			)
		);

		/**
		 * Contact Info styling
		 */
		$wp_customize->add_setting(
			'ciah_contactinfo_styling_heading',
			array(
				'sanitize_callback' => 'wp_kses',
			)
		);

		$wp_customize->add_control(
			new OceanWP_Customizer_Heading_Control(
				$wp_customize,
				'ciah_contactinfo_styling_heading',
				array(
					'label'    => esc_html__( 'Styling', 'ocean' ),
					'section'  => 'ciah_section',
					'priority' => 10,
				)
			)
		);

		/**
		 * Contact Info padding
		 */
		$wp_customize->add_setting(
			'ciah_contactinfo_top_padding',
			array(
				'transport'         => 'postMessage',
				'default'           => '20',
				'sanitize_callback' => 'oceanwp_sanitize_number',
			)
		);
		$wp_customize->add_setting(
			'ciah_contactinfo_bottom_padding',
			array(
				'transport'         => 'postMessage',
				'default'           => '20',
				'sanitize_callback' => 'oceanwp_sanitize_number',
			)
		);

		$wp_customize->add_setting(
			'ciah_contactinfo_tablet_top_padding',
			array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'oceanwp_sanitize_number_blank',
			)
		);
		$wp_customize->add_setting(
			'ciah_contactinfo_tablet_bottom_padding',
			array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'oceanwp_sanitize_number_blank',
			)
		);

		$wp_customize->add_setting(
			'ciah_contactinfo_mobile_top_padding',
			array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'oceanwp_sanitize_number_blank',
			)
		);
		$wp_customize->add_setting(
			'ciah_contactinfo_mobile_bottom_padding',
			array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'oceanwp_sanitize_number_blank',
			)
		);

		$wp_customize->add_control(
			new OceanWP_Customizer_Dimensions_Control(
				$wp_customize,
				'ciah_contactinfo_padding',
				array(
					'label'       => esc_html__( 'Padding (px)', 'oceanwp' ),
					'section'     => 'ciah_section',
					'settings'    => array(
						'desktop_top'    => 'ciah_contactinfo_top_padding',
						'desktop_bottom' => 'ciah_contactinfo_bottom_padding',
						'tablet_top'     => 'ciah_contactinfo_tablet_top_padding',
						'tablet_bottom'  => 'ciah_contactinfo_tablet_bottom_padding',
						'mobile_top'     => 'ciah_contactinfo_mobile_top_padding',
						'mobile_bottom'  => 'ciah_contactinfo_mobile_bottom_padding',
					),
					'priority'    => 10,
					'input_attrs' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					),
				)
			)
		);

		/**
		 * Contact Info background
		 */
		$wp_customize->add_setting(
			'ciah_contactinfo_bg',
			array(
				'default'           => 'rgb(0 0 0 / 60%)',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'oceanwp_sanitize_color',
			)
		);

		$wp_customize->add_control(
			new OceanWP_Customizer_Color_Control(
				$wp_customize,
				'ciah_contactinfo_bg',
				array(
					'label'    => esc_html__( 'Background', 'ocean-contact-info' ),
					'section'  => 'ciah_section',
					'settings' => 'ciah_contactinfo_bg',
					'priority' => 10,
				)
			)
		);

		/**
		 * Contact Info border color
		 */
		$wp_customize->add_setting(
			'ciah_contactinfo_border',
			array(
				'default'           => 'rgb(0 0 0 / 60%)',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'oceanwp_sanitize_color',
			)
		);

		$wp_customize->add_control(
			new OceanWP_Customizer_Color_Control(
				$wp_customize,
				'ciah_contactinfo_border',
				array(
					'label'    => esc_html__( 'Border Color', 'ocean-contact-info' ),
					'section'  => 'ciah_section',
					'settings' => 'ciah_contactinfo_border',
					'priority' => 10,
				)
			)
		);

		/**
		 * Contact Info color
		 */
		$wp_customize->add_setting(
			'ciah_contactinfo_color',
			array(
				'default'           => '#dddddd',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'oceanwp_sanitize_color',
			)
		);

		$wp_customize->add_control(
			new OceanWP_Customizer_Color_Control(
				$wp_customize,
				'ciah_contactinfo_color',
				array(
					'label'    => esc_html__( 'Text Color', 'ocean-contact-info' ),
					'section'  => 'ciah_section',
					'settings' => 'ciah_contactinfo_color',
					'priority' => 10,
				)
			)
		);

		/**
		 * Contact Info links color
		 */
		$wp_customize->add_setting(
			'ciah_contactinfo_links_color',
			array(
				'default'           => '#ffc300',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'oceanwp_sanitize_color',
			)
		);

		$wp_customize->add_control(
			new OceanWP_Customizer_Color_Control(
				$wp_customize,
				'ciah_contactinfo_links_color',
				array(
					'label'    => esc_html__( 'Links', 'ocean-contact-info' ),
					'section'  => 'ciah_section',
					'settings' => 'ciah_contactinfo_links_color',
					'priority' => 10,
				)
			)
		);

		/**
		 * Contact Info links color hover
		 */
		$wp_customize->add_setting(
			'ciah_contactinfo_links_color_hover',
			array(
				'default'           => '#13aff0',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'oceanwp_sanitize_color',
			)
		);

		$wp_customize->add_control(
			new OceanWP_Customizer_Color_Control(
				$wp_customize,
				'ciah_contactinfo_links_color_hover',
				array(
					'label'    => esc_html__( 'Links: Hover', 'ocean-contact-info' ),
					'section'  => 'ciah_section',
					'settings' => 'ciah_contactinfo_links_color_hover',
					'priority' => 10,
				)
			)
		);

		/**
		 * Contact Info button border radius
		 */
		$wp_customize->add_setting(
			'ciah_contactinfo_button_border_radius',
			array(
				'default'           => '7',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'oceanwp_sanitize_number',
			)
		);

		$wp_customize->add_control(
			new OceanWP_Customizer_Range_Control(
				$wp_customize,
				'ciah_contactinfo_button_border_radius',
				array(
					'label'    => esc_html__( 'Button Border Radius (px)', 'ocean-contact-info' ),
					'section'  => 'ciah_section',
					'settings' => 'ciah_contactinfo_button_border_radius',
					'priority' => 10,
				)
			)
		);

		/**
		 * Contact Info button background
		 */
		$wp_customize->add_setting(
			'ciah_contactinfo_button_bg',
			array(
				'default'           => '#ffc300',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'oceanwp_sanitize_color',
			)
		);

		$wp_customize->add_control(
			new OceanWP_Customizer_Color_Control(
				$wp_customize,
				'ciah_contactinfo_button_bg',
				array(
					'label'    => esc_html__( 'Button Background', 'ocean-contact-info' ),
					'section'  => 'ciah_section',
					'settings' => 'ciah_contactinfo_button_bg',
					'priority' => 10,
				)
			)
		);

		/**
		 * Contact Info button color
		 */
		$wp_customize->add_setting(
			'ciah_contactinfo_button_color',
			array(
				'default'           => '#000000',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'oceanwp_sanitize_color',
			)
		);

		$wp_customize->add_control(
			new OceanWP_Customizer_Color_Control(
				$wp_customize,
				'ciah_contactinfo_button_color',
				array(
					'label'    => esc_html__( 'Button Color', 'ocean-contact-info' ),
					'section'  => 'ciah_section',
					'settings' => 'ciah_contactinfo_button_color',
					'priority' => 10,
				)
			)
		);

		/**
		 * Contact Info button hover background
		 */
		$wp_customize->add_setting(
			'ciah_contactinfo_button_hover_bg',
			array(
				'default'           => '#0b7cac',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'oceanwp_sanitize_color',
			)
		);

		$wp_customize->add_control(
			new OceanWP_Customizer_Color_Control(
				$wp_customize,
				'ciah_contactinfo_button_hover_bg',
				array(
					'label'    => esc_html__( 'Button: Hover Background', 'ocean-contact-info' ),
					'section'  => 'ciah_section',
					'settings' => 'ciah_contactinfo_button_hover_bg',
					'priority' => 10,
				)
			)
		);

		/**
		 * Contact Info button hover color
		 */
		$wp_customize->add_setting(
			'ciah_contactinfo_button_hover_color',
			array(
				'default'           => '#ffffff',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'oceanwp_sanitize_color',
			)
		);

		$wp_customize->add_control(
			new OceanWP_Customizer_Color_Control(
				$wp_customize,
				'ciah_contactinfo_button_hover_color',
				array(
					'label'    => esc_html__( 'Button: Hover Color', 'ocean-contact-info' ),
					'section'  => 'ciah_section',
					'settings' => 'ciah_contactinfo_button_hover_color',
					'priority' => 10,
				)
			)
		);
	}

	/**
	 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
	 */
	public function customize_preview_js() {
		wp_enqueue_script( 'ciah-customizer', plugins_url( '/assets/js/customizer.min.js', __FILE__ ), array( 'customize-preview' ), '1.0', true );
		wp_localize_script(
			'ciah-customizer',
			'ciah_contactinfo',
			array(
				'googleFontsUrl'    => '//fonts.googleapis.com',
				'googleFontsWeight' => '100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i',
			)
		);
	}

	/**
	 * Enqueue scripts.
	 */
	public function scripts() {

		// Load main stylesheet
		wp_enqueue_style( 'ciah-style', plugins_url( '/assets/css/style.min.css', __FILE__ ) );

		// If rtl
		if ( is_RTL() ) {
			wp_enqueue_style( 'ciah-style-rtl', plugins_url( '/assets/css/rtl.css', __FILE__ ) );
		}

		// Contact Info font
		$settings = array(
			'ciah_contactinfo_text_typo_font_family',
			'ciah_contactinfo_button_typo_font_family',
		);

		foreach ( $settings as $setting ) {

			// Get fonts
			$fonts = array();
			$val   = get_theme_mod( $setting );

			// If there is a value lets do something
			if ( ! empty( $val ) ) {

				// Sanitize
				$val = str_replace( '"', '', $val );

				$fonts[] = $val;

			}

			// Loop through and enqueue fonts
			if ( ! empty( $fonts ) && is_array( $fonts ) ) {
				foreach ( $fonts as $font ) {
					oceanwp_enqueue_google_font( $font );
				}
			}
		}

	}

	/**
	 * Display the Contact Info.
	 */
	public function display_contact_info() {

		// Return true by default
		$return = true;

		// Apply filters and return
		return apply_filters( 'ciah_display_contact_info', $return );

	}

	/**
	 * Gets the Contact Info template part.
	 */
	public function contact_info() {

		// Return if disabled
		if (!self::display_contact_info() || !get_theme_mod('ciah_on', true)) {
			return;
		}

		if (! is_front_page() && get_theme_mod( 'ciah_only_home_page', true )){
			return;
		}


		$file       = $this->plugin_path . 'template/contact-info.php';

		if ( file_exists( $file ) ) {
			include $file;
		}

	}
	
	/**
	 * Add css in head tag.
	 */
	public function head_css( $output ) {

		// Global vars
		$top_padding           = get_theme_mod( 'ciah_contactinfo_top_padding', '20' );
		$bottom_padding        = get_theme_mod( 'ciah_contactinfo_bottom_padding', '20' );
		$tablet_top_padding    = get_theme_mod( 'ciah_contactinfo_tablet_top_padding' );
		$tablet_bottom_padding = get_theme_mod( 'ciah_contactinfo_tablet_bottom_padding' );
		$mobile_top_padding    = get_theme_mod( 'ciah_contactinfo_mobile_top_padding' );
		$mobile_bottom_padding = get_theme_mod( 'ciah_contactinfo_mobile_bottom_padding' );
		$background            = get_theme_mod( 'ciah_contactinfo_bg', 'rgb(0 0 0 / 60%)' );
		$border                = get_theme_mod( 'ciah_contactinfo_border', 'rgb(0 0 0 / 60%)' );
		$color                 = get_theme_mod( 'ciah_contactinfo_color', '#dddddd' );
		$links                 = get_theme_mod( 'ciah_contactinfo_links_color', '#ffc300' );
		$links_hover           = get_theme_mod( 'ciah_contactinfo_links_color_hover', '#13aff0' );
		$button_border_radius  = get_theme_mod( 'ciah_contactinfo_button_border_radius', '7' );
		$button_bg             = get_theme_mod( 'ciah_contactinfo_button_bg', '#ffc300' );
		$button_color          = get_theme_mod( 'ciah_contactinfo_button_color', '#000000' );
		$button_hover_bg       = get_theme_mod( 'ciah_contactinfo_button_hover_bg', '#0b7cac' );
		$button_hover_color    = get_theme_mod( 'ciah_contactinfo_button_hover_color', '#ffffff' );

		// Text typography
		$text_font_family    = get_theme_mod( 'ciah_contactinfo_text_typo_font_family' );
		$text_font_size      = get_theme_mod( 'ciah_contactinfo_text_typo_font_size' );
		$text_font_weight    = get_theme_mod( 'ciah_contactinfo_text_typo_font_weight' );
		$text_font_style     = get_theme_mod( 'ciah_contactinfo_text_typo_font_style' );
		$text_text_transform = get_theme_mod( 'ciah_contactinfo_text_typo_transform' );
		$text_line_height    = get_theme_mod( 'ciah_contactinfo_text_typo_line_height' );
		$text_letter_spacing = get_theme_mod( 'ciah_contactinfo_text_typo_spacing' );

		// Button typography
		$button_font_family    = get_theme_mod( 'ciah_contactinfo_button_typo_font_family' );
		$button_font_size      = get_theme_mod( 'ciah_contactinfo_button_typo_font_size' );
		$button_font_weight    = get_theme_mod( 'ciah_contactinfo_button_typo_font_weight' );
		$button_font_style     = get_theme_mod( 'ciah_contactinfo_button_typo_font_style' );
		$button_text_transform = get_theme_mod( 'ciah_contactinfo_button_typo_transform' );
		$button_line_height    = get_theme_mod( 'ciah_contactinfo_button_typo_line_height' );
		$button_letter_spacing = get_theme_mod( 'ciah_contactinfo_button_typo_spacing' );

		// Define css var
		$css             = '';
		$text_typo_css   = '';
		$button_typo_css = '';

		// CSS if boxed style
		$boxed_style      = get_theme_mod( 'ocean_main_layout_style', 'wide' );
		$boxed_width      = get_theme_mod( 'ocean_boxed_width', '1280' );
		$half_boxed_width = $boxed_width / 2;
		if ( 'boxed' == $boxed_style ) {
			$css .= 'body.has-parallax-footer #contact-info-wrap {width:' . $boxed_width . 'px;left:50%;margin-left:-' . $half_boxed_width . 'px}';
		}

		// Padding
		if ( isset( $top_padding ) && '20' != $top_padding && '' != $top_padding
			|| isset( $bottom_padding ) && '20' != $bottom_padding && '' != $bottom_padding ) {
			$css .= '#contact-info-wrap{padding:' . oceanwp_spacing_css( $top_padding, '', $bottom_padding, '' ) . '}';
		}

		// Tablet padding
		if ( isset( $tablet_top_padding ) && '' != $tablet_top_padding
			|| isset( $tablet_bottom_padding ) && '' != $tablet_bottom_padding ) {
			$css .= '@media (max-width: 768px){#contact-info-wrap{padding:' . oceanwp_spacing_css( $tablet_top_padding, '', $tablet_bottom_padding, '' ) . '}}';
		}

		// Mobile padding
		if ( isset( $mobile_top_padding ) && '' != $mobile_top_padding
			|| isset( $mobile_bottom_padding ) && '' != $mobile_bottom_padding ) {
			$css .= '@media (max-width: 480px){#contact-info-wrap{padding:' . oceanwp_spacing_css( $mobile_top_padding, '', $mobile_bottom_padding, '' ) . '}}';
		}

		// Add background
		if ( ! empty( $background ) && 'rgb(0 0 0 / 60%)' != $background ) {
			$css .= '#contact-info-wrap{background-color:' . $background . ';}';
		}

		// Add border
		if ( ! empty( $border ) && 'rgb(0 0 0 / 60%)' != $border ) {
			$css .= '#contact-info-wrap{border-color:' . $border . ';}';
		}

		// Add color
		if ( ! empty( $color ) && '#dddddd' != $color ) {
			$css .= '#contact-info-wrap{color:' . $color . ';}';
		}

		// Add links
		if ( ! empty( $links ) && '#ffc300' != $links ) {
			$css .= '.contact-info-content a{color:' . $links . ';}';
		}

		// Add links hover
		if ( ! empty( $links_hover ) && '#13aff0' != $links_hover ) {
			$css .= '.contact-info-content a:hover{color:' . $links_hover . ';}';
		}

		// Add button border radius
		if ( ! empty( $button_border_radius ) && '7' != $button_border_radius ) {
			$css .= '#contact-info .contactinfo-button{border-radius:' . $button_border_radius . 'px;}';
		}

		// Add button background
		if ( ! empty( $button_bg ) && '#ffc300' != $button_bg ) {
			$css .= '#contact-info .contactinfo-button{background-color:' . $button_bg . ';}';
		}

		// Add button color
		if ( ! empty( $button_color ) && '#000000' != $button_color ) {
			$css .= '#contact-info .contactinfo-button{color:' . $button_color . ';}';
		}

		// Add button hover background
		if ( ! empty( $button_hover_bg ) && '#0b7cac' != $button_hover_bg ) {
			$css .= '#contact-info .contactinfo-button:hover{background-color:' . $button_hover_bg . ';}';
		}

		// Add button hover color
		if ( ! empty( $button_hover_color ) && '#ffffff' != $button_hover_color ) {
			$css .= '#contact-info .contactinfo-button:hover{color:' . $button_hover_color . ';}';
		}

		// Add text font family
		if ( ! empty( $text_font_family ) ) {
			$text_typo_css .= 'font-family:' . $text_font_family . ';';
		}

		// Add text font size
		if ( ! empty( $text_font_size ) ) {
			$text_typo_css .= 'font-size:' . $text_font_size . ';';
		}

		// Add text font weight
		if ( ! empty( $text_font_weight ) ) {
			$text_typo_css .= 'font-weight:' . $text_font_weight . ';';
		}

		// Add text font style
		if ( ! empty( $text_font_style ) ) {
			$text_typo_css .= 'font-style:' . $text_font_style . ';';
		}

		// Add text text transform
		if ( ! empty( $text_text_transform ) ) {
			$text_typo_css .= 'text-transform:' . $text_text_transform . ';';
		}

		// Add text line height
		if ( ! empty( $text_line_height ) ) {
			$text_typo_css .= 'line-height:' . $text_line_height . ';';
		}

		// Add text letter spacing
		if ( ! empty( $text_letter_spacing ) ) {
			$text_typo_css .= 'letter-spacing:' . $text_letter_spacing . ';';
		}

		// text typography css
		if ( ! empty( $text_typo_css ) ) {
			$css .= '#contact-info .contact-info-content{' . $text_typo_css . '}';
		}

		// Add button font family
		if ( ! empty( $button_font_family ) ) {
			$button_typo_css .= 'font-family:' . $button_font_family . ';';
		}

		// Add button font size
		if ( ! empty( $button_font_size ) ) {
			$button_typo_css .= 'font-size:' . $button_font_size . ';';
		}

		// Add button font weight
		if ( ! empty( $button_font_weight ) ) {
			$button_typo_css .= 'font-weight:' . $button_font_weight . ';';
		}

		// Add button font style
		if ( ! empty( $button_font_style ) ) {
			$button_typo_css .= 'font-style:' . $button_font_style . ';';
		}

		// Add button text transform
		if ( ! empty( $button_text_transform ) ) {
			$button_typo_css .= 'text-transform:' . $button_text_transform . ';';
		}

		// Add button line height
		if ( ! empty( $button_line_height ) ) {
			$button_typo_css .= 'line-height:' . $button_line_height . ';';
		}

		// Add button letter spacing
		if ( ! empty( $button_letter_spacing ) ) {
			$button_typo_css .= 'letter-spacing:' . $button_letter_spacing . ';';
		}

		// button typography css
		if ( ! empty( $button_typo_css ) ) {
			$css .= '#contact-info .contactinfo-button{' . $button_typo_css . '}';
		}

		// Return CSS
		if ( ! empty( $css ) ) {
			$output .= '/* Contact Info CSS */' . $css;
		}

		// Return output css
		return $output;

	}

} // End Class

Ocean_Contact_Info::instance()->init();