<?php

namespace Drupal\form_validation_module_refactor_srp\Model;

class QuestionAndAnswerValidator
{
  public function validateAnswerToQuestion($question_key, $answer_to_validate)
  {
    $error_message = "";
    
    if (empty($answer_to_validate))
      $error_message = "You didn't guess anything!";

    if (empty($error_message))
      return true;
    else
      return $error_message;
  }
}