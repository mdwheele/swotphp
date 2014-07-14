<?php

class SwotPHPTest extends \PHPUnit_Framework_Testcase
{
    /** @test */
    function it_vacuously_passes_tests()
    {
        $this->assertTrue(true);
    }
    
    /** @test */
    function it_recognizes_academic_email_addresses_and_domains()
    {
        $this->assertTrue(Swot::isAcademic('lreilly@stanford.edu'));
        $this->assertTrue(Swot::isAcademic('LREILLY@STANFORD.EDU'));
        $this->assertTrue(Swot::isAcademic('Lreilly@Stanford.Edu'));
        $this->assertTrue(Swot::isAcademic('lreilly@slac.stanford.edu'));
        $this->assertTrue(Swot::isAcademic('lreilly@strath.ac.uk'));
        $this->assertTrue(Swot::isAcademic('lreilly@soft-eng.strath.ac.uk'));
        $this->assertTrue(Swot::isAcademic('lee@ugr.es'));
        $this->assertTrue(Swot::isAcademic('lee@uottawa.ca'));
        $this->assertTrue(Swot::isAcademic('lee@mother.edu.ru'));
        $this->assertTrue(Swot::isAcademic('lee@ucy.ac.cy'));

        $this->assertFalse(Swot::isAcademic('lee@leerilly.net'));
        $this->assertFalse(Swot::isAcademic('lee@gmail.com'));
        $this->assertFalse(Swot::isAcademic('lee@stanford.edu.com'));
        $this->assertFalse(Swot::isAcademic('lee@strath.ac.uk.com'));

        $this->assertTrue(Swot::isAcademic('stanford.edu'));
        $this->assertTrue(Swot::isAcademic('slac.stanford.edu'));
        $this->assertTrue(Swot::isAcademic('www.stanford.edu'));
        $this->assertTrue(Swot::isAcademic('http://www.stanford.edu'));
        $this->assertTrue(Swot::isAcademic('http://www.stanford.edu:9393'));
        $this->assertTrue(Swot::isAcademic('strath.ac.uk'));
        $this->assertTrue(Swot::isAcademic('soft-eng.strath.ac.uk'));
        $this->assertTrue(Swot::isAcademic('ugr.es'));
        $this->assertTrue(Swot::isAcademic('uottawa.ca'));
        $this->assertTrue(Swot::isAcademic('mother.edu.ru'));
        $this->assertTrue(Swot::isAcademic('ucy.ac.cy'));

        $this->assertFalse(Swot::isAcademic('leerilly.net'));
        $this->assertFalse(Swot::isAcademic('gmail.com'));
        $this->assertFalse(Swot::isAcademic('stanford.edu.com'));
        $this->assertFalse(Swot::isAcademic('strath.ac.uk.com'));

        $this->assertFalse(Swot::isAcademic(null));
        $this->assertFalse(Swot::isAcademic(''));
        $this->assertFalse(Swot::isAcademic('the'));

        $this->assertTrue(Swot::isAcademic(' stanford.edu'));
        $this->assertTrue(Swot::isAcademic('lee@strath.ac.uk '));
        $this->assertFalse(Swot::isAcademic(' gmail.com '));

        $this->assertTrue(Swot::isAcademic('lee@stud.uni-corvinus.hu'));
    }

    /** @test */
    function it_returns_name_of_valid_institution()
    {
        $this->assertEquals('University of Strathclyde', Swot::getInstitutionName('lreilly@cs.strath.ac.uk'));
        $this->assertEquals('BRG Fadingerstraße Linz, Austria', Swot::getInstitutionName('lreilly@fadi.at'));
    }

    /** @test */
    function it_returns_null_when_institution_invalid()
    {
        $this->assertEquals(null, Swot::getInstitutionName('foo@shop.com'));
    }

    /** @test */
    function it_test_aliased_methods()
    {
        $this->assertTrue(Swot::academic('stanford.edu'));
        $this->assertEquals('University of Strathclyde', Swot::schoolName('lreilly@cs.strath.ac.uk'));
    }

    /** @test */
    function it_fails_blacklisted_domains()
    {
        $blacklistedDomains = array(
            'si.edu', ' si.edu ', 'imposter@si.edu', 'foo.si.edu'
        );

        foreach ($blacklistedDomains as $domain) {
            $this->assertFalse(Swot::isAcademic($domain), "#{$domain} should be denied");
        }
    }

    /** @test */
    function it_does_not_error_on_tldonly_domains()
    {
        $this->assertFalse(Swot::isAcademic('.com'));
    }
}