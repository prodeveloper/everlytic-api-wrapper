<?php

class EverlyticTest extends \PHPUnit_Framework_TestCase {

    protected $contact = array();

    protected function setUp() {
        $contact = array();
        $contact['contact_mobile'] = '0726785544';
        $contact['contact_email'] = 'bob@test.com';
        $contact['contact_name'] = 'bob';
        $contact['contact_lastname'] = 'jones';
        $this->contact = $contact;
    }

    protected function tearDown() {
        
    }

    // tests
    function testCreateuser() {
        $everlytic = new Everlytic();
        $contact=  $this->contact;
        $listIds = array(
            1 => 45926,
        );
        $contact['list_id'] = $listIds;
        $contact['on_duplicate'] = CONTACT_ACTION_UPDATE;
        $result = $everlytic->createContact($contact);
        $this->assertTrue(isset($result['result']['id']));
    }
    

}
