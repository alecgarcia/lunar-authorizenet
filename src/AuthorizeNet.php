<?php

namespace alecgarcia\LunarAuthorizeNet;

use net\authorize\api\constants\ANetEnvironment;
use net\authorize\api\contract\v1\CardArtType;
use net\authorize\api\contract\v1\CreateTransactionRequest;
use net\authorize\api\contract\v1\CustomerAddressType;
use net\authorize\api\contract\v1\CustomerDataType;
use net\authorize\api\contract\v1\MerchantAuthenticationType;
use net\authorize\api\contract\v1\OpaqueDataType;
use net\authorize\api\contract\v1\OrderType;
use net\authorize\api\contract\v1\PaymentType;
use net\authorize\api\contract\v1\SettingType;
use net\authorize\api\contract\v1\TransactionRequestType;
use net\authorize\api\contract\v1\UserFieldType;
use net\authorize\api\controller\CreateTransactionController;

class AuthorizeNet
{
    protected MerchantAuthenticationType $merchantAuthentication;

    public function __construct()
    {
        $this->merchantAuthentication = new MerchantAuthenticationType();
        $this->merchantAuthentication->setName(config('services.authorizenet.apiLoginID'));
        $this->merchantAuthentication->setTransactionKey(config('services.authorizenet.transactionKey'));
    }

    public function createAnAcceptPaymentTransaction(int $amount, string $dataDescriptor, string $dataValue)
    {
        $request = new CreateTransactionRequest();
        $request->setMerchantAuthentication($this->merchantAuthentication);

        // Create a TransactionRequestType object
        $transactionRequest = new TransactionRequestType();
        $transactionRequest->setTransactionType('authCaptureTransaction');
        $transactionRequest->setAmount($amount);

        // Create the payment object for a payment nonce
        $opaqueData = new OpaqueDataType();
        $opaqueData->setDataDescriptor($dataDescriptor);
        $opaqueData->setDataValue($dataValue);

        // Add the payment data to a paymentType object
        $paymentType = new PaymentType();
        $paymentType->setOpaqueData($opaqueData);

        $transactionRequest->setPayment($paymentType);

        // TODO: Make this the order id or something?
        $request->setRefId('ref' . time());
        $request->setTransactionRequest($transactionRequest);

        return $this->handleTransactionRequest($request);
    }

    private function handleTransactionRequest(CreateTransactionRequest $request)
    {
        $controller = new CreateTransactionController($request);
        $response = $controller->executeWithApiResponse($this->getANetEnvironment());

        if ($response != null && $response->getMessages()->getResultCode() == 'Ok') {
            $transactionResponse = $response->getTransactionResponse();

            if ($transactionResponse != null && $transactionResponse->getMessages() != null) {
                return [
                    'success' => true,
                    'transactionId' => $transactionResponse->getTransId(),
                    'messages' => $transactionResponse->getMessages(),
                ];
            }
        } else {
            return [
                'success' => false,
                'error' => $response->getMessages()->getMessage()[0]->getText(),
            ];
        }
    }

    private function getANetEnvironment(): string
    {
        return config(
            'lunar-authorizenet.environment',
            app()->environment('production') ? 'production' : 'sandbox'
        ) === 'production'
            ? ANetEnvironment::PRODUCTION
            : ANetEnvironment::SANDBOX;
    }
}
