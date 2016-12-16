<?php

namespace Drupal\form_validation_module_refactor_srp\Model;

class QuestionAndAnswer
{
  protected $question_text;
  protected $answer_text;
  protected $question_key;
  
  public function __construct($question_key, $question_text, $answer_text)
  {
    $this->question_key = $question_key;
    $this->question_text = $question_text;
    $this->answer_text = $answer_text;
  }
  
  public function validateAnswer($answer)
  {
    if (empty($answer))
    {
      throw new \Exception("No answer was given.");
    }

    if ($answer === $this->answer_text)
      return true;
    else
      return false;
  }
  
  public function getQuestion()
  {
    return $this->question_text;
  }
  
  public function getAnswer()
  {
    return $this->answer_text;
  }
  
  public function getQuestionKey()
  {
    return $this->question_key;
  }
}