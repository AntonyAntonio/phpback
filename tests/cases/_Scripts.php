<?php

class Scripts {
    static $instance;

    public static function setInstance($element) {
        self::$instance = $element;
    }

    public static function LoginAdmin($email = 'admin@phpback.org', $password = 'admin') {
        $test = self::$instance;

        $test->url('/admin');

        $test->fillFields(array(
            'email' => $email,
            'password' => $password
        ));
        $test->byCssSelector('input[type=submit]')->click();
    }

    public static function CreateUser($email = 'newemail@phpback.org', $name = 'Steve Jobs', $password = 'Jobs123'){
      $test = self::$instance;

      $test->url('/home/register');
      $test->fillFields(array(
           'email' => $email ,
           'name'  => $name,
           'password' => $password,
           'password2' => $password
      ));
      $test->byName('registration-form')->submit();
    }

    public static function LoginUser($email = 'newemail@phpback.org', $password = 'Jobs123') {
        $test = self::$instance;

        $test->url('/home/login');

        $test->fillFields(array(
            'email' => $email,
            'password' => $password
        ));
        $test->byName('login-form')->submit();
    }
    public static function LogoutUser(){
        $test = self::$instance;
        $test->url('/home/login');
        $test->byLinkText('Log out')->click();
    }
    public static function CreateCategory($name = 'Amsterdam') {
        $test = self::$instance;
        self::LoginAdmin();
        $test->byLinkText('System Settings')->click();
        $test->byLinkText('Categories')->click();

        $test->fillFields(array(
            'name' => $name,
            'description' => 'Value Category Description'
        ));
        $test->byName('add-category')->click();
    }
    public static function CreateIdea($title= 'Relativity Theory', $description='The  relativity is strange'){
        $test = self::$instance;
        $test->url('/home');
        $test->byLinkText('Post a new idea')->click();
        $test->fillFields(array(
          'description' => $description,
          'title' => $title
        ));
        $test->select($test->byName('category'))->selectOptionByValue('2');
        $test->byName('post-idea-form')->submit();
    }
    public static function ApproveIdea(){
        $test = self::$instance;
        self::LoginUser();
        self::CreateIdea();
        self::LogoutUser();
        self::LoginAdmin();
        $test->url('/home/idea/1/Relativity-Theory');
        $test->byLinkText('Approve Idea')->click();
    }
    public static function CreateComment($url='home/idea/3/Relativity-Theory'){
        $test = self::$instance;
        $test->url($url);
        $test->fillFields(array(
                'content' => 'the theory of relativity is fake.'
        ));
        $test->byName('commentbutton')->click();
    }
    public static function FlagComment($url='home/idea/3/Relativity-Theory'){
        $test->url($url);
        $test->byLinkText('flag comment')->click();
    }
}
