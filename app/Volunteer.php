<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Malhal\Geographical\Geographical;

class Volunteer extends Model
{
    use Geographical;

    protected $fillable = [
        "user_id",
        "name",
        "email",
        "phone",
        "city",
        "county",
        "address",
        "neighborhood",
        "activation_area",
        "involvement_type",
        "availability",
        "availability_details",
        "observations",
        "geojson",
        "ci",
        "cnp",
        "loc_domiciliu",
        "str_domiciliu",
        "nr_domiciliu",
        "ap_domiciliu",
        "ip_acord",
        "data_acord",
        "involvement_direction",
        "has_car",
        "car_plates",
        "type",
        "ip_acord_voluntariat",
        "data_acord_voluntariat",
        "status",
        "screening_questions",
        "transportation_options",
        "age_range",
        "address_no",
        "address_bl",
        "address_sc",
        "address_ap",
        "acord_termeni",
        "verified",
        "has_traning",
        "longitude",
        "latitude"
    ];

    protected $casts = [

        "geojson" => "json",
        "screening_questions" => "json"
    ];

    public function user()
    {

        return $this->belongsTo(\App\Laravue\Models\User::class);
    }
}
