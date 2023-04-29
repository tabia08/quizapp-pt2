<?php

require "vendor/autoload.php";

session_start();

use App\QuestionManager;

$score = null;
$answers = [];
$results = [];
$i = 1;

try {
    $manager = new QuestionManager;
    $manager->initialize();

    if (!isset($_SESSION['answers'])) {
        throw new Exception('Missing answers');
    }

    $answers = $_SESSION['answers'];
    $score = $manager->computeScore($answers);
    $results = $manager->results($answers);

    $file = "Results.txt";
    $txt = fopen($file, "w") or die("Unable to open file!");

    $output = "Complete Name: ".$_SESSION['user_fullname']."\n";
    $output .= "Email: ".$_SESSION['email']."\n";
    $output .= "Birthdate: ".$_SESSION['birthday']."\n";
    $output .= "Score: ".$score." out of ".$manager->getQuestionSize()."\n";
    $output .= "Answers:\n\n";

    
foreach ($results as $answer) {
    if ($answer[1] == 1) {
        $output .= $i . ") " . $answer[0] . " (correct) \n";
    } else {
        $output .= $i . ") " . $answer[0] . " (incorrect) \n";
    }
    $i++;
}
    
    
    header('Content-Disposition: attachment; filename='.basename($file));
    fwrite($txt, $output);
    fclose($txt);
    readfile($file);
    exit;

} catch (Exception $e) {
    echo '<h1>An error occurred:</h1>';
    echo '<p>' . $e->getMessage() . '</p>';
    exit;
}

