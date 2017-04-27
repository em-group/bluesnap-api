<?php
namespace EMGroup\BlueSnap;

class API
{

    const API_URL_SANDBOX = 'https://sandbox.bluesnap.com/services/2/';
    const API_URL_LIVE = 'https://ws.bluesnap.com/services/2/';

    /**
     * API constructor.
     * @param string $api_url
     * @param $username
     * @param $password
     */
    public function __construct($username, $password, $api_url = API::API_URL_SANDBOX){
        APICall::$username = $username;
        APICall::$password = $password;
        APICall::$url = $api_url;
    }

    /**
     * creates a shopper in BlueSnap merchant system
     * @param $firstname
     * @param $lastname
     * @param $encryptedCreditCard
     * @param $encryptedCVV
     * @param $cardExpireYear
     * @param $cardExpireMonth
     * @param $currency
     * @param $cardType
     * @param array $additional
     * @return bool|mixed
     */
    public function createShopperVault($firstname, $lastname, $encryptedCreditCard, $encryptedCVV, $cardExpireYear, $cardExpireMonth, $currency, $cardType, $additional = []){
        $data = array_merge([
            'paymentSources' => [
                'creditCardInfo' => [
                    0 => [
                        'creditCard' => [
                            'expirationYear' => $cardExpireYear,
                            'expirationMonth' => $cardExpireMonth,
                            'encryptedCardNumber' => $encryptedCreditCard,
                            'encryptedSecurityCode' => $encryptedCVV,
                            'cardType' => $cardType
                        ]
                    ]
                ]
            ],
            'firstName' => $firstname,
            'lastName' => $lastname,
            'shopperCurrency' => $currency
        ], $additional);
        $response = APICall::call('vaulted-shoppers', 'POST', $data);
        return $response;
    }

    /**
     * update shopper vault with new creditcard information
     * @param $shopper_id
     * @param $firstname
     * @param $lastname
     * @param $encryptedCreditCard
     * @param $encryptedCVV
     * @param $cardExpireYear
     * @param $cardExpireMonth
     * @param $currency
     * @param $cardType
     * @param array $additional
     * @return bool|mixed
     */
    public function updateShopperVault($shopper_id, $firstname, $lastname, $encryptedCreditCard, $encryptedCVV, $cardExpireYear, $cardExpireMonth, $currency, $cardType, $additional = []){
        $data = array_merge([
            'paymentSources' => [
                'creditCardInfo' => [
                    0 => [
                        'creditCard' => [
                            'expirationYear' => $cardExpireYear,
                            'expirationMonth' => $cardExpireMonth,
                            'encryptedCardNumber' => $encryptedCreditCard,
                            'encryptedSecurityCode' => $encryptedCVV,
                            'cardType' => $cardType
                        ]
                    ]
                ]
            ],
            'firstName' => $firstname,
            'lastName' => $lastname,
            'shopperCurrency' => $currency
        ], $additional);
        $response = APICall::call('vaulted-shoppers/'.$shopper_id, 'PUT', $data);
        return $response;
    }

    /**
     * charges amount on saved vault shopper
     * @param $shopper_id
     * @param $amount
     * @param $currency
     * @param string $recurringTransaction
     * @param string $softDescriptor
     * @param string $transactionType
     * @return array
     */
    public function authorize($shopper_id, $amount, $currency, $recurringTransaction = 'RECURRING', $softDescriptor = null, $transactionType = 'AUTH_CAPTURE'){
        $data = [
            'amount' => $amount,
            'vaultedShopperId' => $shopper_id,
            'recurringTransaction' => $recurringTransaction,
            'currency' => $currency,
            'cardTransactionType' => $transactionType
        ];
        if ($softDescriptor){
            $data['softDescriptor'] = $softDescriptor;
        }
        $response = APICall::call('transactions', 'POST', $data);
        return $response;
    }

    /**
     * refund a transaction
     * @param $transaction_id
     * @param null $amount
     * @param null $reason
     * @return bool|mixed
     */
    public function refund($transaction_id, $amount = null, $reason = null){
        $endpoint = 'transactions/'.$transaction_id.'/refund?';
        if ($amount) $endpoint .= '&amount='.$amount;
        if ($reason) $endpoint .= '&reason='.urlencode($reason);
        return APICall::call($endpoint, 'PUT');
    }

    /**
     * returns latest Response object
     * @return APIResponse|null
     */
    public function getLastResponse(){
        return APICall::$last_response;
    }

}