<?php
/**
 * Created by PhpStorm.
 * User: Javon
 * Date: 4/1/19
 * Time: 4:05 PM
 */

namespace Omnipay\FirstAtlanticCommerce\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

class Response extends AbstractResponse
{
    /**
     * Request id
     *
     * @var string URL
     */
    protected $requestId = null;

    /**
     * @var array
     */
    protected $headers = [];

    public function __construct(RequestInterface $request, $data, $headers = [])
    {
        $this->request = $request;
        $xml = simplexml_load_string($data);
        $json = json_encode($xml);
        $this->data = json_decode($json,TRUE);
        $this->headers = $headers;
    }

    /**
     * Is the transaction successful?
     *
     * @return bool
     */
    public function isSuccessful()
    {
        if ( isset($this->data['CreditCardTransactionResults']['ResponseCode']) && $this->data['CreditCardTransactionResults']['ResponseCode'] == 1)
        {
            return true;
        }
        elseif (isset($this->data['ResponseCode']) && $this->data['ResponseCode'] == 1)
        {
            return true;
        }
        elseif (isset($this->data['ResponseCode']) && $this->data['ResponseCode'] == 0)
        {
            return true;
        }
        elseif (isset($this->data['Success']) && 'true' === $this->data['Success'])
        {
            return true;
        }
        return false;
    }

    /**
     * Return transaction reference
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return null
     */
    public function getTransactionReference()
    {
        return isset($this->data['CreditCardTransactionResults']['ReferenceNumber']) ? $this->data['CreditCardTransactionResults']['ReferenceNumber'] : null;
    }

    /**
     * @return null
     */
    public function getTransactionId()
    {
        return isset($this->data['OrderNumber']) ? $this->data['OrderNumber'] : null;
    }

    /**
     * @return null
     */
    public function getResponseCode()
    {
        if (isset($this->data['CreditCardTransactionResults']['ResponseCode'])){
            return $this->data['CreditCardTransactionResults']['ResponseCode'];
        }
        elseif (isset($this->data['ResponseCode'])){
            return $this->data['ResponseCode'];
        }
        return null;
    }

    /**
     * @return null
     */
    public function getReasonCode()
    {
        if (isset($this->data['CreditCardTransactionResults']['ReasonCode'])){
            return $this->data['CreditCardTransactionResults']['ReasonCode'];
        }
        elseif (isset($this->data['ReasonCode'])){
            return $this->data['ReasonCode'];
        }
        return null;
    }

    public function getMessage()
    {
        if (isset($this->data['CreditCardTransactionResults']['ReasonCodeDescription'])){
            return $this->data['CreditCardTransactionResults']['ReasonCodeDescription'];
        }
        elseif (isset($this->data['ReasonCodeDescription'])){
            return $this->data['ReasonCodeDescription'];
        }
        elseif (isset($this->data['ResponseCodeDescription'])){
            return $this->data['ResponseCodeDescription'];
        }
        elseif (isset($this->data['ErrorMsg'])){
            return $this->data['ErrorMsg'];
        }
        return null;
    }

    /**
     * Return card reference
     *
     * @return string
     */
    public function getCardReference()
    {
        return isset($this->data['Token']) ? $this->data['Token'] : null;
    }

    /**
     * Return transaction reference
     *
     * @return string
     */
    public function getHtmlFormData()
    {
        return isset($this->data['HTMLFormData']) ? $this->data['HTMLFormData'] : null;
    }

}