<?php
class GitConnector_Widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'GitConnector',
            'GitConnector',
            array(
                'description' => 'Un formulaire d\'inscription à la newsletter.'
            )
        );
        load_plugin_textdomain('gitconnector', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    public function widget($args, $instance)
    {
        echo 'widget Git Connector';
    }

    public function form($instance)
    {
        $title = isset($instance['title']) ? $instance['title'] : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_name('githubAccount'); ?>">
                <?php _e('Github Account:', 'gitconnector'); ?>
            </label>
            <input class="widefat" id="<?php echo $this->get_field_id('githubAccount'); ?>" name="<?php echo $this->get_field_name('githubAccount'); ?>" type="text" value="<?php echo  $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_name('repository'); ?>">
                <?php _e('Repository:', 'gitconnector'); ?>
            </label>
            <input class="widefat" id="<?php echo $this->get_field_id('repository'); ?>" name="<?php echo $this->get_field_name('repository'); ?>" type="text" value="<?php echo  $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_name('commitQty'); ?>"><?php _e('Number of commits to display:', 'gitconnector'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('commitQty'); ?>" name="<?php echo $this->get_field_name('commitQty'); ?>" type="text" value="<?php echo  $title; ?>" />
        </p>
<?php
    }
}
