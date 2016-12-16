<?php

namespace Drupal\Tests\form_validation_module_refactor_srp\Unit;

use Drupal\form_validation_module_refactor_srp\Form\GuessForm;
use Drupal\form_validation_module_refactor_srp\Model\QuestionAndAnswerKeys;
use Drupal\form_validation_module_refactor_srp\Model\QuestionAndAnswerValidator;

/**
 * @group form_validation_module_refactor_srp
 */
class GuessFormValidateMethodTestCase extends FormValidationUnitTestCase 
{
  const form_element_name_key = 'form_element_name_key';
  const form_element_default_value_key = 'form_element_default_value_key';
  const form_element_name_that_should_generate_error_message_key = 'form_element_name_that_should_generate_error_message_key';
  const expected_error_message_key = 'expected_error_message_key';

  protected $form_element_names_and_default_values;
  protected $fully_namespaced_form_class_to_test;
  protected $mock_question_and_answer_validator;
  
  protected function getFullyNamespacedFormClassToTest()
  {
    return 'Drupal\form_validation_module_refactor_srp\Form\GuessForm';
  }
  
  protected function getFormElementNamesAndDefaultValues()
  {
    $question_and_answer_validator = new QuestionAndAnswerValidator();
    $favorite_number_answer = $question_and_answer_validator
      ->getQuestionAndAnswerForKey(QuestionAndAnswerKeys::favorite_number_key)
      ->getAnswer();
    
    $form_element_names_and_default_values_for_test = [
      GuessForm::select_list_key => QuestionAndAnswerKeys::favorite_number_key,
      GuessForm::text_field_key => $favorite_number_answer
    ];
    
    return $form_element_names_and_default_values_for_test;
  }
  
  protected function getMethodsToNotMockExcludingValidateFormMethod()
  {
    return ['getQuestionSelectionList', 'getAnswerToQuestion'];
  }
  
  protected function getMockQuestionAndAnswerValidator()
  {
    $question_and_answer_validator = $this->getMockBuilder('\Drupal\form_validation_module_refactor_srp\Model\QuestionAndAnswerValidator')
      ->setMethods([]) // we want to mock ALL methods on this object (so pass empty array)
      ->getMock(); 
    
    $this->assertTrue($question_and_answer_validator instanceof \Drupal\form_validation_module_refactor_srp\Model\QuestionAndAnswerValidator);
    
    return $question_and_answer_validator;
  }
  
  protected function getFormConstructorArguments()
  {
    return [ $this->mock_question_and_answer_validator ];
  }
  
  public function setUp()
  {
    $this->mock_question_and_answer_validator = $this->getMockQuestionAndAnswerValidator();
  }
  
  public function testFormValidationNoErrorThrownForCorrectInput() {
    $form_element_names_and_input_values = [
      GuessForm::select_list_key => QuestionAndAnswerKeys::favorite_aircraft_make_key,
      GuessForm::text_field_key => 'Cessna'      
    ];
    
    $this->mock_question_and_answer_validator->expects($this->once())
      ->method('validateAnswerToQuestion')
      ->with(QuestionAndAnswerKeys::favorite_aircraft_make_key, 'Cessna')
      ->will($this->returnValue(true));

    $this->assertFormElementDoesNotCausesErrorMessageWithValidFormInput($form_element_names_and_input_values);
  }
  
  public function testFormValidationCallsSetErrorByNameWhenValidatorReturnsError() {
    $form_element_names_and_input_values = [
      GuessForm::select_list_key => QuestionAndAnswerKeys::favorite_aircraft_make_key,
      GuessForm::text_field_key => 'wrong answer'      
    ];

    $this->mock_question_and_answer_validator->expects($this->once())
      ->method('validateAnswerToQuestion')
      ->with(QuestionAndAnswerKeys::favorite_aircraft_make_key, 'wrong answer')
      ->will($this->returnValue("This is an error message"));

    $expected_error_message = "This is an error message";

    $this->assertFormElementCausesErrorMessageWithInvalidFormInput(GuessForm::text_field_key, $expected_error_message, $form_element_names_and_input_values);  
  }

  public function testFormValidationNoQuestionSelected() {
    $form_element_names_and_input_values = [
      GuessForm::select_list_key => GuessForm::question_selection_default_key
    ];

    $expected_error_message = "Please select a question to guess an answer!";

    $this->assertFormElementCausesErrorMessageWithInvalidFormInput(GuessForm::select_list_key, $expected_error_message, $form_element_names_and_input_values);
  }
  
  public function testFormValidationDefaultTextFieldNotChanged() {
    $form_element_names_and_input_values = [
      GuessForm::text_field_key => GuessForm::text_field_default_value
    ];

    $expected_error_message = "You should remove the default guess and enter your own.";

    $this->assertFormElementCausesErrorMessageWithInvalidFormInput(GuessForm::text_field_key, $expected_error_message, $form_element_names_and_input_values);
  }  
}