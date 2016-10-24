<?php

use Selenide\By, Selenide\Condition;


require_once (__DIR__ . '/../lib/bootstrap.php');


$wd = new \Selenide\Selenide();
$wd->connect();
$wd->open('http://devtest.dev/selenidehtml/');
$wd->find(By::id('e_textarea'))->setValue('Корыто')->pressEnter();
$wd->findAll(By::css('#ires li.gtest'))
    ->shouldHave(Condition::size(10))
    ->shouldNotHave(Condition::size(9));

$wd->find(By::text('textOne'))
    ->shouldHave(Condition::text("textOne"))
    ->shouldNotHave(Condition::text("textTwo"));

$wd->find(By::withText('textTwo'))
    ->shouldHave(Condition::withText("textTwo"))
    ->shouldNotHave(Condition::withText("textTwozz"));
