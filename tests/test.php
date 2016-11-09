<?php

use Selenide\By, Selenide\Condition;


require_once (__DIR__ . '/../lib/bootstrap.php');


$wd = new \Selenide\Selenide();

$wd->connect();
$wd->open('http://devtest.dev/selenidehtml/');

$wd->find(By::id('e_textarea'))->setValue('Корыто')->pressEnter();

$wd->findAll(By::css('#ires li.gtest'))
    ->assert(Condition::size(10))
    ->assertNot(Condition::size(9));

$wd->find(By::text('textOne'))
    ->assert(Condition::text("textOne"))
    ->assertNot(Condition::text("textTwo"));

$wd->find(By::withText('textTwo'))
    ->assert(Condition::withText("textTwo"))
    ->assertNot(Condition::withText("textOne"));

$wd->find(By::id('childList'))
    ->findAll(By::tagName('li'))
    ->should(Condition::text('ChildTwo'))
    ->assert(Condition::size(1));


$wd->findAll(By::id('childList'))
    ->findAll(By::tagName('li'))
    ->should(Condition::text('ChildDouble'))
    ->assert(Condition::size(2));

$wd->findAll(By::id('childList'))
    ->findAll(By::tagName('li'))
    ->should(Condition::withText('ChildDouble'))
    ->assert(Condition::size(2));


// Displayed