<?php
namespace System\Source\Classes\output;

use Asset\Skins\spectre\Header;

class View
{
    public $layout = "spectre";

    public $tpl;

    public $title = "Главная";

    public $html_main;

    public $cfg;

    public $global;

    public $app_data;

    public $routes;

    public $menu = "";

    public function setAppData( $routes, $apps )
    {
        $this->data = $routes;

        $this->app_data = $apps;
    }

    public $offset;
    protected $offset_set;
    public $time_options = array();

    public function __construct()
    {
        $config = require root_path . "/conf_global.php";

        $this->cfg = $config;

        $this->time_options = array( 'JOINED' => '%d-%B %y',
            'SHORT'  => '%b %d %Y %H:%M',
            'LONG'   => '%d %B %Y - %H:%M',
            'TINY'   => '%d %b %Y - %H:%M',
            'DATE'   => '%d %b %Y',
            'SHORT2' => '%d %B %Y',
            'TIME'   => '%H:%M',
            'ACP'    => '%d %B %Y, %H:%M',
            'ACP2'   => '%d %B %Y, %H:%M',
            'YMD'    => '%Y-%m-%d',
        );
    }

    /**
     * @return Load Main Wrapper
     */
    public function globalContainer()
    {
        $tut = new \Asset\Skins\spectre\Header($this->data, $this->app_data);
        $this->global = $tut->wrapper();
    }

    /**
     * @return  Site Url adress
     */
    public function getUrl()
    {
        return $this->cfg['site_url'];
    }

    public function fetchMenu( $active_app, $apps )
    {
        $active = "";
        $lng = require root_path."/language/ru/menu.php";
        $menu = $this->cfg['installed_app'];
        $lnk = "";
        foreach ( $menu as $item ) {
            if( $active_app == $item['dir'] ) {
                $active = " class='active'";
            } else {
                $active = "";
            }

            $lnk .= <<<HTML
                            <li{$active}>
                                <a href="{$item['url']}">{$lng["menu_".$item['dir']]}</a>
                            </li>\n
HTML;
        }
        return $lnk;
    }

    /**
     * @param $name
     * @desc Load skin File
     */
    public function loadLayout($name )
    {
        $path = "Asset\Skins\\".$this->layout."\\".$name;
        if ( class_exists( $path ) ) {
            return new $path( $this->data, $this->app_data );
        } else {
            throw new \Exception( "Не удалось загрузить шаблон: <b>{$name}</b>", 404 );
        }
    }

    /**
     * @return HTML view
     */
    public function render() {
        $html			= str_replace('<%CONTENT%>',  $this->html_main, $this->global );
        $html           = str_replace( "<%MENU%>", $this->fetchMenu( $this->data['app'], $this->app_data ), $html );
        $html           = str_replace( "<%title%>", $this->title, $html );
        print $html;
    }

    public function getCss()
    {
        $handle = opendir(root_path.'/Asset/Skins/spectre/css');
        $link = "";

        /* Именно этот способ чтения элементов каталога является правильным. */
        while (false !== ($file = readdir($handle))) {
            if( substr( $file, -3 ) == "css" ) {
                $link .= "    <link href='Asset/Skins/" . $this->layout . "/css/" . $file . "' rel='stylesheet'>\n";
            }
        }

        closedir($handle);
        return $link;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Formats the current timestamp to make it read nicely
     *
     * @access	public
     * @param	integer	[$ts]			Timestamp to format, $this->timestamp used if none specified
     * @param	string	[$format]		Type of formatting to use: short or long
     * @param	bool	[$relative]		Determines if date will be displayed in relative format
     * @return	string
     */
    public function formatTime( $ts=0, $format='short', $relative=1 )
    {
        return $this->getDate( $ts, strtoupper( $format ), $relative ? 0 : 1 );
    }

    /**
     * Generate Human formatted date string
     * Return a date or '--' if the date is undef.
     * We use the rather nice gmdate function in PHP to synchronise our times
     * with GMT. This gives us the following choices:
     * If the user has specified a time offset, we use that. If they haven't set
     * a time zone, we use the default board time offset (which should automagically
     * be adjusted to match gmdate.
     *
     * @access	public
     * @param	integer		Unix date
     * @param	method		LONG, SHORT, JOINED, TINY
     * @param	integer		Do not use relative dates
     * @param	integer		Use fully relative dates
     * @return	string		Parsed time
     * @since	2.0
     */
    public function getDate($date, $method, $norelative=0, $full_relative=1, $overrideOffset=NULL)
    {
        //-----------------------------------------
        // INIT
        //-----------------------------------------

        $format = '';

        //-----------------------------------------
        // Manual format?
        //-----------------------------------------

        if ( empty($method) )
        {
            $format = $this->time_options['LONG'];
        }
        else if ( ! in_array( strtoupper($method), array_keys( $this->time_options ) ) )
        {
            if ( preg_match( '#^manual\{([^\{]+?)\}#i', $method, $match ) )
            {
                $format = $match[1];

                //-----------------------------------------
                // Shut off relative dates if using manual{} tag
                //-----------------------------------------

                $norelative	= true;
            }
            else
            {
                $format = $this->time_options['LONG'];
            }
        }
        else
        {
            $format = str_replace( "&#092;", "\\", $this->time_options[ strtoupper( $method ) ] );
        }

        if( strpos( $date, "custom" ) !== false )
        {
            if( preg_match( "/{custom:(.+?)}/i", $date, $matches ) )
            {
                if( $matches[1] )
                {
                    if( ! preg_match( "#^[0-9]{10}$#", $matches[1] ) )
                    {
                        $_time = strtotime( $matches[1] );

                        if ( $_time === FALSE OR $_time == -1 )
                        {
                            $date = 0;
                        }
                        else
                        {
                            $date = $_time;
                        }
                    }
                    else
                    {
                        $date	= $matches[1];
                    }
                }
            }
        }

        if ( ! $date )
        {
            return '--';
        }

        if ($this->offset_set == 0 OR ! $this->today_time)
        {
            // Save redoing this code for each call, only do once per page load

            $this->offset = $this->getTimeOffset();

            if ( 1 )
            {
                $this->today_time     = $this->_fix( @gmstrftime('%m,%d,%Y', ( time() + $this->offset ) ) );
                $this->yesterday_time = $this->_fix( @gmstrftime('%m,%d,%Y', ( ( time() - 86400 ) + $this->offset ) ) );
            }

            $this->offset_set = 1;
        }

        if ( $overrideOffset !== NULL )
        {
            $this->offset = $overrideOffset;
            $this->offset_set = 0;
        }

        //-----------------------------------------
        // Full relative?
        //-----------------------------------------

        if ( 1 == 3 )
        {
            $full_relative = 1;
        }

        //-----------------------------------------
        // Future date?
        //-----------------------------------------

        if( $date > ( time() + $this->offset ) )
        {
            $full_relative = 0;
        }

        //-----------------------------------------
        // FULL Relative
        //-----------------------------------------
        if ( $full_relative and ( $norelative != 1 ) )
        {
            $diff = time() - $date;

            if ( $diff < 3600 )
            {
                if ( $diff < 120 )
                {
                    return "минуту назад";
                }
                else
                {
                    return sprintf( "%s минут назад", intval($diff / 60) );
                }
            }
            else if ( $diff < 7200 )
            {
                return "час назад";
            }
            else if ( $diff < 86400 )
            {
                return sprintf( "%s часов назад", intval($diff / 3600) );
            }
            else if ( $diff < 172800 )
            {
                return "день назад";
            }
            else if ( $diff < 604800 )
            {
                return sprintf( "%s дней назад", intval($diff / 86400) );
            }
            else if ( $diff < 1209600 )
            {
                return "неделю назад";
            }
            else if ( $diff < 3024000 )
            {
                return sprintf( "%s недель назад", intval($diff / 604800) );
            }
            else
            {
                return $this->_fix( @gmstrftime($format, ($date + $this->offset) ) );
            }
        }

        //-----------------------------------------
        // Yesterday / Today
        //-----------------------------------------

        else if ( 1 and ( $norelative != 1 ) )
        {
            $this_time = $this->_fix( @gmstrftime( '%m,%d,%Y', ($date + $this->offset) ) );

            //-----------------------------------------
            // Use level 2 relative?
            //-----------------------------------------

            if ( 1 == 2 AND ($date <= time()) )
            {
                $diff = time() - $date;

                if ( $diff < 3600 )
                {
                    if ( $diff < 120 )
                    {
                        return "минуту назад";
                    }
                    else
                    {
                        return sprintf( "%s минут назад", intval($diff / 60) );
                    }
                }
            }

            //-----------------------------------------
            // Still here?
            //-----------------------------------------

            if ( $this_time == $this->today_time )
            {
                return str_replace( '{--}', "Сегодня", $this->_fix( @gmstrftime("{--}, %H:%M", ($date + $this->offset) ) ) );
            }
            else if  ( $this_time == $this->yesterday_time )
            {
                return str_replace( '{--}', "Вчера", $this->_fix( @gmstrftime("{--}, %H:%M", ($date + $this->offset) ) ) );
            }
            else
            {
                return $this->_fix( @gmstrftime( $format, ($date + $this->offset) ) );
            }
        }

        //-----------------------------------------
        // Normal
        //-----------------------------------------

        else
        {
            return $this->_fix( @gmstrftime($format, ($date + $this->offset) ) );
        }
    }

    /**
     * Bug in strftime and locals can mean some UTF-8 chars are mangled
     * @link http://stackoverflow.com/questions/8993971/php-strftime-french-characters
     * @param	string
     * @return	string
     */
    private function _fix( $date )
    {

        if( strpos( strtolower( PHP_OS ), 'win' ) === 0 )
        {
            $date = iconv( 'windows-1251', utf-8, $date );
        }
        /* Reverted for http://community.invisionpower.com/resources/bugs.html/_/ip-board/problem-with-a-date-r40950
        if ( IPS_DOC_CHAR_SET == 'UTF-8' )
        {
            return utf8_encode( $date );
        }
        */
        return $date;
    }

    /**
     * Return current TIME (not date)
     *
     * @access	public
     * @param	integer		Unix date
     * @param	string		PHP strftime() formatting options
     * @return	string
     * @since	2.0
     */
    public function getTime($date, $method='%I:%M %p')
    {
        if ($this->offset_set == 0)
        {
            // Save redoing this code for each call, only do once per page load

            $this->offset = $this->getTimeOffset();

            $this->offset_set = 1;
        }

        return $this->_fix( @gmstrftime($method, ($date + $this->offset) ) );
    }

    /**
     * Returns the member's time zone offset
     *
     * @access	public
     * @param	bool	Ignore the ACP adjustment
     * @return	string
     * @since	2.0
     */
    public function getTimeOffset( $ignoreAdjustment=false )
    {
        $r = 0;

        $this->settings['time_offset']   = ( ! empty( $this->settings['time_offset'] ) ) ? $this->settings['time_offset'] : 0;
        $this->memberData['time_offset'] = ( isset( $this->memberData['time_offset'] ) ) ? $this->memberData['time_offset'] : null;

        $r = 3 * 3600;

        if ( 0 AND !$ignoreAdjustment )
        {
            $r += (4 * 60);
        }

        if ( !empty($this->memberData['dst_in_use']) )
        {
            $r += 3600;
        }

        return $r;
    }


}