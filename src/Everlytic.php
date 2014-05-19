<?php
namespace Chencha\Everlytic;
/**
 * Wrapper for everyltic API
 *
 * @author jacob
 */
class Everlytic extends \MailerSubscriptionApiV2_0 {

    public function __construct() {
        parent::__construct(\Config::get('everlytic::api.url'), \Config::get('everlytic::api.user'), \Config::get('everlytic::api.apiKey'));  
    }

}
