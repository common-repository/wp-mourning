<?php
/**
 * Plugin Name:     WP Mourning
 * Plugin URI:      
 * Description:     Grey out the website for mourning
 * Author:          Chanon Srithongsook
 * Author URI:      http://chanon.info
 * Text Domain:     wp-mourning
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Wp_Mourning
 */
define('WPM_PLUGIN_BASENAME', plugin_basename( __FILE__ ));

if ( is_admin() ) require_once __DIR__ . '/admin/admin.php';


class WPMourning{

	private $options;

	public function __construct(){
		$this->mourning_init();
	}


	private function is_date_activate($start_date, $end_date, $schedule_date)
	{
	  // Convert to timestamp
	  $start_ts = strtotime($start_date);
	  $end_ts = strtotime($end_date);
	  $schedule_ts = strtotime($schedule_date);
	
	  // Check that user date is between start & end
	  return (($schedule_ts >= $start_ts) && ($schedule_ts <= $end_ts));
	}

	public function check_schedule(){
			$current_date = date('Y-m-d');
			$current_year = date('Y');
			
			if ($this->options['schedule_date'] == 'once'): 
				return $this->is_date_activate($this->options['schedule_from'], $this->options['schedule_to'], $current_date);
			elseif ($this->options['schedule_date'] == 'yearly'):
				// TODO: check recurring for month between 2 year
				$schedule_from = date($current_year . '-m-d', strtotime($this->options['schedule_from']));
				$schedule_to = date($current_year . '-m-d', strtotime($this->options['schedule_to']));
				return $this->is_date_activate($schedule_from, $schedule_to, $current_date);
			else:
				return true;
			endif;
	}

	public function mourning_init(){
		$this->options = get_option('wpm_options');

		if (!$this->check_schedule()) return;

		add_action('wp_enqueue_scripts', array($this, 'mourning_style'), 999);

		if ($this->options['mourning_text_on']) add_action('wp_footer', array($this, 'show_mourning_text'));

		if ($this->options['show_ribbon'] != 'none'):
			add_action('wp_footer', array($this, 'show_ribbon'));
		endif;

	}

	private function ribbon_right_style(){
			$css = ".wpm-ribbon{
		-moz-transform: scaleX(-1);
        -o-transform: scaleX(-1);
        -webkit-transform: scaleX(-1);
        transform: scaleX(-1);
        filter: FlipH;
        -ms-filter: \"FlipH\";
		left:auto;
		right:-.8rem;
		}";
			return $css;
	}


	public function show_mourning_text(){
		$mourning_text = $this->options['mourning_text'];
		$snippet = "
		<script type='text/javascript'>
			jQuery('document').ready(function($){
				$('body').prepend('<div class=\"wpm-mourning-text\">{$mourning_text} <a href=\"#\" class=\"fa fa-times-circle-o close-mourning\"> </a></div>');
				$('.close-mourning').on('click', function(){ $('.wpm-mourning-text').slideUp(1000); });
			});
		</script>
";
		echo $snippet;

	}

	public function show_ribbon(){
			$snippet = "
		<script type='text/javascript'>
			jQuery('document').ready(function($){
				$('body').prepend('<img src=\"".plugin_dir_url(__FILE__)."images/wp-mourning-ribbon.png"."\" class=\"wpm-ribbon\" >');
				$('.wpm-ribbon').on('click', function(){
					$(this).fadeOut(2000);
				});
			});
		</script>
";
		echo $snippet; 
	}

	public function mourning_style(){
		wp_enqueue_script('jquery');
		wp_enqueue_style('wpm-mourning-style', plugin_dir_url( __FILE__ ) . 'css/mourning.css');
		wp_enqueue_style('font-awesome', plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css', array(), '4.6.3');
			$grey_percentage = $this->options['grey_percentage'];
			$grey_decimal = $grey_percentage / 100;
			$custom_mourning = "
			html {
				/* IE */
			    filter: progidXImageTransform.Microsoft.BasicImage(grayscale={$grey_decimal});
			    /* Chrome, Safari */
			    -webkit-filter: grayscale({$grey_decimal});
			    /* Firefox */
			    filter: grayscale({$grey_decimal});
			    filter: grayscale({$grey_percentage}%);
			    filter: gray; 
			    -moz-filter: grayscale({$grey_percentage}%);
				-webkit-filter: grayscale({$grey_percentage}%);
			}
			";
			if ($this->options['show_ribbon'] == 'right') $custom_mourning .= $this->ribbon_right_style();
		wp_add_inline_style( 'wpm-mourning-style', $custom_mourning );

	}

}

if (!is_admin()) $wpm_mourning = new WPMourning(); 


