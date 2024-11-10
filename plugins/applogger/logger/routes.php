<?php

use AppLogger\Logger\Models\Checkouts;

Route::post("/logcheckout",function(){
    $checkoutModel = new Checkouts();
    $checkoutModel->student_name = $_POST["student_name"];
    $checkoutModel->is_late = date("H") >= 8;
    $checkoutModel->save();

    $redirect = Redirect::to("/showcheckouts");

    return $redirect;
});

Route::any("/showcheckouts",function(){
    $distinctStudentsQuery = Checkouts::select("student_name")->distinct()->get();

    foreach ($distinctStudentsQuery as $key => $value) {
        echo "<a href='showcheckouts/{$value["student_name"]}'> " . htmlspecialchars($value["student_name"]) . " </a> \n<br>";
    }

    echo '<a href="/" style="position: relative;top: 20px;">go back</a>';
});

Route::any("/showcheckouts/{student_name?}/{raw?}",function($student_name = null,$raw=null){
    
    if ($student_name == null) {
        return Redirect::to("showcheckouts");
    }

    $checkoutsQuery = Checkouts::where("student_name", $student_name)->get()->reverse();

    if ($raw == "raw") {
        return $checkoutsQuery;
    }

    foreach ($checkoutsQuery as $key => $value) {
        $is_late_str = $value["is_late"] ? "late" : "on time";

        echo $key + 1 . ". Checked out at {$value["created_at"]} | {$is_late_str} \n <br>";
    }
    echo '<a href="/showcheckouts" style="position: relative;top: 20px;">go back</a>';
});