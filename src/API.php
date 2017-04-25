<?php
namespace EMGroup\Bluesnap;

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
     * @return bool|mixed
     */
    public function CreateShopperVault($firstname, $lastname, $encryptedCreditCard, $encryptedCVV, $cardExpireYear, $cardExpireMonth, $currency, $cardType){
        $response = APICall::call('vaulted-shoppers', [
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
        ]);
        return $response;
    }

    /**
     * Charges amount on shopper ID
     * @param $shopper_id
     * @param $amount
     * @param $currency
     * @param string $recurringTransaction
     * @param string $softDescriptor
     * @param string $transactionType
     * @return array
     */
    public function Authorize($shopper_id, $amount, $currency, $recurringTransaction = 'RECURRING', $softDescriptor = 'Shopper Charge', $transactionType = 'AUTH_CAPTURE'){
        $data = [
            'amount' => $amount,
            'vaultedShopperId' => $shopper_id,
            'recurringTransaction' => $recurringTransaction,
            'softDescriptor' => $softDescriptor,
            'currency' => $currency,
            'cardTransactionType' => $transactionType
        ];
        $response = APICall::call('transactions', $data);
        return $response;
    }

}