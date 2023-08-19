<?php

namespace App\Controllers;

use Core\Services\Auth\Auth;
use Core\Services\Cookie;
use Core\Views;

class AuthController extends Views{

  protected $errors;

  private $data;

  public function __construct($page){

    if(Auth::isLoggedIn()){
      parent::redirect();
    }

    $cookie = Cookie::getInstance();
    
    $this->errors = $cookie::getErrors();

    $this->data = [
        "plugins"=>[
            "header"=>[
                "googleFont"=>[],
                "fontAwesomeIcons"=>[],
                "adminLTE"=>[],
            ],
            "footer"=>[
                "jQuery"=>[],
                "bootstrap"=>[],
                "jqueryValidation"=>[],
                "app"=>[]
            ]
        ]
      ];
    
  }
    
    public function lock($data=[]){
      // $data = 

      // Створення об'єкту View та виклик методу render()
      views::render('lock', $data);
    }

    public function login($data=[]){
        // Створення об'єкту View та виклик методу render()
        $data = ['title'=>_('Authentication')];
        views::render('login', array_merge($this->data, $data));
    }

    public function register($data=[]){
        $data = [
                'title' => _('Registration'),
                "plugins"=>[
                    "footer"=>[
                        "customJSCode"=>["code"=>"$(function () {
                            $('#registerForm').validate({
                              rules: {
                                email: {
                                  required: true,
                                  email: true,
                                },
                                password: {
                                  required: true,
                                  minlength: 5
                                },
                                text: {
                                  required: true,
                                  minlength: 3
                                },
                                terms: {
                                  required: true
                                },
                              },
                              messages: {
                                email: {
                                  required: \"" . _("Please enter a email address") . "\",
                                  email: \"" . _("Please enter a valid email address") . "\"
                                },
                                password: {
                                  required: \"" . _("Please provide a password") . "\",
                                  minlength: \"" . _("Your password must be at least 5 characters long") . "\"
                                },
                                text: {
                                  required: \"" . _("The field cannot be empty") . "\",
                                  minlength: \"" . _("Must be at least 3 characters long") . "\"
                                },
                                terms: \"" . _("Please accept our terms") . "\"
                              },
                              errorElement: 'span',
                              errorPlacement: function (error, element) {
                                error.addClass('invalid-feedback');
                                element.closest('.mb-3').append(error);
                              },
                              highlight: function (element, errorClass, validClass) {
                                $(element).addClass('is-invalid');
                              },
                              unhighlight: function (element, errorClass, validClass) {
                                $(element).removeClass('is-invalid');
                              }
                            });
                          });"],
                        "app"=>[]
                    ]
                ]
            ];
        
        
        // Створення об'єкту View та виклик методу render()
        views::render('register', array_merge($this->data, $data));
    }

}
