<?php

use Pdp\Parser;
use Pdp\PublicSuffixListManager;
use SwotPHP\Swot;
use SwotPHP\Facades\Native\Swot as SwotFacade;

class SwotPHPTest extends \PHPUnit_Framework_Testcase
{
    public $swot;

    public function setUp()
    {
        $list = new PublicSuffixListManager();
        $this->swot = new Swot(new Parser($list->getList()));
    }

    /** @test */
    function it_can_use_native_facade_accessor()
    {
        $this->assertTrue(SwotFacade::isAcademic('lreilly@stanford.edu'));
        $this->assertEquals('University of Strathclyde', SwotFacade::getInstitutionName('lreilly@cs.strath.ac.uk'));
    }

    /** @test */
    public function it_recognizes_academic_email_addresses_and_domains()
    {
        $this->assertTrue($this->swot->isAcademic('lreilly@stanford.edu'));
        $this->assertTrue($this->swot->isAcademic('LREILLY@STANFORD.EDU'));
        $this->assertTrue($this->swot->isAcademic('Lreilly@Stanford.Edu'));
        $this->assertTrue($this->swot->isAcademic('lreilly@slac.stanford.edu'));
        $this->assertTrue($this->swot->isAcademic('lreilly@strath.ac.uk'));
        $this->assertTrue($this->swot->isAcademic('lreilly@soft-eng.strath.ac.uk'));
        $this->assertTrue($this->swot->isAcademic('lee@ugr.es'));
        $this->assertTrue($this->swot->isAcademic('lee@uottawa.ca'));
        $this->assertTrue($this->swot->isAcademic('lee@mother.edu.ru'));
        $this->assertTrue($this->swot->isAcademic('lee@ucy.ac.cy'));

        $this->assertFalse($this->swot->isAcademic('lee@leerilly.net'));
        $this->assertFalse($this->swot->isAcademic('lee@gmail.com'));
        $this->assertFalse($this->swot->isAcademic('lee@stanford.edu.com'));
        $this->assertFalse($this->swot->isAcademic('lee@strath.ac.uk.com'));

        $this->assertTrue($this->swot->isAcademic('stanford.edu'));
        $this->assertTrue($this->swot->isAcademic('slac.stanford.edu'));
        $this->assertTrue($this->swot->isAcademic('www.stanford.edu'));
        $this->assertTrue($this->swot->isAcademic('http://www.stanford.edu'));
        $this->assertTrue($this->swot->isAcademic('http://www.stanford.edu:9393'));
        $this->assertTrue($this->swot->isAcademic('strath.ac.uk'));
        $this->assertTrue($this->swot->isAcademic('soft-eng.strath.ac.uk'));
        $this->assertTrue($this->swot->isAcademic('ugr.es'));
        $this->assertTrue($this->swot->isAcademic('uottawa.ca'));
        $this->assertTrue($this->swot->isAcademic('mother.edu.ru'));
        $this->assertTrue($this->swot->isAcademic('ucy.ac.cy'));

        $this->assertFalse($this->swot->isAcademic('leerilly.net'));
        $this->assertFalse($this->swot->isAcademic('gmail.com'));
        $this->assertFalse($this->swot->isAcademic('stanford.edu.com'));
        $this->assertFalse($this->swot->isAcademic('strath.ac.uk.com'));

        $this->assertFalse($this->swot->isAcademic(null));
        $this->assertFalse($this->swot->isAcademic(''));
        $this->assertFalse($this->swot->isAcademic('the'));

        $this->assertTrue($this->swot->isAcademic(' stanford.edu'));
        $this->assertTrue($this->swot->isAcademic('lee@strath.ac.uk '));
        $this->assertFalse($this->swot->isAcademic(' gmail.com '));

        $this->assertTrue($this->swot->isAcademic('lee@stud.uni-corvinus.hu'));
    }

    /** @test */
    public function it_returns_name_of_valid_institution()
    {
        $this->assertEquals('University of Strathclyde', $this->swot->getInstitutionName('lreilly@cs.strath.ac.uk'));
        $this->assertEquals('BRG Fadingerstraße Linz, Austria', $this->swot->getInstitutionName('lreilly@fadi.at'));
    }

    /** @test */
    public function it_returns_null_when_institution_invalid()
    {
        $this->assertEquals(null, $this->swot->getInstitutionName('foo@shop.com'));
    }

    /** @test */
    public function it_test_aliased_methods()
    {
        $this->assertTrue($this->swot->academic('stanford.edu'));
        $this->assertEquals('University of Strathclyde', $this->swot->schoolName('lreilly@cs.strath.ac.uk'));
    }

    /** @test */
    public function it_fails_blacklisted_domains()
    {
        $blacklistedDomains = array(
            'si.edu', ' si.edu ', 'imposter@si.edu', 'foo.si.edu', 'america.edu', 'folger.edu'
        );

        foreach ($blacklistedDomains as $domain) {
            $this->assertFalse($this->swot->isAcademic($domain), "#{$domain} should be denied");
        }
    }

    /** @test */
    public function it_does_not_error_on_tldonly_domains()
    {
        $this->assertFalse($this->swot->isAcademic('.com'));
    }
}