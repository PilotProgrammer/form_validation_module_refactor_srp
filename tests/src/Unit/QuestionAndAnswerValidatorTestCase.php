<?php

namespace Drupal\Tests\form_validation_module_refactor_srp\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\form_validation_module_refactor_srp\Model\QuestionAndAnswerValidator;
use Drupal\form_validation_module_refactor_srp\Model\QuestionAndAnswerKeys;

/**
* @group form_validation_module_refactor_srp
*/
class QuestionAndAnswerValidatorTestCase extends UnitTestCase 
{
  public function getQuestionAndAnswerFacadeToTest()
  {
    $question_and_answer_facade = new QuestionAndAnswerValidator();
    return $question_and_answer_facade;
  }
  
  public function testValidateCorrectAnswerToQuestion() 
  {
    $question_and_answer_facade = $this->getQuestionAndAnswerFacadeToTest();
    $question_key = QuestionAndAnswerKeys::favorite_aircraft_make_key;
    $answer_to_validate = 'Cessna';
    
    $error_message_or_success = $question_and_answer_facade->validateAnswerToQuestion($question_key, $answer_to_validate);
    $this->assertTrue($error_message_or_success, "The validate method should return true for the correct answer.");
  }
  
  public function testValidateNoAnswerToQuestionGiven()
  {
    $question_and_answer_facade = $this->getQuestionAndAnswerFacadeToTest();
    $question_key = QuestionAndAnswerKeys::favorite_number_key;
    $answer_to_validate = '';
    $expected_error_message = "You didn't guess anything!";

    $error_message_or_success = $question_and_answer_facade->validateAnswerToQuestion($question_key, $answer_to_validate);
    $this->assertTrue($error_message_or_success === $expected_error_message, "The validate method should return an error message for incorrect input.");
  }
}
