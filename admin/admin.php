<?php 
class WPMourningAdmin
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
		add_filter( 'plugin_action_links_' . WPM_PLUGIN_BASENAME, array( $this, 'add_action_links' ));
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    public function page_init(){
        register_setting( 'wpm-option-group', 'wpm_options');
        wp_register_script('wpm_admin_script', plugin_dir_url( __FILE__ ) . 'js/admin.js', array('jquery-ui-slider', 'jquery-ui-datepicker'), '1.0');
        wp_enqueue_script('wpm_admin_script');
        wp_enqueue_style('jquery-ui-smoothness-style', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.css');
        wp_enqueue_style('wp-mourning-admin-style', plugin_dir_url( __FILE__ ) . 'css/admin.css');

    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        add_options_page( 'WP Mourning settings', 'Mourning', 'manage_options', 'wpm_setting', array( $this, 'create_admin_page' ) );

    }

	public function add_action_links ( $links ) {
			 $mylinks = array(
					  '<a href="' . admin_url( 'options-general.php?page=wpm_setting' ) . '">Settings</a>',
					   );
			 return array_merge( $links, $mylinks );
	}

  
    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'wpm_options' );
        ?>
            <div class="wrap">
            <h1>WP Mourning settings</h1>

            <form method="post" action="options.php">
                <?php settings_fields( 'wpm-option-group' ); ?>
                <?php do_settings_sections( 'wpm-option-group' ); ?>
                <table class="form-table">
                    <tr valign="top">
                    <th scope="row">Mourning text</th>
                    <td>
                        <input type="checkbox" name="wpm_options[mourning_text_on]" value="1" <?php if( $this->options['mourning_text_on'] ) echo 'checked="checked"'; ?> /> Turn on
                        <br/> <input type="textbox"  name="wpm_options[mourning_text]" value="<?php echo $this->options['mourning_text']; ?>"  />

                    </td>
                    </tr>

                     <tr valign="top">
                    <th scope="row">Grayscale percentage
                    <br/> <img style="width:100px;" src="<?php echo plugin_dir_url( __FILE__ ) . 'images/heart.png'; ?>" class="gray_image" />
                    </th>

                    <td>
                        <div id="grayscale_percent"></div>
                    <span class="gray_percent"></span>%
                    <input id="grayscale_text" type="hidden" value="<?php echo $this->options['grey_percentage']; ?>" name="wpm_options[grey_percentage]" >
                    </td>
                    </tr>
                     <tr valign="top">
                    <th scope="row">Date schedule</th>
                    <td>
					<input type="radio" name="wpm_options[schedule_date]" <?php echo ($this->options['schedule_date'] == 'all-time')? ' checked="checked" ': ''; ?>value="all-time"> All time 
 								 <input type="radio" name="wpm_options[schedule_date]" <?php echo ($this->options['schedule_date'] == 'once')? ' checked="checked" ': ''; ?>value="once"> Once 
 								 <input type="radio" name="wpm_options[schedule_date]" <?php echo ($this->options['schedule_date'] == 'yearly')? ' checked="checked" ': ''; ?>value="yearly"> Yearly 
						<br/> From: <input <?php echo $this->options['scedule_from']; ?>class="datepicker" type="textbox"  name="wpm_options[schedule_from]" value="<?php echo $this->options['schedule_from']; ?>"  />
                        <br/> to: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="datepicker" type="textbox"  name="wpm_options[schedule_to]" value="<?php echo $this->options['schedule_to']; ?>"  />

                    </td>
                    </tr>
                  <tr valign="top">
                    <th scope="row">Show floating ribbon</th>
                    <td>
					<input type="radio" name="wpm_options[show_ribbon]" <?php echo ($this->options['show_ribbon'] == 'none')? ' checked="checked" ': ''; ?>value="none"> None 
 								 <input type="radio" name="wpm_options[show_ribbon]" <?php echo ($this->options['show_ribbon'] == 'left')? ' checked="checked" ': ''; ?>value="left"> Top left 
 								 <input type="radio" name="wpm_options[show_ribbon]" <?php echo ($this->options['show_ribbon'] == 'right')? ' checked="checked" ': ''; ?>value="right"> Top right 

                    </td>
                    </tr>
             
                </table>
                
                <?php submit_button(); ?>

            </form>
            </div>
        <?php
    }

}


if ( is_admin()) $wpm = new WPMourningAdmin();

