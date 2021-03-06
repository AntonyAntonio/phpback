<?php
require_once '_TestCase.php';

use RedBeanPHP\Facade as RedBean;

class UserTest extends TestCase {

    public function testCreateUser() {
        Scripts::CreateUser('newemail2@phpback.org','Bill Gates','Gates123');
        $newuser= RedBean::load('users',4);
        $this->assertEquals($newuser->id,'4');
        $this->assertEquals($newuser->name,'Bill Gates');
        $this->assertEquals($newuser->email,'newemail2@phpback.org');
    }

    public function testLoginUser() {
        Scripts::LoginUser('newemail2@phpback.org','Gates123');
        $this->assertEquals($this->url(),'http://localhost:8080/home/');
        $this->assertContains('Logged as Bill Gates',$this->byTag('body')->text());

        //Test log out user
        $this->byLinkText('Log out')->click();
        $this->assertContains('Log in',$this->byTag('body')->text());
        $this->assertNotContains('Logged as Bill Gates',$this->byTag('body')->text());
    }

    public function testCreateIdea() {
        Scripts::LoginUser();
        Scripts::CreateIdea();
        $newidea = RedBean::load('ideas',3);
        $this->assertEquals($newidea->title,'Relativity Theory');
        $this->assertEquals($newidea->authorid,'2');
        $this->assertEquals($newidea->votes,'0');
        $this->assertEquals($newidea->content,'The  relativity is strange');
        $this->assertEquals($newidea->comments,'0');
        $this->assertEquals($newidea->status,'new');
        $this->assertEquals($newidea->categoryid,'2');

    }

    public function testCreateComment() {
        Scripts::LoginUser('newemail2@phpback.org','Gates123');
        Scripts::CreateComentary();
        $newcomment = RedBean::load('comments',2);
        $this->assertEquals($newcomment->content,'the theory of relativity is fake.');
        $this->assertEquals($newcomment->ideaid,'3');
        $this->assertEquals($newcomment->userid ,'4');
    }
    public function testFlagComment() {
        Scripts::LoginUser('newemail2@phpback.org','Gates123');
        Scripts::FlagComment();
        $newflag = RedBean::load('flags',1);
        $this->assertEquals($newflag->toflagid,'1');
        $this->assertEquals($newflag->userid,'4');
    }
    public function testVoteIdea() {
        Scripts::LoginUser('newemail2@phpback.org','Gates123');
        $this->url('home/idea/3/Relativity-Theory');
        $this->byName('Vote')->click();
        $this->byLinkText('2 Votes')->click();
        $newvote = RedBean::load('votes',1);
        $voteidea = RedBean::load('ideas',3);
        $user = RedBean::load('users',4);
        $this->assertEquals($newvote->number,'2');
        $this->assertEquals($newvote->userid,'4');
        $this->assertEquals($newvote->ideaid,'3');
        $this->assertEquals($user->votes,'48');//error
        $this->assertEquals($voteidea->votes,'2');
    }
    public function testDisableVoteIdea() {
        Scripts::LoginUser('newemail2@phpback.org','Gates123');
        $this->url('home/profile/4');
        $this->byLinkText('Delete votes')->click();
        $voteidea = RedBean::load('ideas',3);
        $this->assertEquals($voteidea->votes,'0');
    }
    public function testChangePass() {
        Scripts::LoginUser('newemail2@phpback.org','Gates123');
        $this->url('/home/profile/4');
        $this->byLinkText('Change Password')->click();
        $this->fillFields(array(
            'old' => 'Gates123',
            'new' => 'Microsoft123',
            'rnew' => 'Microsoft123'
        ));
        $this->byName('changepassform')->submit();
        $this->byLinkText('Log out')->click();
        Scripts::LoginUser('newemail2@phpback.org','Microsoft123');
        $this->assertEquals($this->url(),'http://localhost:8080/home/');
        $this->assertContains('Logged as Bill Gates',$this->byTag('body')->text());
    }


}
