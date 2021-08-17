<?php

namespace App\Tests\Service;

use App\Service\Mock\ParseUsers;
use App\Entity\ParseDataProvider;
use App\Service\NewsletterGenerator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ParseUsersTest extends KernelTestCase
{
    public function testParse()
    {
        self::bootKernel();
        $container = static::getContainer();
        $parser = $container->get(ParseUsers::class);
        // $res = $parser->parse();

        $mockedDataProvider = new ParseDataProvider();
        $mockedDataProvider->setUrl('fake url');
        $mockedDataProvider->setSubmission(json_decode('{"Email":"email","FullName":"name.title;name.first;name.last","Country":"location.country","Username":"login.username","Gender":"gender","City":"location.city","Phone":"phone"}', true));
        $mockedDataProvider->setElementsForParse(10);
        $mockedDataProvider->setRootIteratorElement('results');

        $parseResults = $parser->parse($mockedDataProvider);
        $this->assertIsArray($parseResults);
        $this->assertEquals(10, count($parseResults));

        // $this->assertEquals(..., $newsletter->getContent());
    }
}
