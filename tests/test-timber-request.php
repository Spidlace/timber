<?php

class TestTimberRequest extends Timber_UnitTestCase
{
    public function testPostData()
    {
        $_POST['foo'] = 'bar';
        $template = '{{request.post.foo}}';
        $context = Timber::context();
        $str = Timber::compile_string($template, $context);
        $this->assertEquals('bar', $str);
    }

    public function testGetData()
    {
        $_GET['foo'] = 'bar';
        $template = '{{request.get.foo}}';
        $context = Timber::context();
        $str = Timber::compile_string($template, $context);
        $this->assertEquals('bar', $str);
    }
}
