<?php

namespace Chencha\Everlytic;

use Illuminate\Support\ServiceProvider;

class EverlyticServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot() {
        $this->package('chencha/everlytic');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {

        $this->_defineConstants();
        \App::bind('everlytic', function() {
            return new \Chencha\Everlytic\Everlytic;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return array();
    }

    protected function _defineConstants() {
        define('ORDER_ASC', 'ASC');
        define('ORDER_DESC', 'DESC');

// Status values on returned results
        define('STATUS_SUCCESS', 'success');
        define('STATUS_WARNING', 'warning');
        define('STATUS_ERROR', 'error');

// Constants relating to contacts
        define('CONTACT_SUBSCRIPTION_STATUS_SUBSCRIBED', 'subscribed');
        define('CONTACT_SUBSCRIPTION_STATUS_UNSUBSCRIBED', 'unsubscribed');
        define('CONTACT_SUBSCRIPTION_STATUS_UNCONFIRMED', 'unconfirmed');
        define('CONTACT_SUBSCRIPTION_STATUS_FORWARDED', 'forwarded');

        define('CONTACT_STATUS_ON', 'on');
        define('CONTACT_STATUS_OFF', 'off');
        define('CONTACT_STATUS_SUPPRESSED', 'suppressed');

        define('CONTACT_EMAIL_SMS_STATUS_NONE', 'none');
        define('CONTACT_EMAIL_SMS_STATUS_BOUNCING', 'bouncing');
        define('CONTACT_EMAIL_SMS_STATUS_BOUNCED', 'bounced');
        define('CONTACT_EMAIL_SMS_STATUS_ALWAYS_SEND', 'always send');

        define('CONTACT_ACTION_ADD', 'add');
        define('CONTACT_ACTION_UPDATE', 'update');
        define('CONTACT_ACTION_IGNORE', 'ignore');

        define('CONTACT_GENDER_UNKNOWN', 'unknown');
        define('CONTACT_GENDER_MALE', 'male');
        define('CONTACT_GENDER_FEMALE', 'female');

        define('CONTACT_MARITAL_STATUS_UNKNOWN', 'unknown');
        define('CONTACT_MARITAL_STATUS_SINGLE', 'single');
        define('CONTACT_MARITAL_STATUS_MARRIED', 'married');

        define('CONTACT_PREFERED_EMAIL_FORMAT_HTML', 'html');
        define('CONTACT_PREFERED_EMAIL_FORMAT_TEXT', 'text');

// Constants relating to messages
        define('MESSAGE_TYPE_EMAIL', 'email');
        define('MESSAGE_TYPE_SMS', 'sms');

        define('MESSAGE_HTML_TEXT_FETCH_WHEN_NOW', 'now');
        define('MESSAGE_HTML_TEXT_FETCH_WHEN_CONFIRM', 'confirm');
        define('MESSAGE_HTML_TEXT_FETCH_WHEN_SEND', 'send');

        define('MESSAGE_SEND_SCHEDULE_RECURRENCE_ONCE', 'once');
        define('MESSAGE_SEND_SCHEDULE_RECURRENCE_WEEKDAYS', 'weekdays');
        define('MESSAGE_SEND_SCHEDULE_RECURRENCE_DAILY', 'daily');
        define('MESSAGE_SEND_SCHEDULE_RECURRENCE_WEEKLY', 'weekly');
        define('MESSAGE_SEND_SCHEDULE_RECURRENCE_MONTHLY', 'monthly');

        define('MESSAGE_PRIORITY_LOW', 'low');
        define('MESSAGE_PRIORITY_MEDIUM', 'medium');
        define('MESSAGE_PRIORITY_HIGH', 'high');

        define('MESSAGE_ENCODING_7BIT', '7bit');
        define('MESSAGE_ENCODING_8BIT', '8bit');
        define('MESSAGE_ENCODING_BINARY', 'binary');
        define('MESSAGE_ENCODING_QUOTED_PRINTABLE', 'quoted-printable');
        define('MESSAGE_ENCODING_BASE64', 'base64');
    }

}
