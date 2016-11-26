<?php

namespace Drupal\form_validation_module_refactor_srp\Model;

class QuestionAndAnswerValidator implements QuestionAndAnswerValidatorInterface
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
    else 
    {
      // 5
      if (!$question_and_answer_object->validateAnswer($answer_to_validate))
      {
        $error_message = "You entered an incorrect answer to the question you were trying to guess.";
      }
      
      // 4
      if (!empty($error_message)
          && $this->checkAnswerIsCorrectForOtherThanSpecifiedQuestion($question_key, $answer_to_validate))
      {
        $error_message = "You entered an incorrect answer to the question you were trying to guess, "
          . "but the answer was ironically correct for a different question. Change the question and then click 'guess again'!";
      }
    }

    if (empty($error_message))
      return true;
    else
      return $error_message;
  }
  
  protected function getQuestionAndAnswerForKey($key)
  {
    $questions = $this->getTextOfAllQuestions();
    $answers = $this->getTextOfAllAnswers();
    
    if (!isset($questions[$key]))
      throw new QuestionNotFoundException($key);
    
    if (!isset($answers[$key]))
      throw new AnswerNotFoundException($key);
    
    $question = $questions[$key];
    $answer = $answers[$key];
    
    $question_and_answer_object = new QuestionAndAnswer($key, $question, $answer);
    return $question_and_answer_object;
  }
  
  protected function checkAnswerIsCorrectForOtherThanSpecifiedQuestion($question_key, $answer_to_validate)
  {
    $answer_is_correct_for_other_than_specified_question = false;
    $all_question_keys_except_parameter_question_key = $this->getAllQuestionKeysExcept($question_key);

    foreach ($all_question_keys_except_parameter_question_key as $current_question_key)
    {
      $question_and_answer_object = $this->getQuestionAndAnswerForKey($current_question_key);
      
      if ($question_and_answer_object->validateAnswer($answer_to_validate))
      {
        $answer_is_correct_for_other_than_specified_question = true;
        break;
      }
    }
    
    return $answer_is_correct_for_other_than_specified_question;
  }
  
  protected function getAllQuestionKeysExcept($key_of_question_to_exclude)
  {
    $questions = $this->getTextOfAllQuestions();
    unset($questions[$key_of_question_to_exclude]);
    $all_question_keys_except_the_one_to_exclude = array_keys($questions);
    
    return $all_question_keys_except_the_one_to_exclude;
  }
  
  protected function getTextOfAllQuestions()
  {
    return [
      QuestionAndAnswerKeys::favorite_number_key => 'What\'s my favorite number?',
      QuestionAndAnswerKeys::favorite_color_key => 'What\'s my favorite color?',
      QuestionAndAnswerKeys::favorite_aircraft_make_key => 'What\'s my favorite aircraft make?',
    ];
  }

  protected function getTextOfAllAnswers()
  {
    return [
      QuestionAndAnswerKeys::favorite_number_key => '14.5',
      QuestionAndAnswerKeys::favorite_color_key => 'light cyan',
      QuestionAndAnswerKeys::favorite_aircraft_make_key => 'Cessna',
    ];
  }
}