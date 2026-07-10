<?php
defined('BASEPATH') or exit('No direct script access allowed');

function formatOffset($offset)
{
    $hours = $offset / 3600;
    $remainder = $offset % 3600;
    $sign = $hours > 0 ? '+' : '-';
    $hour = (int) abs($hours);
    $minutes = (int) abs($remainder / 60);

    if ($hour == 0 and $minutes == 0) {
        $sign = ' ';
    }
    return $sign . str_pad($hour, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minutes, 2, '0');
}


function get_timezone()
{

    $list = DateTimeZone::listAbbreviations();
    $idents = DateTimeZone::listIdentifiers();

    $data = $offset = $added = array();
    foreach ($list as $abbr => $info) {
        foreach ($info as $zone) {
            if (
                !empty($zone['timezone_id'])
                and
                !in_array($zone['timezone_id'], $added)
                and
                in_array($zone['timezone_id'], $idents)
            ) {
                $z = new DateTimeZone($zone['timezone_id']);
                $c = new DateTime('', $z);
                $zone['time'] = $c->format('H:i a');
                $offset[] = $zone['offset'] = $z->getOffset($c);
                $data[] = $zone;
                $added[] = $zone['timezone_id'];
            }
        }
    }

    array_multisort($offset, SORT_ASC, $data);
    $options = array();
    foreach ($data as $key => $row) {
        $options[$row['timezone_id']] = $row['time'] . ' - '
            . formatOffset($row['offset'])
            . ' ' . $row['timezone_id'];
    }

    return $options;
}



function get_timezone_array()
{
    $idents = DateTimeZone::listIdentifiers(); // Get all timezone identifiers

    $data = [];
    foreach ($idents as $timezone) {
        $z = new DateTimeZone($timezone);
        $c = new DateTime('now', $z); // Get current time in the timezone
        $data[] = [
            'time' => $c->format('H:i a'),          // Formatted time
            'offset' => $z->getOffset($c),         // UTC offset
            'timezone_id' => $timezone,           // Timezone ID
        ];
    }

    // Sort timezones by offset
    usort($data, function ($a, $b) {
        return $a['offset'] <=> $b['offset'];
    });

    $options = [];
    foreach ($data as $key => $row) {
        $options[$key] = [
            $row['time'],                           // Current time
            formatOffset($row['offset']),          // Formatted offset
            $row['timezone_id'],                   // Timezone ID
        ];
    }

    return $options;
}

