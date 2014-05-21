#Everlytic API Wrapper

This provides a Laravel wrapper for the everlytic BulkMail API.

No extra methods have been added to the API.

The documentation for the API can be found here http://www.everlytic.co.za/features/integration/api-php-library/

##Installation


1. Update your composer.json file and this line to your require ***

    "chencha/everlytic": "dev-master"

2. Run composer update to get the files

3. Register the service provider in your app/config/app.php


    'providers' => array(

        'Chencha\Everlytic\EverlyticServiceProvider',

    )

4. Register the Facade in app/config/app.php 

    'aliases' => array(

        'Everlytic'=>'Chencha\Everlytic\Facades\Everlytic',

    )

5. Publish your credentials using Artisan CLI

    php artisan config:publish chencha/everlytic ***

Edit the config file 
    
    app/config/packages/chencha/everlytic/api.php

Replace the user, url and api key with the ones provided by everlytic



##Usage

The wrapper provides access to all the underlying functions provided by the php api library.

Sample usage:

    <?php
    Create a new contact
    $contact = array();
    $contact['contact_mobile'] = '0726785544';
    $contact['contact_email'] = 'bob@test.com';
    $contact['contact_name'] = 'bob';
    $contact['contact_lastname'] = 'jones';
    $listIds = array(
        1 => CONTACT_SUBSCRIPTION_STATUS_SUBSCRIBED,
        2 => CONTACT_SUBSCRIPTION_STATUS_SUBSCRIBED
    );
    $contact['list_id'] = $listIds;
    $contact['on_duplicate'] = CONTACT_ACTION_UPDATE;
    $result = Everlytic::createContact($contact);
    // Contact - get
    $createdContactId = $result['result']['id'];

    $gotContact = Everlytic::getContact($createdContactId);

    // Contact - getBatch
    $filter = array();
    $filter['contact_country_id'] = '1';

    $page = 1;
    $count = 10;
    $order = 'contact_name';
    $direction = ORDER_ASC;
    $result = Everlytic::listContacts($filter, $page, $count, $order, $direction);

    // Contact - update
    $updates = array();
    $updates['contact_name'] = 'test';
    $result = Everlytic::updateContact(1, $updates);

    // List - getBatch
    $filter = array();
    $filter['list_name'] = 'none';

    $page = 1;
    $count = 10;
    $order = 'list_name';
    $direction = ORDER_ASC;
    $result = Everlytic::getLists($filter, $page, $count, $order, $direction);

    // List - create
    $list = array();
    $list['list_name'] = 'testing_list';
    $list['list_owner_name'] = 'john';
    $list['list_owner_email'] = 'jh@example.com';
    $result = Everlytic::createList($list);
    $createdListId = $result['id'];

    // List - get
    $gotList = Everlytic::getList($createdListId);

    // Message - getBatch
    $filter = array();
    $filter['message_type'] = MESSAGE_TYPE_EMAIL;

    $page = 1;
    $count = 10;
    $order = 'message_subject';
    $direction = ORDER_ASC;
    $result = Everlytic::getMessages($filter, $page, $count, $order, $direction);

    // Message - create
    $message = array();
    $message['message_subject'] = 'a test message';
    $message['message_from_email'] = 'message@test.com';
    $message['message_from_name'] = 'johnny tester';
    $message['message_reply_email'] = 'jt@test.com';
    $message['message_type'] = MESSAGE_TYPE_EMAIL;
    $result = Everlytic::createMessage($message);
    $createdMessageId = $result['id'];

    // Message - get
    $gotMessage = Everlytic::getMessage($createdMessageId);

    // Message - update
    $updates = array();
    $updates['message_from_name'] = 'Michael';
    $result = Everlytic::updateMessage($createdMessageId, $updates);

    ?>

