<?php

function abcdxyz()
{
	$arr = array('main-menu' => __('Main Menu'));
	register_nav_menus($arr);
}

add_action('init', "abcdxyz");


function themename_custom_logo_setup() {
 $defaults = array(
 'height'      => 100,
 'width'       => 400,
 'flex-height' => true,
 'flex-width'  => true,
 'header-text' => array( 'site-title', 'site-description' ),
'unlink-homepage-logo' => true, 
 );
 add_theme_support( 'custom-logo', $defaults );
}
add_action( 'after_setup_theme', 'themename_custom_logo_setup' );



function getchilds($items, $parentid)
{
    $childs = array();

    foreach ($items as $item) {
        if ($item->menu_item_parent == $parentid) {
            array_push($childs, $item);
        }
    }
    return $childs;
}

function create_ogani_menu()
{
//wp_nav_menu( array( 'theme_location' => 'main-menu' ) );
    $locations = get_nav_menu_locations();
    $menu = wp_get_nav_menu_object( $locations['main-menu'] );

    $menu_items = wp_get_nav_menu_items($menu->term_id);

    //var_dump($locations);
    $i = 0;

    foreach ($menu_items as $item) {
       // var_dump($item);
        if ($item->menu_item_parent != "0") {
            continue;
        }

        $cls = "";
        if ($i == 0) {
            $cls = "active";
        }

        $bachay =  getchilds($menu_items, $item->ID);                                
      
        if (count($bachay) > 0) {
            echo  '<li class="' . $cls .'"><a href="#">'.$item->title.'</a>';
            echo '<ul class="header__menu__dropdown">';
                foreach ($bachay as $bachaitem) {
                    echo '<li><a href="./shop-details.html">' . $bachaitem->title . '</a></li>';
                }
            echo '</ul></li>';
        }
        else
        {
            echo  '<li class="' . $cls .'"><a href="'. $item->url .'">'.$item->title.'</a></li>';
        }
        
        $i++;
    }

}                          



function my_register_sidebars() {
    /* Register the 'primary' sidebar. */
    register_sidebar(
        array(
            'id'            => 'primary',
            'name'          => __( 'Ogani Departments Menu Sidebar' ),
            'description'   => __( 'A short description of the sidebar.' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3="widget-title">',
            'after_title'   => '</h3>',
        )
    );
    /* Repeat register_sidebar() code for additional sidebars. */
}
add_action( 'widgets_init', 'my_register_sidebars' );




function wpdocs_my_search_form( $form ) {
    
}
add_filter( 'get_search_form', 'wpdocs_my_search_form' );


/**
 * 
 */
class AccuWeatherWidget extends WP_Widget
{
    
    public function __construct()
    {
         parent::__construct(
            'AccuWeatherWidget', // Base ID
            'AccuWeatherWidget', // Name
            array( 'description' => __( 'A AccuWeatherWidget Widget', 'text_domain' ), ) // Args
        );
    }

    public function form( $instance ) {
        // outputs the options form in the admin
    }
 
    public function update( $new_instance, $old_instance ) {
        // processes widget options to be saved
    }

    public function widget($args, $instance)
    {
         ?>
         <img src="" id="ci"  style="width: 200px" alt="" srcset="">
        <div id="temp">

        </div>    
        <div id="cloudy">
                
        </div>

         <script>
            function GetResponseFromAPI(params) {
                var xhr = new XMLHttpRequest();
                xhr.open("get", "http://dataservice.accuweather.com/forecasts/v1/daily/1day/261158?apikey=4KaHLFlTiGQesbYbGzFJJnFBFsMyGogn");
                xhr.onload = function(resp)
                {   
                    console.log(xhr.response);
                    var forecast = JSON.parse(xhr.response).DailyForecasts[0];

                    var calcius = Math.ceil((parseInt(forecast.Temperature.Maximum.Value) - 32)  * 5/9);


                    document.getElementById("temp").innerHTML = "Karachi Temperature: " + calcius + " &#8451;";  
                    document.getElementById("cloudy").innerHTML = forecast.Day.IconPhrase;  

                  document.getElementById("ci").src = "https://www.accuweather.com/images/weathericons/"+ forecast.Day.Icon +".svg";
                };
                xhr.send();
            }
            GetResponseFromAPI();
        </script>

        <?php
    }
}

add_action( 'widgets_init', 'register_AccuWeatherWidget' );
 
function register_AccuWeatherWidget() {
    register_widget( 'AccuWeatherWidget' );
}

?>



