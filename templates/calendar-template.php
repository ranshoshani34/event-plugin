<?php
/*Template Name: Calendar*/

get_header();
query_posts(array(
    'post_type' => 'event'
)); ?>

<?php

class Event {
    public $date;
    public $permalink;
    public $title;
    public $is_weekly;

    /**
     * @param $date
     * @param $permalink
     * @param $title
     */
    public function __construct($date, $permalink, $title, $is_weekly = false)
    {
        $this->date = $date;
        $this->permalink = $permalink;
        $this->title = $title;
        $this->is_weekly = $is_weekly;
    }

    /**
     * @return mixed
     */
    public function get_date()
    {
        return $this->date;
    }

    public function generate_HTML_link(){
        return "<a href='{$this->permalink}'>{$this->title}</a>";
    }

    public function is_weekly(){
        return $this->is_weekly;
    }
}

$events = array();

while (have_posts()) : the_post();
    $event_start_date = get_post_meta( $post->ID, 'event-start-date', true );
    $event_is_weekly = get_post_meta( $post->ID, 'event-weekly', true );
    echo date('d m y', $event_start_date);
    //echo $event_is_weekly;
    $events[] = new Event($event_start_date, get_permalink($post), get_the_title($post), $event_is_weekly);
endwhile;

/* draws a calendar */
function draw_calendar($month,$year, $events){

    /* draw table */
    $calendar = '<table class="calendar">';

    /* table headings */
    $headings = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
    $calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'
        . implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';

    /* days and weeks vars now ... */
    $running_day = date('w',mktime(0,0,0,$month,1,$year));
    $num_days_in_month = date('t',mktime(0,0,0,$month,1,$year));
    $days_in_this_week = 1;
    $day_counter = 0;
    $dates_array = array();

    /* row for week one */
    $calendar .= '<tr class="calendar-row">';

    /* print "blank" days until the first of the current week */
    for($x = 0; $x < $running_day; $x++):
        $calendar .= '<td class="calendar-day-np"> </td>';
        $days_in_this_week++;
    endfor;

    /* keep going with days.... */
    for($list_day = 1; $list_day <= $num_days_in_month; $list_day++) {
        $current_date = mktime(0, 0, 0, $month, $list_day, $year);
        $calendar .= '<td class="calendar-day">';
        /* add in the day number */
        $calendar .= '<div class="day-number">' . $list_day . '</div>';

        $filtered_array = array_filter($events, function($event) use ($current_date) {
            if ($event->is_weekly()){
                return date('w', $event->get_date()) === date('w', $current_date)   &&
                    $event->get_date() <= $current_date;
            }

            return $event->get_date() === $current_date;
        });

        foreach ($filtered_array as $event){
            $calendar .= $event->generate_HTML_link() . '<br>';
        }

        $calendar .= str_repeat('<p> </p>', 2);

        $calendar .= '</td>';
        if ($running_day == 6):
            $calendar .= '</tr>';
            if (($day_counter + 1) != $num_days_in_month):
                $calendar .= '<tr class="calendar-row">';
            endif;
            $running_day = -1;
            $days_in_this_week = 0;
        endif;
        $days_in_this_week++;
        $running_day++;
        $day_counter++;
    }

    /* finish the rest of the days in the week */
    if($days_in_this_week < 8):
        for($x = 1; $x <= (8 - $days_in_this_week); $x++):
            $calendar.= '<td class="calendar-day-np"> </td>';
        endfor;
    endif;

    /* final row */
    $calendar.= '</tr>';

    /* end the table */
    $calendar.= '</table>';

    /* all done, return result */
    return $calendar;
}

/* sample usages */
echo '<h2>' . date('F') .  ' 2021</>';
echo draw_calendar(date('m'), date('Y'), $events);

get_footer();
?>

