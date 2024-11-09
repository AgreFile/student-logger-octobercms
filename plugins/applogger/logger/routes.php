<?php

use AppLogger\Logger\Models\Checkouts;

Route::post("/logcheckout",function(){
    $checkoutModel = new Checkouts();
    $checkoutModel->studentName = $_POST["studentName"];
    $checkoutModel->save();

    $redirect = Redirect::to("/showcheckouts");

    return $redirect;
});

Route::any("/showcheckouts",function(){
    $uniqueStudents = Checkouts::select("studentName")->distinct()->get();

    foreach ($uniqueStudents as $key => $value) {
        echo "<a href='showcheckouts/{$value["studentName"]}'> {$value["studentName"]} </a> \n<br>";
    }

    echo '<a href="/" style="position: relative;top: 20px;">go back</a>';
});

Route::any("/showcheckouts/{studentName?}",function($studentName = null){
    
    if ($studentName == null) {
        return Redirect::to("showcheckouts");
    }

    $checkoutsQuery = Checkouts::where("studentName", $studentName)->get();

    foreach ($checkoutsQuery as $key => $value) {
        echo $value["created_at"] . "<br>";
    }
    echo '<a href="/showcheckouts" style="position: relative;top: 20px;">go back</a>';
});