<?php
namespace App\Common;

class Util {
  /**
   * Validate Http Request and return error array
   *
   * @param  \lluminate\Support\Facades\Validator $validator
   * @return $errors
   */
  public static function failsValidation($validator) :array {
    $errs = [];
    if ($validator->fails()) {
      $errMsgs = $validator->errors()->messages();
      $errs = [];
      foreach ($errMsgs as $k => $e) {
        // $errs[] = $e[0];
        $errs = array_merge($errs, $e);
      }
    }
    return $errs;
  }
}




