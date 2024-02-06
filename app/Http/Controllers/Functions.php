<?php

namespace App\Http\Controllers;

use App\Models\ManufactureProducts;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ManufactureSettings;

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

class Functions extends Controller
{

    static function fix_weighed_items()
    {
        $items = ManufactureProducts::get();
        $log = '';
        foreach ($items as $item) {
            if (DefaultsController::unit_measure_weighed[$item['unit_measure']]) {
                if ($item['weighed_product'] == 0) {
                    ManufactureProducts::where('id', $item['id'])->update(['weighed_product' => 1]);
                    $log .= "{$item['code']} set to weighed product. ";
                }
            } else {
                if ($item['weighed_product'] == 1) {
                    ManufactureProducts::where('id', $item['id'])->update(['weighed_product' => 0]);
                    $log .= "{$item['code']} set to unweighed product. ";
                }
            }
        }
        return $log;
    }

    static function negate($number)
    {
        if ($number > 0) {
            return -abs($number);
        } else if ($number < 0) {
            return abs($number);
        } else {
            return 0;
        }
    }

    static function get_doc_number($type)
    {
        switch ($type) {
            case 'batch':
                $settings = ManufactureSettings::first();

                DB::table('manufacture_settings')->update(['batch_number' => DB::raw("@doc_number := batch_number+1")]);
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

            case 'jobcard':
                $settings = ManufactureSettings::first();

                DB::table('manufacture_settings')->update(['jobcard_number' => DB::raw("@doc_number := jobcard_number+1")]);
                $number = DB::select(DB::raw("select @doc_number as number"));
                $number = $number[0]->number;

                $length = strlen($number);
                if ($length < $settings->jobcard_digits) {
                    $digits = $settings->jobcard_digits - $length;

                    for ($a = 1; $a <= $digits; $a++) {
                        $number = "0" . $number;
                    }
                }

                $number = $settings->jobcard_prefix . $number;

                return $number;

                break;

            case 'dispatch':
                $settings = ManufactureSettings::first();

                DB::table('manufacture_settings')->update(['dispatch_number' => DB::raw("@doc_number := dispatch_number+1")]);
                $number = DB::select(DB::raw("select @doc_number as number"));
                $number = $number[0]->number;

                $length = strlen($number);
                if ($length < $settings->batch_digits) {
                    $digits = $settings->batch_digits - $length;

                    for ($a = 1; $a <= $digits; $a++) {
                        $number = "0" . $number;
                    }
                }

                $number = $settings->dispatch_prefix . $number;

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

    static function validDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }

    static function printPDF($pdf_html, $pdf_filename = 'temp', $pdf_save = false, $pdf_open = false, $pdf_orientation = 'P', $pdf_page = 'A4', $stylesheet = '')
    {
        global $error;

        if (strlen($pdf_page) == 0) {
            $pdf_page = 'A4';
        }
        if (strlen($pdf_orientation) == 0) {
            $pdf_orientation = 'P';
        }
        if (strlen($pdf_filename) == 0) {
            $pdf_filename = 'temp';
        }
        if (!$pdf_open && !$pdf_save) {
            $pdf_open = true;
        }


        if (strlen($pdf_html) > 0) {
            //require_once('assets/html2pdf/html2pdf.class.php');
            try {

                $html2pdf = new Html2Pdf($pdf_orientation, $pdf_page, 'en', true, 'UTF-8');
                if (strlen($stylesheet) > 0) {
                    if (file_exists($stylesheet)) {
                        $style = file_get_contents($stylesheet);
                        $html2pdf->writeHTML("<style>" . $style . "</style>");
                    } else {
                        //$_SESSION['error'] = "Could not find stylesheet: " . $stylesheet;
                    }
                }

                $html2pdf->writeHTML($pdf_html);
                unset($pdf_html);


                if ($pdf_open) {
                    $html2pdf->Output("$pdf_filename.pdf");
                }

                if ($pdf_save) {
                    $html2pdf->Output("$pdf_filename.pdf", "D");
                }
            } catch (Html2PdfException $e) {
                echo  $e;
                exit;
            }
        }
    }
}
