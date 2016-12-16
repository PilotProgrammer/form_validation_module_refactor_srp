<?php

namespace Drupal\form_validation_module_refactor_srp\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\form_validation_module_refactor_srp\Model\QuestionAndAnswerValidatorInterface;

class GuessForm extends FormBase 
{
  const select_list_key = 'select_list_key';
  const text_field_key = 'text_field_key';
  const submit_button_key = 'form_select_list_key';
  
  const question_selection_default_key = 'question_selection_default_key';
  const text_field_default_value = 'Enter your guess here';
  
  protected $question_and_answer_validator;
  
  public function __construct(QuestionAndAnswerValidatorInterface $question_and_answer_validator) 
  {
    $this->question_and_answer_validator = $question_and_answer_validator;
  }

  public static function create(ContainerInterface $container) 
  {
    return new static(
      $container->get('question_and_answer_validator')
    );
  }
  
  public function getFormId()
  {
    return 'form_test';
  }
  
  public function getQuestionSelectionList()
  {
    $questions = [];
    $questions[GuessForm::question_selection_default_key] = 'Select a question to answer...';
    $questions = array_merge($questions, $this->question_and_answer_validator->getTextOfAllQuestions());

    return $questions;
  }
  
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form = [];
    
    $form[GuessForm::select_list_key] = [
      '#type' => 'select',
      '#options' => $this->getQuestionSelectionList(),
      '#default_value' => GuessForm::question_selection_default_key,
    ];
    
    $form[GuessForm::text_field_key] = [
      '#type' => 'textfield',
      '#default_value' => GuessForm::text_field_default_value,
    ];
    
    $form[GuessForm::submit_button_key] = [
      '#type' => 'submit',
      '#value' => 'Guess!',
    ];

    return $form;
  }
  
public function validateForm(array &$form, FormStateInterface $form_state)
{
  $values = $form_state->getValues();

  $selected_question_key = $values[GuessForm::select_list_key];
  $entered_answer_value = $values[GuessForm::text_field_key];

  if ($selected_question_key == GuessForm::question_selection_default_key)
    $form_state->setErrorByName(GuessForm::select_list_key, "Please select a question to guess an answer!");
  else if ($entered_answer_value == GuessForm::text_field_default_value)
    $form_state->setErrorByName(GuessForm::text_field_key, "You should remove the default guess and enter your own.");
  else
  {
    $error_message_or_success = $this->question_and_answer_validator->validateAnswerToQuestion($selected_question_key, $entered_answer_value);

    if (is_string($error_message_or_success))
      $form_state->setErrorByName(GuessForm::text_field_key, $error_message_or_success);
  }
}
  
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    drupal_set_message("Thanks for playing! You Won!! Sorry no prizes though.");
  }
}