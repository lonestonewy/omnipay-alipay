<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Common\Message;

use Mockery as m;
use Omnipay\TestCase;

class AbstractRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = m::mock('\Omnipay\Common\Message\AbstractRequest[getData,send]');
        $this->request->initialize();
    }

    public function testInitializeWithParams()
    {
        $this->request->initialize(array('amount' => 123));
        $this->assertSame(123, $this->request->getAmount());
    }

    public function testCard()
    {
        // no type checking on card parameter
        $this->request->setCard('abc123');
        $this->assertSame('abc123', $this->request->getCard());
    }

    public function testSetCardWithArray()
    {
        // passing array should create CreditCard object
        $this->request->setCard(array('number' => '1234'));

        $card = $this->request->getCard();
        $this->assertInstanceOf('\Omnipay\Common\CreditCard', $card);
        $this->assertSame('1234', $card->getNumber());
    }

    public function testToken()
    {
        $this->request->setToken('12345');
        $this->assertSame('12345', $this->request->getToken());
    }

    public function testAmount()
    {
        $this->request->setAmount(200);
        $this->assertSame(200, $this->request->getAmount());
    }

    public function testAmountCastsToInteger()
    {
        $this->request->setAmount('6.1');
        $this->assertSame(6, $this->request->getAmount());
    }

    public function testGetAmountDecimal()
    {
        $this->request->setAmount(1366);
        $this->assertSame('13.66', $this->request->getAmountDecimal());
    }

    public function testGetAmountDecimalNoDecimals()
    {
        $this->request->setCurrency('JPY');
        $this->request->setAmount(1366);
        $this->assertSame('1366', $this->request->getAmountDecimal());
    }

    public function testCurrency()
    {
        $this->request->setCurrency('USD');
        $this->assertSame('USD', $this->request->getCurrency());
    }

    public function testCurrencyLowercase()
    {
        $this->request->setCurrency('usd');
        $this->assertSame('USD', $this->request->getCurrency());
    }

    public function testCurrencyNumeric()
    {
        $this->request->setCurrency('USD');
        $this->assertSame('840', $this->request->getCurrencyNumeric());
    }

    public function testCurrencyDecimals()
    {
        $this->request->setCurrency('JPY');
        $this->assertSame(0, $this->request->getCurrencyDecimalPlaces());
    }

    public function testDescription()
    {
        $this->request->setDescription('Cool product');
        $this->assertSame('Cool product', $this->request->getDescription());
    }

    public function testTransactionId()
    {
        $this->request->setTransactionId(87);
        $this->assertSame(87, $this->request->getTransactionId());
    }

    public function testGatewayReference()
    {
        $this->request->setGatewayReference('xyz');
        $this->assertSame('xyz', $this->request->getGatewayReference());
    }

    public function testClientIp()
    {
        $this->request->setClientIp('127.0.0.1');
        $this->assertSame('127.0.0.1', $this->request->getClientIp());
    }

    public function testReturnUrl()
    {
        $this->request->setReturnUrl('https://www.example.com/return');
        $this->assertSame('https://www.example.com/return', $this->request->getReturnUrl());
    }

    public function testCancelUrl()
    {
        $this->request->setCancelUrl('https://www.example.com/cancel');
        $this->assertSame('https://www.example.com/cancel', $this->request->getCancelUrl());
    }
}
