<?php

/**
 * @author Prefix Technologies
 * @copyright (C) 2012-Prefix Technologies
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/



/**
 * Mailer subscription API. Methods for lists, contacts, messages and templates.
 *
 * Sample:
 * $api = new MailerSubscriptionApiV2_0('qa.pmailer.net', 'jkhjkh');
 * $lists = $api->getLists(); // returns an array of lists
 *
 * @version 1.0
 * @author Prefix Technologies
 *
 */
class MailerSubscriptionApiV2_0
{
    var $_url = null;

    /**
     * RPC Client object
     * @var IXR_Client
     */
    var $_xml_rpc = null;

    /**
     * Constructs a new api interaction object.
     *
     * @param string $url API URL eg live.everlytic.com.
     * @param string $user_name User name for API.
     * @param string $api_key API key.
     */
    public function __construct($url, $user_name, $api_key)
    {
        $this->_url = $this->_getUrl($url);
        $auth = base64_encode($user_name . ':' . $api_key);
        $this->_xml_rpc = new IXR_Client($this->_url, $auth);

    }

    /**
     * Constructs the URL string which will point to the API endpoint.
     *
     * @param string $url API URL.
     *
     * @return string
     */
    private function _getUrl($url)
    {
        // Check if http:// must be added at the begining.
        if ( strpos($url, 'http://') === false )
        {
            $url = 'http://' . $url;
        }
        // Check if there is a slash at the end.
        if ( substr_compare($url, '/', -1, 1) !== 0 )
        {
            $url = $url . '/';
        }
        // Check if we must add the path to API at the end.
        if ( strpos($url, '/api/2.0/xmlrpc/') === false )
        {
            $url = $url . 'api/2.0/xmlrpc/';
        }

        return $url;

    }

    public function listMethods()
    {
        $success = $this->_xml_rpc->query(
            'system.listMethods'
        );

        if ( $success !== true )
        {
            throw new MailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    public function getSig($name)
    {
        $success = $this->_xml_rpc->query(
            'system.methodSignature',
            $name
        );

        if ( $success !== true )
        {
            throw new MailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
     * Retrieves api response for getting lists in an array.
     *
     * @param struct|array $filter Filters to apply to the query.
     * @param integer $page Pagination page.
     * @param integer $count Items per page.
     * @param string $order Order to apply to the query.
     * @param string $direction Direction in which results are ordered.
     *
     * @throws MailerSubscriptionException The Error that occured.
     *
     * @return array
     */
    public function listLists($filter = array(), $page = 1, $count = 50,
        $order = '', $direction = ORDER_ASC)
    {
        $success = $this->_xml_rpc->query(
            'lists.ListLists',
            $page,
            $count,
            $order,
            $direction,
            $filter
        );

        if ( $success !== true )
        {
            throw new MailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
     * Retrieves api response for getting a list in an array.
     *
     * @param integer $listId Identifier of list to be got.
     *
     * @throws MailerSubscriptionException The Error that occured.
     *
     * @return array
     */
    public function getList($listId)
    {
        $success = $this->_xml_rpc->query(
            'lists.GetList',
            $listId
        );

        if ( $success !== true )
        {
            throw new MailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
     * Creates new contact list.
     *
     * @param array $list Eg array('list_name' => 'test'); .
     *
     * @throws MailerSubscriptionException The Error that occured.
     *
     * @return array
     */
    public function createList(array $list = array())
    {
        $success = $this->_xml_rpc->query(
            'lists.CreateList',
            $list
        );
        if ( $success !== true )
        {
            throw new MailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
     * Updates existing contact list.
     *
     * @param integer $listId Identifier of list to be updated.
     * @param array $list Array of list properties to be updated.
     *
     * @throws MailerSubscriptionException The Error that occured.
     *
     * @return array
     */
    public function updateList($listId, array $list = array())
    {
        $success = $this->_xml_rpc->query(
            'lists.UpdateList',
            $listId,
            $list
        );
        if ( $success !== true )
        {
            throw new MailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
     * Deletes contact list by ID.
     *
     * @param integer $listId Identifier of list to be deleted.
     *
     * @throws MailerSubscriptionException The Error that occured.
     *
     * @return array
     */
    public function deleteList($listId)
    {
        $success = $this->_xml_rpc->query(
            'lists.DeleteList',
            $listId
        );
        if ( $success !== true )
        {
            throw new MailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
     * Retrieves api response for getting contacts on a list in an array.
     *
     * @param integer $listId Identifier of the list.
     * @param integer $page Result page to show.
     * @param integer $count Number of results per page.
     * @param array $order Column to order by.
     * @param string $direction Direction to order by, ASC or DESC.
     * @param array $filter Eg array('list_name' => 'test').
     *
     * @throws MailerSubscriptionException The Error that occured.
     *
     * @return array
     */
    public function listSubscriptions($listId, array $filter = array(),
        $page = 1, $count = 50, array $order = array(), $direction = 'ASC' )
    {
        $success = $this->_xml_rpc->query(
            'list._subscriptions.ListSubscriptions',
            $listId,
            $page,
            $count,
            $order,
            $direction,
            $filter
        );
        if ( $success !== true )
        {
            throw new MailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
     * Create and Update Subscription of a Contact to List.
     *
     * @param integer $listId Identifier of the list.
     * @param integer $contactId Identifier of the contact.
     * @param string $emailStatus Email status for contact on list.
     * @param string $mobileStatus Mobile status for contact on list.
     *
     * @throws MailerSubscriptionException The Error that occured.
     *
     * @return array
     */
    public function createListSubscription($listId, $contactId,
        $emailStatus = CONTACT_SUBSCRIPTION_STATUS_SUBSCRIBED,
        $mobileStatus = CONTACT_SUBSCRIPTION_STATUS_UNSUBSCRIBED)
    {
        $success = $this->_xml_rpc->query(
            'list._subscriptions.CreateListSubscription',
            $listId,
            array(
                'list_id' => $listId,
                'contact_id' => $contactId,
                'email_status' => $emailStatus,
                'mobile_status' => $mobileStatus
            )
        );
        if ( $success !== true )
        {
            throw new MailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
     * Get the subscription status of the contact on the list.
     *
     * @param integer $listId Identifier of the list.
     * @param integer $contactId Identifier of the contact.
     *
     * @throws MailerSubscriptionException The Error that occured.
     *
     * @return array
     */
    public function getListSubscription($listId, $contactId)
    {
        $success = $this->_xml_rpc->query(
            'list._subscriptions.GetListSubscription',
            $listId,
            $contactId
        );
        if ( $success !== true )
        {
            throw new MailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }




    /**
     * Gets a batch of contacts.
     *
     * @param struct|array $filter Filters to apply to the query.
     * @param integer $page Pagination page.
     * @param integer $count Items per page.
     * @param string $order Order to apply to the query.
     * @param string $direction Direction in which results are ordered.
     *
     * @throws MailerSubscriptionException The Error that occured.
     *
     * @return array
     */
    public function listContacts($filter = array(), $page = 1, $count = 50,
        $order = '', $direction = ORDER_ASC)
    {
        $success = $this->_xml_rpc->query(
            'contacts.ListContacts',
            $page,
            $count,
            $order,
            $direction,
            $filter
        );

        if ( $success !== true )
        {
            throw new MailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
     * Gets a single contact.
     *
     * @param integer $contactId Contacts identifier.
     *
     * @throws MailerSubscriptionException The Error that occured.
     *
     * @return array
     */
    public function getContact($contactId)
    {
        $success = $this->_xml_rpc->query(
            'contacts.GetContact',
            $contactId
        );

        if ( $success !== true )
        {
            throw new MailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
     * Creates a new contact.
     *
     * @param array $contact Contacts parameters.
     *
     * @throws MailerSubscriptionException The Error that occured.
     *
     * @return array
     */
    public function createContact(array $contact)
    {
        $success = $this->_xml_rpc->query(
            'contacts.CreateContact',
            $contact
        );

        if ( $success !== true )
        {
            throw new MailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
     * Gets a single message.
     *
     * @param integer $messageId Message identifier.
     *
     * @throws MailerSubscriptionException The Error that occured.
     *
     * @return array
     */
    public function getMessage($messageId)
    {
        $success = $this->_xml_rpc->query(
            'emails.GetEmail',
            $messageId
        );
        if ( $success !== true )
        {
            throw new MailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
     * Gets a batch of messages.
     *
     * @param struct|array $filter Filters to apply to the query.
     * @param integer $page Pagination page.
     * @param integer $count Items per page.
     * @param string $order Order to apply to the query.
     * @param string $direction Direction in which results are ordered.
     *
     * @throws MailerSubscriptionException The Error that occured.
     *
     * @return array
     */
    public function listMessages($filter = array(), $page = 1, $count = 50,
        $order = '', $direction = ORDER_ASC)
    {
        $success = $this->_xml_rpc->query(
            'emails.ListEmails',
            $page,
            $count,
            $order,
            $direction,
            $filter
        );

        if ( $success !== true )
        {
            throw new MailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
     * Creates a new message.
     *
     * @param array $message Message containing parameters.
     *
     * @throws MailerSubscriptionException The Error that occured.
     *
     * @return array
     */
    public function createMessage(array $message)
    {
        $success = $this->_xml_rpc->query(
            'emails.CreateEmail',
            $message
        );
        if ( $success !== true )
        {
            throw new MailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
     * Updates a message.
     *
     * @param integer $messageId Message identifier.
     * @param array $message Message parameters.
     *
     * @throws MailerSubscriptionException The Error that occured.
     *
     * @return array
     */
    public function updateMessage($messageId, array $message)
    {
        $success = $this->_xml_rpc->query(
            'emails.UpdateEmail',
            $messageId,
            $message
        );
        if ( $success !== true )
        {
            throw new MailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
     * Sends a message.
     *
     * @param integer $messageId Message identifier.
     *
     * @throws MailerSubscriptionException The Error that occured.
     *
     * @return array
     */
    public function sendMessage($messageId)
    {
        $success = $this->_xml_rpc->query(
            'emails.SendEmail',
            $messageId
        );
        if ( $success !== true )
        {
            throw new MailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
     * Get message reports.
     *
     * @param integer $messageId Message identifier.
     * @param string $report The type of report to return.
     * Defaults to overview. Possible values:
     * * overview - A general overview of the email.
     * * contacts - Contact activity on the email.
     * * links - Link performance on the email.
     * * locations - Locations the email was delivered to.
     *
     * @throws MailerSubscriptionException The Error that occured.
     *
     * @return array
     */
    public function messageReports($messageId, $report)
    {
        $success = $this->_xml_rpc->query(
            'email._reports.EmailReports',
            $messageId,
            $report
        );
        if ( $success !== true )
        {
            throw new MailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }


    /**
     * Gets a transactional category.
     *
     * @param integer|string $id Transactional category identifier.
     *
     * @throws MailerSubscriptionException The Error that occured.
     *
     * @return array
     */
    public function getTransactionalCategory($id)
    {
        $success = $this->_xml_rpc->query(
            'transactional._categories.GetTransactionalCategory',
            $id
        );
        if ( $success !== true )
        {
            throw new MailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
     * Get a list of transactional categories.
     *
     * @param string $search Match transaction group names containing a specific keyword.
     * @param integer $page Pagination page.
     * @param integer $count Items per page.
     * @param string $order Order to apply to the query.
     * @param string $direction Direction in which results are ordered.
     *
     * @throws MailerSubscriptionException The Error that occured.
     *
     * @return array
     */
    public function listTransactionalCategories($search = '', $page = 1,
        $count = 50, $order = '', $direction = ORDER_ASC)
    {
        $success = $this->_xml_rpc->query(
            'transactional._categories.ListTransactionalCategories',
            $search,
            $page,
            $count,
            $order,
            $direction
        );

        if ( $success !== true )
        {
            throw new MailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
     * Gets a transactional category.
     *
     * @param integer|string $id Transactional category identifier.
     *
     * @throws MailerSubscriptionException The Error that occured.
     *
     * @return array
     */
    public function getTransactionalCallbackEvent($id)
    {
        $success = $this->_xml_rpc->query(
            'transactional._callback_events.GetTransactionalCallbackEvents',
            $id
        );
        if ( $success !== true )
        {
            throw new MailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
     * Creates a transactional mail.
     *
     * @param array $headers An array of headers to use when sending the email.
     * @param array $body An array containing at least a text or an html
     * element, defining the body of the email.
     * @param array $attachments An array containing the attachments for the
     * email.
     * @param array $options An array containing the options for the email.
     * @param array $properties An array of properties for the email.
     *
     * @throws MailerSubscriptionException The Error that occured.
     *
     * @return array
     */
    public function createTransactionalMail(array $headers, array $body,
        array $attachments = array(), array $options = array())
    {
        $success = $this->_xml_rpc->query(
            'transactional._mails.CreateTransactionalMail',
            $headers,
            $body,
            $attachments,
            $options
        );

        if ( $success !== true )
        {
            throw new MailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
     * Gets a transactional mail.
     *
     * @param integer $id Transactional mail identifier.
     *
     * @throws MailerSubscriptionException The Error that occured.
     *
     * @return array
     */
    public function getTransactionalMail($id)
    {
        $success = $this->_xml_rpc->query(
            'transactional._mails.GetTransactionalMail',
            $id
        );
        if ( $success !== true )
        {
            throw new MailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
     * Get a list of transactional mails.
     *
     * @param struct|array $filters Filters to apply to the query.
     * @param integer $page Pagination page.
     * @param integer $count Items per page.
     * @param string $order Order to apply to the query.
     * @param string $direction Direction in which results are ordered.
     *
     * @throws MailerSubscriptionException The Error that occured.
     *
     * @return array
     */
    public function listTransactionalMails($filters = array(), $page = 1,
        $count = 50, $order = '', $direction = ORDER_ASC)
    {
        $success = $this->_xml_rpc->query(
            'transactional._mails.ListTransactionalMails',
            $page,
            $count,
            $order,
            $direction,
            $filters
        );

        if ( $success !== true )
        {
            throw new MailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
     * Get a list of transactional events.
     *
     * @param struct|array $filters Filters to apply to the query.
     * @param integer $page Pagination page.
     * @param integer $count Items per page.
     * @param string $order Order to apply to the query.
     * @param string $direction Direction in which results are ordered.
     *
     * @throws MailerSubscriptionException The Error that occured.
     *
     * @return array
     */
    public function listTransactionalCallbackEvents(array $filters =
        array(), $page = 1, $count = 100, $order = null, $direction = 'asc')
    {
        $success = $this->_xml_rpc->query(
            'transactional._callback_events.ListTransactionalCallbackEvents',
            $page,
            $count,
            $order,
            $direction,
            $filters
        );

        if ( $success !== true )
        {
            throw new MailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
     * Gets a transactional stat summary.
     *
     * @param integer $id Transactional summary identifier.
     *
     * @throws MailerSubscriptionException The Error that occured.
     *
     * @return array
     */
    public function getTransactionalStatsSummary($id)
    {
        $success = $this->_xml_rpc->query(
            'transactional._stats_summary.GetTransactionalStatsSummary',
            $id
        );
        if ( $success !== true )
        {
            throw new MailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
     * Get a list of transactional summaries.
     *
     * @param integer $group_id Group identifier.
     * @param struct|array $filters Filters to apply to the query.
     * @param integer $page Pagination page.
     * @param integer $count Items per page.
     * @param string $order Order to apply to the query.
     * @param string $direction Direction in which results are ordered.
     *
     * @throws MailerSubscriptionException The Error that occured.
     *
     * @return array
     */
    public function listTransactionalStatsSummaries($group_id, array $filters =
        array(), $page = 1, $count = 50, $order = '', $direction = ORDER_ASC)
    {
        $success = $this->_xml_rpc->query(
            'transactional._stats_summary.ListTransactionalStatsSummary',
            $group_id,
            $page,
            $count,
            $order,
            $direction,
            $filters
        );

        if ( $success !== true )
        {
            throw new MailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

}

/**
 * Exception that is thrown when API interaction occurs.
 *
 * @author Prefix
 *
 */
class MailerSubscriptionException extends Exception
{

    /**
     * Constructs a new MailerSubscriptionException.
     *
     * @param string $message The error that occured.
     */
    public function __construct($message)
    {
        parent::__construct($message);

    }
}

/**
 * IXR_Client
 *
 * @package IXR
 * @since 1.5
 *
 */
class IXR_Client
{
    var $server;
    var $port;
    var $path;
    var $useragent;
    var $response;
    var $message = false;
    var $debug = true;
    var $timeout;
    var $headers = array();
    var $auth;

    // Storage place for an error message
    var $error = false;

    function IXR_Client($server, $auth, $path = false, $port = 80, $timeout = 15)
    {
        if (!$path) {
            // Assume we have been given a URL instead
            $bits = parse_url($server);
            $this->server = $bits['host'];
            $this->port = isset($bits['port']) ? $bits['port'] : 80;
            $this->path = isset($bits['path']) ? $bits['path'] : '/';

            // Make absolutely sure we have a path
            if (!$this->path) {
                $this->path = '/';
            }
        } else {
            $this->server = $server;
            $this->path = $path;
            $this->port = $port;
        }
        $this->useragent = 'Everlytic Plugin';
        $this->timeout = $timeout;
        $this->auth = $auth;
    }

    function query()
    {
        $args = func_get_args();
        $method = array_shift($args);
        $request = new IXR_Request($method, $args);
        $length = $request->getLength();
        $xml = $request->getXml();
        $r = "\r\n";
        $request  = 'POST ' . $this->path . ' HTTP/1.0' . $r;

        // Merged from WP #8145 - allow custom headers
        $this->headers['Host']           = $this->server;
        $this->headers['Content-Type']   = 'text/xml';
        $this->headers['User-Agent']     = $this->useragent;
        $this->headers['Content-Length'] = $length;
        $this->headers['Authorization']   = 'Basic ' . $this->auth;

        foreach( $this->headers as $header => $value ) {
            $request .= $header . ': ' . $value . $r ;
        }
        $request .= $r;

        $request .= $xml;

        // Now send the request
        if ($this->debug) {
            //echo '<pre class="ixr_request">'.htmlspecialchars($request)."\n".'</pre>'."\n\n";
        }

        if ($this->timeout) {
            $fp = @fsockopen($this->server, $this->port, $errno, $errstr, $this->timeout);
        } else {
            $fp = @fsockopen($this->server, $this->port, $errno, $errstr);
        }
        if (!$fp) {
            $this->error = new IXR_Error(-32300, 'transport error - could not open socket');
            return false;
        }
        fputs($fp, $request);
        $contents = '';
        $debugContents = '';
        $gotFirstLine = false;
        $gettingHeaders = true;
        while (!feof($fp)) {
            $line = fgets($fp, 4096);
            if (!$gotFirstLine) {
                // Check line for '200'
                if (strstr($line, '200') === false) {
                    $this->error = new IXR_Error(-32300, 'transport error - HTTP status code was not 200');
                    return false;
                }
                $gotFirstLine = true;
            }
            if (trim($line) == '') {
                $gettingHeaders = false;
            }
            if (!$gettingHeaders) {
                // merged from WP #12559 - remove trim
                $contents .= $line;
            }
            if ($this->debug) {
                $debugContents .= $line;
            }
        }
        //echo $contents;
        if ($this->debug) {
            //echo '<pre class="ixr_response">'.htmlspecialchars($debugContents)."\n".'</pre>'."\n\n";
        }

        // Now parse what we've got back
        $this->message = new IXR_Message($contents);
        if (!$this->message->parse()) {
            // XML error

            $this->error = new IXR_Error(-32700, 'parse error. not well formed');
            return false;
        }

        // Is the message a fault?
        if ($this->message->messageType == 'fault') {
            $this->error = new IXR_Error($this->message->faultCode, $this->message->faultString);
            return false;
        }

        // Message must be OK
        return true;
    }

    function getResponse()
    {
        // methodResponses can only have one param - return that
        return $this->message->params[0];
    }

    function isError()
    {
        return (is_object($this->error));
    }

    function getErrorCode()
    {
        return $this->error->code;
    }

    function getErrorMessage()
    {
        return $this->error->message;
    }
}

/**
 * IXR_MESSAGE
 *
 * @package IXR
 * @since 1.5
 *
 */
class IXR_Message
{
    var $message;
    var $messageType;  // methodCall / methodResponse / fault
    var $faultCode;
    var $faultString;
    var $methodName;
    var $params;

    // Current variable stacks
    var $_arraystructs = array();   // The stack used to keep track of the current array/struct
    var $_arraystructstypes = array(); // Stack keeping track of if things are structs or array
    var $_currentStructName = array();  // A stack as well
    var $_param;
    var $_value;
    var $_currentTag;
    var $_currentTagContents;
    // The XML parser
    var $_parser;

    function IXR_Message($message)
    {
        $this->message =& $message;
    }

    function parse()
    {
        // first remove the XML declaration
        // merged from WP #10698 - this method avoids the RAM usage of preg_replace on very large messages
        $header = preg_replace( '/<\?xml.*?\?'.'>/', '', substr($this->message, 0, 100), 1);
        $this->message = substr_replace($this->message, $header, 0, 100);
        if (trim($this->message) == '') {
            return false;
        }
        $this->_parser = xml_parser_create('UTF-8');
        // Set XML parser to take the case of tags in to account
        xml_parser_set_option($this->_parser, XML_OPTION_CASE_FOLDING, false);
        // Set XML parser callback functions
        xml_set_object($this->_parser, $this);
        xml_set_element_handler($this->_parser, 'tag_open', 'tag_close');
        xml_set_character_data_handler($this->_parser, 'cdata');
        $chunk_size = 262144; // 256Kb, parse in chunks to avoid the RAM usage on very large messages
        do {
            if (strlen($this->message) <= $chunk_size) {
                $final = true;
            }
            $part = substr($this->message, 0, $chunk_size);
            $this->message = substr($this->message, $chunk_size);
            if (!xml_parse($this->_parser, $part, $final)) {
                $error = xml_error_string(xml_get_error_code($this->_parser));
                var_dump(
                    $error,
                    xml_get_current_line_number($this->_parser),
                    xml_get_current_column_number($this->_parser)
                );
                die($error);
                return false;
            }
            if ($final) {
                break;
            }
        } while (true);
        xml_parser_free($this->_parser);

        // Grab the error messages, if any
        if ($this->messageType == 'fault') {
            $this->faultCode = $this->params[0]['faultCode'];
            $this->faultString = $this->params[0]['faultString'];
        }
        return true;
    }

    function tag_open($parser, $tag, $attr)
    {
        $this->_currentTagContents = '';
        $this->currentTag = $tag;
        switch($tag) {
            case 'methodCall':
            case 'methodResponse':
            case 'fault':
                $this->messageType = $tag;
                break;
                /* Deal with stacks of arrays and structs */
            case 'data':    // data is to all intents and puposes more interesting than array
                $this->_arraystructstypes[] = 'array';
                $this->_arraystructs[] = array();
                break;
            case 'struct':
                $this->_arraystructstypes[] = 'struct';
                $this->_arraystructs[] = array();
                break;
        }
    }

    function cdata($parser, $cdata)
    {
        $this->_currentTagContents .= $cdata;
    }

    function tag_close($parser, $tag)
    {
        $valueFlag = false;
        switch($tag) {
            case 'int':
            case 'i4':
                $value = (int)trim($this->_currentTagContents);
                $valueFlag = true;
                break;
            case 'double':
                $value = (double)trim($this->_currentTagContents);
                $valueFlag = true;
                break;
            case 'string':
                $value = (string)trim($this->_currentTagContents);
                $valueFlag = true;
                break;
            case 'dateTime.iso8601':
                $value = new IXR_Date(trim($this->_currentTagContents));
                $valueFlag = true;
                break;
            case 'value':
                // 'If no type is indicated, the type is string.'
                if (trim($this->_currentTagContents) != '') {
                    $value = (string)$this->_currentTagContents;
                    $valueFlag = true;
                }
                break;
            case 'boolean':
                $value = (boolean)trim($this->_currentTagContents);
                $valueFlag = true;
                break;
            case 'base64':
                $value = base64_decode($this->_currentTagContents);
                $valueFlag = true;
                break;
                /* Deal with stacks of arrays and structs */
            case 'data':
            case 'struct':
                $value = array_pop($this->_arraystructs);
                array_pop($this->_arraystructstypes);
                $valueFlag = true;
                break;
            case 'member':
                array_pop($this->_currentStructName);
                break;
            case 'name':
                $this->_currentStructName[] = trim($this->_currentTagContents);
                break;
            case 'methodName':
                $this->methodName = trim($this->_currentTagContents);
                break;
        }

        if ($valueFlag) {
            if (count($this->_arraystructs) > 0) {
                // Add value to struct or array
                if ($this->_arraystructstypes[count($this->_arraystructstypes)-1] == 'struct') {
                    // Add to struct
                    $this->_arraystructs[count($this->_arraystructs)-1][$this->_currentStructName[count($this->_currentStructName)-1]] = $value;
                } else {
                    // Add to array
                    $this->_arraystructs[count($this->_arraystructs)-1][] = $value;
                }
            } else {
                // Just add as a paramater
                $this->params[] = $value;
            }
        }
        $this->_currentTagContents = '';
    }
}

/**
 * IXR_Request
 *
 * @package IXR
 * @since 1.5
 */
class IXR_Request
{
    var $method;
    var $args;
    var $xml;

    function IXR_Request($method, $args)
    {
        $this->method = $method;
        $this->args = $args;
        $this->xml = <<<EOD
<?xml version='1.0'?>
<methodCall>
<methodName>{$this->method}</methodName>
<params>

EOD;
        foreach ($this->args as $arg) {
            $this->xml .= '<param><value>';
            $v = new IXR_Value($arg);
            $this->xml .= $v->getXml();
            $this->xml .= '</value></param>'."\n";
        }
        $this->xml .= '</params>'."\n".'</methodCall>';
    }

    function getLength()
    {
        return strlen($this->xml);
    }

    function getXml()
    {
        return $this->xml;
    }
}

/**
 * IXR_Date
 *
 * @package IXR
 * @since 1.5
 */
class IXR_Date {
    var $year;
    var $month;
    var $day;
    var $hour;
    var $minute;
    var $second;
    var $timezone;

    function IXR_Date($time)
    {
        // $time can be a PHP timestamp or an ISO one
        if (is_numeric($time)) {
            $this->parseTimestamp($time);
        } else {
            $this->parseIso($time);
        }
    }

    function parseTimestamp($timestamp)
    {
        $this->year = date('Y', $timestamp);
        $this->month = date('m', $timestamp);
        $this->day = date('d', $timestamp);
        $this->hour = date('H', $timestamp);
        $this->minute = date('i', $timestamp);
        $this->second = date('s', $timestamp);
        $this->timezone = '';
    }

    function parseIso($iso)
    {
        $this->year = substr($iso, 0, 4);
        $this->month = substr($iso, 4, 2);
        $this->day = substr($iso, 6, 2);
        $this->hour = substr($iso, 9, 2);
        $this->minute = substr($iso, 12, 2);
        $this->second = substr($iso, 15, 2);
        $this->timezone = substr($iso, 17);
    }

    function getIso()
    {
        return $this->year.$this->month.$this->day.'T'.$this->hour.':'.$this->minute.':'.$this->second.$this->timezone;
    }

    function getXml()
    {
        return '<dateTime.iso8601>'.$this->getIso().'</dateTime.iso8601>';
    }

    function getTimestamp()
    {
        return mktime($this->hour, $this->minute, $this->second, $this->month, $this->day, $this->year);
    }
}

class IXR_Value
{
    var $data;
    var $type;

    function IXR_Value($data, $type = false)
    {
        $this->data = $data;
        if (!$type) {
            $type = $this->calculateType();
        }
        $this->type = $type;
        if ($type == 'struct') {
            // Turn all the values in the array in to new IXR_Value objects
            foreach ($this->data as $key => $value) {
                $this->data[$key] = new IXR_Value($value);
            }
        }
        if ($type == 'array') {
            for ($i = 0, $j = count($this->data); $i < $j; $i++) {
                $this->data[$i] = new IXR_Value($this->data[$i]);
            }
        }
    }

    function calculateType()
    {
        if ($this->data === true || $this->data === false) {
            return 'boolean';
        }
        if (is_integer($this->data)) {
            return 'int';
        }
        if (is_double($this->data)) {
            return 'double';
        }

        // Deal with IXR object types base64 and date
        if (is_object($this->data) && is_a($this->data, 'IXR_Date')) {
            return 'date';
        }
        if (is_object($this->data) && is_a($this->data, 'IXR_Base64')) {
            return 'base64';
        }

        // If it is a normal PHP object convert it in to a struct
        if (is_object($this->data)) {
            $this->data = get_object_vars($this->data);
            return 'struct';
        }
        if (!is_array($this->data)) {
            return 'string';
        }

        // We have an array - is it an array or a struct?
        if ($this->isStruct($this->data)) {
            return 'struct';
        } else {
            return 'array';
        }
    }

    function getXml()
    {
        // Return XML for this value
        switch ($this->type) {
            case 'boolean':
                return '<boolean>'.(($this->data) ? '1' : '0').'</boolean>';
                break;
            case 'int':
                return '<int>'.$this->data.'</int>';
                break;
            case 'double':
                return '<double>'.$this->data.'</double>';
                break;
            case 'string':
                return '<string>'.htmlspecialchars($this->data).'</string>';
                break;
            case 'array':
                $return = '<array><data>'."\n";
                foreach ($this->data as $item) {
                    $return .= '  <value>'.$item->getXml().'</value>'."\n";
                }
                $return .= '</data></array>';
                return $return;
                break;
            case 'struct':
                $return = '<struct>'."\n";
                foreach ($this->data as $name => $value) {
                    $return .= '  <member><name>' .$name . '</name><value>';
                    $return .= $value->getXml().'</value></member>'."\n";
                }
                $return .= '</struct>';
                return $return;
                break;
            case 'date':
            case 'base64':
                return $this->data->getXml();
                break;
        }
        return false;
    }

    /**
     * Checks whether or not the supplied array is a struct or not
     *
     * @param unknown_type $array
     * @return boolean
     */
    function isStruct($array)
    {
        $expected = 0;
        foreach ($array as $key => $value) {
            if ((string)$key != (string)$expected) {
                return true;
            }
            $expected++;
        }
        return false;
    }
}

/**
 * IXR_Error
 *
 * @package IXR
 * @since 1.5
 */
class IXR_Error
{
    var $code;
    var $message;

    function IXR_Error($code, $message)
    {
        $this->code = $code;
        $this->message = htmlspecialchars($message);
    }

    function getXml()
    {
        $xml = <<<EOD
<methodResponse>
  <fault>
    <value>
      <struct>
        <member>
          <name>faultCode</name>
          <value><int>{$this->code}</int></value>
        </member>
        <member>
          <name>faultString</name>
          <value><string>{$this->message}</string></value>
        </member>
      </struct>
    </value>
  </fault>
</methodResponse>

EOD;
        return $xml;
    }
}


?>