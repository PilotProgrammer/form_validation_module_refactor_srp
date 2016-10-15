<?php

namespace Drupal\form_validation_module_refactor_srp\Model;

class QuestionAndAnswerValidator
{
  public function validateAnswerToQuestion($question_key, $answer_to_validate)
  {
    $error_message = "";
    $question_and_answer_object = $this->getQuestionAndAnswerForKey($question_key);
    
    // 1
    if (empty($answer_to_validate))
    {
      $error_message = "You didn't guess anything!";
    }
    else if (!$question_and_answer_object->validateAnswer($answer_to_validate))
    {
      $error_message = "You entered an incorrect answer to the question you were trying to guess.";
    }
    
    if (empty($error_message))
      return true;
    else
      return $error_message;
  }
  
  protected function getQuestionAndAnswerForKey($key)
  {
    $questions = [
      QuestionAndAnswerKeys::favorite_number_key => 'What\'s my favorite number?',
      QuestionAndAnswerKeys::favorite_color_key => 'What\'s my favorite color?',
      QuestionAndAnswerKeys::favorite_aircraft_make_key => 'What\'s my favorite aircraft make?',
    ];
    
    $answers = [
      QuestionAndAnswerKeys::favorite_number_key => '14.5',
      QuestionAndAnswerKeys::favorite_color_key => 'light cyan',
      QuestionAndAnswerKeys::favorite_aircraft_make_key => 'Cessna',
    ];
    
    if (!isset($questions[$key]))
      throw new QuestionNotFoundException($key);
    
    if (!isset($answers[$key]))
      throw new AnswerNotFoundException($key);
    
    $question = $questions[$key];
    $answer = $answers[$key];
    
    $question_and_answer_object = new QuestionAndAnswer($key, $question, $answer);
    return $question_and_answer_object;
  }
}