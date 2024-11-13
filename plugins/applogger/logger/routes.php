<?php
date_default_timezone_set("Europe/Prague");

use AppLogger\Logger\Models\Checkouts;
use Carbon\Carbon;

// REVIEW - v routes.php by si mal mať iba endpoint definície, takáto funkcia by mala byť skôr v modeli (prípadne v service class-e). Tým pádom by si mohol robno urobiť $student->getCheckouts() napr.
// V Leveli 2 uvidíš že sa to dá robiť ešte lepšie, a to cez relations :DD
function scopeByStudent($student_name){
    return Checkouts::where("student_name", $student_name)->get();
}

Route::post("/logcheckout",function(){
    $checkoutModel = new Checkouts();
    $student_name = filter_input(INPUT_POST, 'student_name', FILTER_SANITIZE_STRING); // REVIEW - Toto sa dá zjednodušiť na post('student_name'), je to to isté len o dosť prehľadnejšie
    if (!$student_name) {
        // REVIEW - Ak nastala chyba, mal by si hodiť exception, napr. takto: throw new Exception('Invalid student name', 400);
        return response()->json(['error' => 'Invalid student name'], 400);
    }
    $checkoutModel->student_name = $student_name;
    $checkoutModel->is_late = Carbon::now()->hour >= 8;
    $checkoutModel->save();

    return Redirect("/showcheckouts");
});

Route::any("/showcheckouts",function(){
    $distinctStudentsQuery = Checkouts::select("student_name")->distinct()->get();

    foreach ($distinctStudentsQuery as $index => $student) {
        echo "<a href='showcheckouts/{$student->student_name}'> " . htmlspecialchars($student->student_name) . " </a> \n<br>";
    }

    echo '<a href="/showallcheckouts/" style="position: relative;top: 20px;">show all checkouts</a><br>';
    echo '<a href="/"  style="position: relative;top: 20px;">go back</a>';
});

Route::any("/showallcheckouts/{raw?}",function($raw = null){
    if ($raw == "raw") {
        return Checkouts::select("*")->get();
    }

    $distinctStudentsQuery = Checkouts::select("student_name")->distinct()->get();

    foreach ($distinctStudentsQuery as $index => $student) {
        $studentCheckoutsQuery = scopeByStudent($student->student_name);

        echo htmlspecialchars($student->student_name) . " || amounts of time that the student checkedout: " . count($studentCheckoutsQuery)."<br>";
        echo "<ul>";
        foreach ($studentCheckoutsQuery as $index => $checkout) {
            $is_late_str = $checkout->is_late ? "late" : "on time";
            echo "<li>" . $index + 1 . ". {$checkout->created_at} | {$is_late_str} </li>";
        }
        echo "</ul>";
    }

    echo '<a href="/" style="position: relative;top: 20px;">go back</a>';
});

Route::any("/showcheckouts/{student_name?}/{raw?}",function($student_name = null,$raw=null){
    
    if ($student_name == null) {
        return Redirect::to("showcheckouts");
    }

    $checkoutsQuery = scopeByStudent($student_name)->reverse();
    
    if ($raw == "raw") {
        return $checkoutsQuery;
    }

    foreach ($checkoutsQuery as $key => $value) {
        $is_late_str = $value["is_late"] ? "late" : "on time";
        echo $key + 1 . ". Checked out at {$value["created_at"]} | {$is_late_str} \n <br>";
    }
    echo '<a href="/showcheckouts" style="position: relative;top: 20px;">go back</a>';
});