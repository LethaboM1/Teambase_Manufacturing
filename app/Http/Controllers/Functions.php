<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ManufactureSettings;

class Functions extends Controller
{
    static function get_doc_number($type)
    {
        switch ($type) {
            case 'batch':
                $settings = ManufactureSettings::first();
                ManufactureSettings::get()->update(['batch_number' => DB::raw("@doc_number := batch_number+1")]);
                $number = DB::select(DB::raw("select @doc_number as number"));
                $number = $number[0]->number;

                $length = strlen($number);
                if ($length < $settings->batch_digits) {
                    $digits = $settings->batch_digits - $length;

                    for ($a = 1; $a <= $digits; $a++) {
                        $number = "0" . $number;
                    }
                }

                $number = $settings->batch_prefix . $number;

                return $number;

                break;
        }
    }

    static function validPassword($password)
    {
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);

        if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
            return false;
        } else {
            return true;
        }
    }
}
