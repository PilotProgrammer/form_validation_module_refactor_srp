<?php

namespace Drupal\form_validation_module_refactor_srp\Model\Exceptions;

class AnswerNotFoundException
{
  public function __construct($key, $code = 0, Exception $previous = null) 
  {
    $message = "Answer for key {$key} not found.";
    parent::__construct($message, $code, $previous);
  }
}