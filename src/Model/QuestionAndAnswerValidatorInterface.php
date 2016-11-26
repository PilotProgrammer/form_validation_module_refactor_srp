<?php

namespace Drupal\form_validation_module_refactor_srp\Model;

interface QuestionAndAnswerValidatorInterface
{
  public function validateAnswerToQuestion($question_key, $answer_to_validate);
}