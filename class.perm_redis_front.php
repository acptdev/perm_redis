<?php 
  //referência http://www.wpexplorer.com/create-widget-plugin-wordpress/
  class PermRedisFront extends WP_Widget{  
    
    /* registro do widget*/
    public static function registrarWidget()
    {
      register_widget( 'PermRedisFront' );
    }
    
    // Main constructor
    public function __construct() {
      parent::__construct(
        'permredis_front','PermRedisFront',
        array(
          'customize_selective_refresh' => true,
        )
      );
    }

    // The widget form (for the backend )
    public function form( $instance ) {	      
      echo "<p>widget code</p>";      
    }

    // Update widget settings
    public function update( $new_instance, $old_instance ) {
      /* ... Função para atualizar as opções do widget */
      
    }

    // Display the widget
    public function widget( $args, $instance ) {
      echo "formulários de busca, preenchimento e listagem do widget";
      echo $after_widget;
    }
  }

?>
