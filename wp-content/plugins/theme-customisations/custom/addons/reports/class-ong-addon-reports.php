<?php

class Ong_Addon_Reports
{
    /**
     * Get a report from our reports subfolder.
     */
    public static function get_report($name)
    {
        $name = sanitize_title(str_replace('_', '-', $name));
        $class = 'Ong_Addon_Reports_' . str_replace('-', '_', $name);

        $report = new $class();
        $report->output_report();
    }
}
