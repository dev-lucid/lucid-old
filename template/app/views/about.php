<h1>About</h1>
<p class="lead">
  Use this document as a way to quickly start any new project.
  <br /> All you get is this text and a mostly barebones HTML document.
</p>
<?php
/*
echo(bs::container()->add(
    bs::col(
        [
            'size'  =>[12,12,8,7],
            'offset'=>[null,null,1,2]
        ]
    )->add(
        'testing3'
    )
)->render());


echo(bs::form(
    [
        'name'=>'test_form',
        'action'=>'test/submit',
        #'class'=>'form-inline',
    ]
)->add(
    bs::strong('testing')
)->add(
    bs::input_text(['name'=>'name','label'=>'Name','prefix'=>bs::icon('user')])
)->add(
    bs::input_textarea(['name'=>'textarea','label'=>'Test Text'])
)->add(
    bs::input_email(['name'=>'email','label'=>bs::icon('cog').' E-mail','prefix'=>bs::icon('envelope'),'suffix'=>'@example.com'])
)->add(
    bs::input_password(['name'=>'password','label'=>'Password'])
)->add(
    bs::input_checkbox(['name'=>'remember_me','label'=>'Remember Me'])
)->add(
    bs::input_radio(['name'=>'radiotest1','label'=>'Radio Test 1a','value'=>'a'])
)->add(
    bs::input_radio(['name'=>'radiotest1','label'=>'Radio Test 1b','value'=>'b'])
)->add(
    bs::button(['label'=>'Click Me '.bs::badge(42),'modifier'=>'primary','onclick'=>"alert('testing');"])
)->add(
    bs::button_group()->add(
        bs::button(['label'=>bs::icon('align-left')])
    )->add(
        bs::button(['label'=>bs::icon('align-center')])
    )->add(
        bs::button(['label'=>bs::icon('align-right')])
    )
)->add(
    bs::label(['text'=>'mike testing','modifier'=>'warning'])
));

echo(bs::emphasis('testing 2: '.bs::badge(42)));

echo(bs::alert(['title'=>'test-alert','body'=>'hi mike','modifier'=>'danger']));
echo(bs::alert(['title'=>'test-alert','body'=>'hi mike','modifier'=>'warning']));
echo(bs::alert(['title'=>'test-alert','body'=>'hi mike '.bs::badge(42),'modifier'=>'success']));
echo(bs::well(['text'=>'testing a well','size'=>'lg']));
echo(bs::jumbotron(['title'=>'testing a jumbotron','body'=>'heres some more text','full_width'=>true]));
*/
echo(bs::panel(['heading'=>'test panel','modifier'=>'warning','footer'=>'test panel footer'])->add(
    bs::fieldset(['title'=>'fieldset title','body'=>'fieldset body'])
));
echo(bs::progress(['values'=>[10,20,30],'modifiers'=>['danger','warning','primary'],'stripes'=>[true,false,true],'actives'=>[false,false,true]]));
echo(
    bs::nav(['type'=>'stacked'])->add(
        bs::anchor(['href'=>'#!content/about','text'=>'About','active'=>true])
    )->add(
        bs::anchor(['href'=>'#!content/contact','text'=>'Contact'])
    )
);
lucid::replace('#body');
lucid::set_nav('nav1','nav1');
lucid::set_nav('nav2','nav2');
lucid::set_title(lucid::i18n('navigation-about'));
?>