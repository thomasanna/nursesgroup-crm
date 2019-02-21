<?php

namespace App\Helpers;
use Log;
class Direction
{
    public static function getCoordinates($city)
    {
        $address = urlencode($city);
        $url = "https://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=India&key=AIzaSyAsw26FlFh8cteBOsGNT0Rj3NPz5v5i_6Y";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response);

        $status = $response_a->status;

        if ( $status == 'ZERO_RESULTS' )
        {
            return ['status'=>300,'message'=>'No cordinates found..'];
        }
        if ( $status == 'OVER_QUERY_LIMIT' )
        {
            return ['status'=>250,'message'=>'You are exceeded daily limit'];
        }
        else
        {
            $return = ['status'=>200,'lat' => $response_a->results[0]->geometry->location->lat, 'long' => $long = $response_a->results[0]->geometry->location->lng];
            return $return;
        }
    }

    public static function getDrivingDuration($lat1, $lat2, $long1, $long2)
    {
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=driving&language=pl-PL&key=AIzaSyAsw26FlFh8cteBOsGNT0Rj3NPz5v5i_6Y";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response, true);
        $status = $response_a['status'];
        if ( $status == 'NOT_FOUND' )
        {
            return ['status'=>300];
        }
        if($response_a['rows'][0]['elements'][0]['status']=="NOT_FOUND"){
          return ['status'=>300];
        }
        $dist = $response_a['rows'][0]['elements'][0]['distance']['value'];
        $time = $response_a['rows'][0]['elements'][0]['duration']['value'];

        return ['status'=>200, 'time' => $time];
    }

    public static function getDrivingDistance($lat1, $lat2, $long1, $long2)
    {
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=driving&language=pl-PL&key=AIzaSyAsw26FlFh8cteBOsGNT0Rj3NPz5v5i_6Y";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response, true);
        $status = $response_a['status'];
        if ( $status == 'NOT_FOUND' )
        {
            return ['status'=>300];
        }
        if($response_a['rows'][0]['elements'][0]['status']=="NOT_FOUND"){
        return ['status'=>300];
        }
        $dist = $response_a['rows'][0]['elements'][0]['distance']['value'];
        $time = $response_a['rows'][0]['elements'][0]['duration']['value'];

        return ['status'=>200,'distance' => $dist ];
    }

}
