<?php

require_once "db.php";
require_once "location.inc.php";

function get_rules_by_device_id($device_id)
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM rules WHERE device_id = ?');
    $stmt->execute([$device_id]);
    return $stmt->fetchAll();
}


function get_rules_by_id($id)
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM rules WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if ($row)
    {
        return $row;
    } else {
        return null;
    }
}

function insert_rule($device_id, $name, $lat, $lon, $radius, $max_speed)
{
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO rules (device_id, name, lat, lon, radius, max_speed)
        VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([$device_id, $name, $lat, $lon, $radius, $max_speed]);
}


// South latitudes are negative, east longitudes are positive
// calculate the distance between two points in meters
function distance($lat1, $lon1, $lat2, $lon2)
{

  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  return $dist * 60 * 1.1515 * 1609.344;
}

function get_matched_rules($device_id, $location, $tolerance = 200)
{
    $rules = get_rules_by_device_id($device_id);
    $matched_rules = [];
    foreach ($rules as $rule) {
        if (distance($location['lat'], $location['lon'], $rule['lat'], $rule['lon']) < ($rule['radius'] + $tolerance))
        {
            $matched_rules[] = $rule;
        }
    }
    return $matched_rules;
}

function check_location($device_id, $location_id)
{
    $location = get_location_by_id($location_id);

    if ($location)
    {
        var_dump(get_matched_rules($device_id, $location));
    }
}


 ?>
