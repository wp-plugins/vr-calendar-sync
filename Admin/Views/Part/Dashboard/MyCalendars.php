<h2>
    My Calendars <a href="<?php echo admin_url('admin.php?page='.VRCALENDAR_PLUGIN_SLUG.'-add-calendar') ?>" class="add-new-h2">Add new</a>
</h2>
<form id="my-calendars" name=my-calendars" method="post" action="">
    <?php
    $VRCalendarTable = new VRCalendarTable();
    $VRCalendarTable->prepare_items();
    $VRCalendarTable->display();
    $VRCalendarTable->process_bulk_action();
    ?>
</form>
<?php
class VRCalendarTable extends WP_List_Table
{
    function __construct()
    {
        parent::__construct();
    }
    /**
     * @Method name  column_default
     * @Params       $cal,$column_name
     * @description  display static column name and corrosponding value
     */
    function column_default($cal, $column_name)
    {
        /* display all dynamic data from database  */
        switch ($column_name)
        {
            case 'title':
                echo  $cal['calendar_name'];
                break;
            case 'calendar_shortcode':
                echo  '[vrcalendar id="'.$cal['calendar_id'].'" /]';
                break;
            case 'author':
                echo   get_the_author_meta( 'display_name', $cal['calendar_author_id'] );
                break;
            case 'last_synchronized':
                echo  date('F d, Y \a\t h:i a', strtotime($cal['calendar_last_synchronized']));
                break;
            case 'created_on':
                echo  date('Y-m-d', strtotime($cal['calendar_created_on']));
                break;
            default:
                return $cal->$column_name;
        }
    }
    /**
     * @Method name  column_name
     * @Params       $cal
     * @description  display static column name and corrosponding value
     */
    function column_title($cal)
    {
        $actions = array(
            'edit' => '<a href="' .admin_url('admin.php?page='.VRCALENDAR_PLUGIN_SLUG.'-add-calendar&cal_id='.$cal['calendar_id']). '">Edit</a>',
            'delete' => '<a href="' .admin_url('admin.php?page='.VRCALENDAR_PLUGIN_SLUG.'-dashboard&vrc_cmd=VRCalendarAdmin:deleteCalendar&cal_id='.$cal['calendar_id']). '">Delete</a>',
            'sync' => '<a href="' .admin_url('admin.php?page='.VRCALENDAR_PLUGIN_SLUG.'-dashboard&vrc_cmd=VRCalendarAdmin:syncCalendar&cal_id='.$cal['calendar_id']). '">Sync</a>'
        );
        return $cal['calendar_name'].$this->row_actions($actions) ;
    }

    /**
     * @Method name  column_cb
     * @Params       $cal
     * @description  display check box for all Calendar data value
     */
    function column_cb($cal)
    {
        return '<input type="checkbox" name="check[]" value="'.$cal['calendar_id'].'" />';
    }

    /**
     * @Method name  get_columns
     * @description  display head tr for table
     */
    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox"/>',
            'title' => 'Title',
            'calendar_shortcode' =>'Calendar Shortcode',
            'author'=> 'Author',
            'last_synchronized'=> 'Last Sync',
            'created_on' =>'Date'
        );
        return $columns;
    }

    function process_bulk_action()
    {
        extract($_REQUEST);
        if(isset($check))
        {
            if( 'trash'===$this->current_action() )
            {
                $msg = 'delete';
                global $wpdb;
                $calendar_table = $wpdb->prefix."vrcalandar";
                foreach($check as $cal_id)
                {
                    $cal_query = "delete  FROM ".$calendar_table." where calendar_id='".$cal_id."' ";
                    $wpdb->query($cal_query);
                }
                //$redirectTo = admin_url().'admin.php?page=vr-calendar/includes/controller.php&msg='.$msg;
                //wp_redirect($redirectTo);
                exit;
            }
        }
    }

    /**
     * @Method name  get_sortable_columns
     * @description  implement sorting on elments included in $sortable_columns array
     */
    function get_sortable_columns()
    {
        $sortable_columns = array(
            'title' => array(
                'calendar_name',
                false
            ),
            'created_date' => array(
                'created_date',
                false
            )
        );
        return $sortable_columns;
    }
    /**
     * @Method name  get_bulk_actions
     * @description  implement bulk action included in $actions array
     */
    function get_bulk_actions()
    {
        $actions = array(
            'trash' => 'Trash'
        );
        return $actions;
    }

    /**
     * @Method name  prepare_items
     * @description  ready data to display
     */
    function prepare_items()
    {
        global $wpdb;
        $calendar_table = $wpdb->prefix."vrcalandar";
        $cal_per_page   = 10;
        //retrive all calendar  from database

        $cal_query = "SELECT * FROM {$calendar_table}";
        $calendar_data = $wpdb->get_results($cal_query, ARRAY_A);
        $columns   = $this->get_columns();
        $sortable  = $this->get_sortable_columns();
        $this->process_bulk_action();
        $this->_column_headers = array(
            $columns,
            array(),
            $sortable
        );

        //pagging code starts from here
        $current_page = $this->get_pagenum();
        $total_cal = count($calendar_data);
        $calendar_data = array_slice(
            $calendar_data,(
                ($current_page-1)*$cal_per_page
            ),$cal_per_page
        );
        $this->items = $calendar_data;

        $this->set_pagination_args(
            array(
                'total_items'=>$total_cal,
                'per_page'=> $cal_per_page,
                'total_pages'=>ceil($total_cal/$cal_per_page)
            )
        );
        //pagging code ends from here
    }

    /**
     * @Method name  sort_data
     * @params $a $b
     * @description  sort product member data
     */
    public function sort_data($a, $b)
    {
        // Set defaults
        $orderby = 'calendar_name';
        $order   = 'asc';
        // If orderby is set, use this as the sort column
        if (!empty($_GET['orderby']))
        {
            $orderby = $_GET['orderby'];
        }
        // If order is set use this as the order
        if (!empty($_GET['order']))
        {
            $order = $_GET['order'];
        }
        $result = strnatcmp($a->$orderby, $b->$orderby);
        if ($order =='asc')
        {
            return $result;
        }
        return -$result;
    }
}