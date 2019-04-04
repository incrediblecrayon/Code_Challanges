<?php

namespace App\Http\Controllers;
// use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Validator;

class SpeakNumberController extends Controller
{
    private $stringToSpeak = "";

    private $singleDigitSpoken = [
        "zero", //Just taking up the index 0,
        "one",
        "two",
        "three",
        "four",
        "five",
        "six",
        "seven",
        "eight",
        "nine"
    ];

    private $tenthDigitSpoken = [
        "zero", //Just taking up the index 0,
        "one", //Not being used,
        "twenty",
        "thirty",
        "fourty",
        "fifty",
        "sixty",
        "seventy",
        "eighty",
        "ninety"
    ];

    private $edgecaseSpoken = [
        'teens' => [
            "10" => 'ten',
            "11" => "eleven",
            "12" => "twelve",
            "13" => "thirteen",
            "14" => "fourteen",
            "15" => "fifteen",
            "16" => "sixteen",
            "17" => "seventeen",
            "18" => "eighteen",
            "19" => "nineteen",
        ]
    ];
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /** 
     * Return the english representaion of an int >= 9999
     * @param int $numberToSpeak What number we want to speak.
     */
    public function index(int $numberToSpeak)
    {
        $finalSpokenString = "";

        // Validation
        $validator = Validator::make(['numberToSpeak' => $numberToSpeak], [
            'numberToSpeak' => 'required|numeric|lte:9999'
        ]);

        if ($validator->fails()) {
            return response("Failed Validation");
        }

        // TODO - Check for zero.
        $intToMorph = $numberToSpeak;
        $thousands = floor($intToMorph/1000);
        if($thousands)
        {
            $finalSpokenString .= $this->singleDigitSpoken[$thousands] . " thousand ";
            $intToMorph = $intToMorph - ($thousands * 1000);
            // echo "$thousands thousand <br />";
        }

        $hundreds = floor($intToMorph/100);
        if($hundreds)
        {
            $finalSpokenString .= $this->singleDigitSpoken[$hundreds] . " hundred ";
            $intToMorph = $intToMorph - ($hundreds * 100);
            // echo "$hundreds hundred <br />";
        }

        $tens = floor($intToMorph/10);
        if($tens)
        {
            if($intToMorph >= 10 && $intToMorph < 20)
            {
                $finalSpokenString .= $this->edgecaseSpoken['teens'][$intToMorph];
                $intToMorph = 0;
            }else{
                $finalSpokenString .= $this->tenthDigitSpoken[$tens] . " ";
            }
            $intToMorph = $intToMorph - ($tens * 10);
        }

        if($intToMorph > 0)
        {
            $finalSpokenString .= $this->singleDigitSpoken[$intToMorph];
        }

        echo $finalSpokenString;

    }

}
