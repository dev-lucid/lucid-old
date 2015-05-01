<?php 
$countries = Model::factory('countries')->where_not_equal('country_id','US')->find_many();
$regions   = Model::factory('regions')->where('country_id','US')->find_many();
?>
<div class="container-fluid">
    <h1 class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Login or register <?=lucid_format::date()?></h1>
</div>
<div class="container-fluid">
    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong><?=lucid::i18n('navigation-register')?></strong>
            </div>
            <div class="panel-body">
                <form role="form" name="registerForm" action="authentication/register" onsubmit="return lucid.form.submit(this);">
                    <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <label><?=lucid::i18n('field-organizations-name')?></label>
                        <input type="text" name="organization_name" class="form-control" value="" />
                    </div>                    
                    <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <label><?=lucid::i18n('field-users-email')?></label>
                        <input type="text" name="email" class="form-control" value="" />
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <label><?=lucid::i18n('field-users-password')?></label>
                        <input type="password" name="password" class="form-control" value="" />
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <label><?=lucid::i18n('field-users-password-confirm')?></label>
                        <input type="password" name="password_confirm" class="form-control" value="" />
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <label><?=lucid::i18n('field-users-first_name')?></label>
                        <input type="text" name="first_name" class="form-control" value="" />
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <label><?=lucid::i18n('field-users-last_name')?></label>
                        <input type="text" name="last_name" class="form-control" value="" />
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <label><?=lucid::i18n('field-addresses-street_1')?></label>
                        <input type="text" name="street_1" class="form-control" value="" />
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <label><?=lucid::i18n('field-addresses-street_2')?></label>
                        <input type="text" name="street_2" class="form-control" value="" />
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <label><?=lucid::i18n('field-addresses-city')?></label>
                        <input type="text" name="city" class="form-control" value="" />
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <label><?=lucid::i18n('field-addresses-postal_code')?></label>
                        <input type="text" name="postal_code" class="form-control" value="" />
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <label><?=lucid::i18n('field-addresses-region_id')?></label>
                        <select name="region_id" class="form-control">
                            <option></option>
                            <?php foreach($regions as $region){?>
                            <option value="<?=$region->region_id?>"><?=$region->name?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <label><?=lucid::i18n('field-addresses-country_id')?></label>
                        <select name="country_id" class="form-control" onchange="lucid.form.selectOptionsRefresh(this);" data-url="dataloader/get_option_list" data-table="regions" data-change-field="region_id">
                            <option value="US">United States</option>
                            <?php foreach($countries as $country){?>
                            <option value="<?=$country->country_id?>"><?=$country->name?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-offset-6 col-md-6 col-lg-offset-6 col-lg-6">
                        <input type="submit" class="btn btn-primary btn-block pull-right" value="<?=lucid::i18n('action-register')?>" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong><?=lucid::i18n('navigation-login')?></strong>
            </div>
            <div class="panel-body">
                <form role="form" name="loginForm" action="authentication/login" onsubmit="return lucid.form.submit(this);">
                    
                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input id="login-username" type="text" class="form-control" name="email" value="admin@localhost" placeholder="username or email">                                        
                    </div>

                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input id="login-password" type="password" class="form-control" name="password" value="password1" placeholder="password">
                    </div>

                    <div class="input-group">
                        <div class="checkbox">
                            <label>
                                <input id="login-remember" type="checkbox" name="remember" value="1" /> Remember me
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-lg btn-primary btn-block" value="<?=lucid::i18n('action-login')?>" />
                    </div>
                     
                </form>
            </div>
        </div>
    </div>
</div>
<?php
#lucid::process_action('authentication','generate_auto_auth_token');
lucid::replace('#body');
lucid::set_nav('nav1','nav1');
lucid::set_nav('nav2','nav2');
lucid::set_title(lucid::i18n('navigation-login'));
?>