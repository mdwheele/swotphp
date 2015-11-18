<?php namespace SwotPHP;

use Pdp\Parser;

class Swot
{
    private $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Determines whether or not an email address or domain is
     * part of a higher-education institution.
     *
     * @param  string $text Email address or domain name
     * @return bool   true if academic, false otherwise
     */
    public function isAcademic($text)
    {
        if (empty($text)) {
            return false;
        }

        $domain = $this->getDomain($text);

        if ($domain === null) {
            return false;
        }

        foreach ($this->getBlacklistedTopLevelDomains() as $blacklistedDomain) {
            $name = (string) $domain['host'];

            if (preg_match('/' . preg_quote($blacklistedDomain) . '$/', $name)) {
                return false;
            }
        }

        if (in_array($domain['tld'], $this->getAcademicTopLevelDomains())) {
            return true;
        }

        if ($this->matchesAcademicDomain($domain)) {
            return true;
        }

        return false;
    }

    /**
     * Alias for isAcademic(text)
     *
     * @param  string $text Email address or domain name
     * @return bool   true if academic, false otherwise
     */
    public function academic($text)
    {
        return $this->isAcademic($text);
    }

    /**
     * Retrieves domain information including top-level domain (tld),
     * second-level domain (sld), and host information.
     *
     * @param  string     $text Email address or domain name
     * @return array|null array of domain information if found, null if error.
     */
    private function getDomain($text)
    {
        try {
            $domain = array();
            $url = $this->parser->parseUrl(trim($text));

            $domain['tld'] = $url->host->publicSuffix;
            $registerableDomainParts = explode('.', $url->host->registerableDomain);
            $domain['sld'] = $registerableDomainParts[0];
            $domain['host'] = $url->host;
            $domain['path'] = implode('/', array_reverse($registerableDomainParts)) . '.txt';

            return $domain;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Retrieves the institution name matching a domain or email address.
     *
     * @param  string      $text Email address or domain name
     * @return string|null Name of institution if found, null if error.
     */
    public function getInstitutionName($text)
    {
        return $this->nameFromAcademicDomain($this->getDomain($text));
    }

    /**
     * Alias for getInstitutionName()
     *
     * @param  string      $text Email address or domain name
     * @return string|null Name of institution if found, null if error.
     */
    public function schoolName($text)
    {
        return $this->getInstitutionName($text);
    }

    /**
     * Retrieves the institution name matching a domain.
     *
     * @param  array       $domain
     * @return string|null Name of institution if found, null if file doesn't exist.
     */
    private function nameFromAcademicDomain($domain)
    {
        $path = $this->getPath($domain);

        if ( ! file_exists($path)) {
            return null;
        }

        return trim(file_get_contents($path));
    }

    /**
     * Constructs the path to a domain definition.
     *
     * @param  array  $domain
     * @return string path to domain definition.
     */
    private function getPath($domain)
    {
        return dirname(__DIR__) . '/domains/' . $domain['path'];
    }

    /**
     * Helper to determine if a file exists for an academic domain.
     *
     * @param  array $domain
     * @return bool  true if a file exists for the domain, false otherwise.
     */
    private function matchesAcademicDomain($domain)
    {
        if (empty($domain['tld']) or empty($domain['sld'])) {
            return false;
        }

        return file_exists($this->getPath($domain));
    }

    /**
     * @return array list of blacklisted top-level domains
     */
    private function getBlacklistedTopLevelDomains()
    {
        return array('si.edu', 'america.edu', 'folger.edu');
    }

    /**
     * @return array list of valid top-level domains
     */
    private function getAcademicTopLevelDomains()
    {
        return array(
            'ac.ae',
            'ac.at',
            'ac.bd',
            'ac.be',
            'ac.cn',
            'ac.cr',
            'ac.cy',
            'ac.fj',
            'ac.gg',
            'ac.gn',
            'ac.id',
            'ac.il',
            'ac.in',
            'ac.ir',
            'ac.jp',
            'ac.ke',
            'ac.kr',
            'ac.ma',
            'ac.me',
            'ac.mu',
            'ac.mw',
            'ac.mz',
            'ac.ni',
            'ac.nz',
            'ac.om',
            'ac.pa',
            'ac.pg',
            'ac.pr',
            'ac.rs',
            'ac.ru',
            'ac.rw',
            'ac.sz',
            'ac.th',
            'ac.tz',
            'ac.ug',
            'ac.uk',
            'ac.yu',
            'ac.za',
            'ac.zm',
            'ac.zw',
            'ed.ao',
            'ed.cr',
            'ed.jp',
            'edu',
            'edu.af',
            'edu.al',
            'edu.ar',
            'edu.au',
            'edu.az',
            'edu.ba',
            'edu.bb',
            'edu.bd',
            'edu.bh',
            'edu.bi',
            'edu.bn',
            'edu.bo',
            'edu.br',
            'edu.bs',
            'edu.bt',
            'edu.bz',
            'edu.ck',
            'edu.cn',
            'edu.co',
            'edu.cu',
            'edu.do',
            'edu.dz',
            'edu.ec',
            'edu.ee',
            'edu.eg',
            'edu.er',
            'edu.es',
            'edu.et',
            'edu.ge',
            'edu.gh',
            'edu.gr',
            'edu.gt',
            'edu.hk',
            'edu.hn',
            'edu.ht',
            'edu.in',
            'edu.iq',
            'edu.jm',
            'edu.jo',
            'edu.kg',
            'edu.kh',
            'edu.kn',
            'edu.kw',
            'edu.ky',
            'edu.kz',
            'edu.la',
            'edu.lb',
            'edu.lr',
            'edu.lv',
            'edu.ly',
            'edu.me',
            'edu.mg',
            'edu.mk',
            'edu.ml',
            'edu.mm',
            'edu.mn',
            'edu.mo',
            'edu.mt',
            'edu.mv',
            'edu.mw',
            'edu.mx',
            'edu.my',
            'edu.ni',
            'edu.np',
            'edu.om',
            'edu.pa',
            'edu.pe',
            'edu.ph',
            'edu.pk',
            'edu.pl',
            'edu.pr',
            'edu.ps',
            'edu.pt',
            'edu.pw',
            'edu.py',
            'edu.qa',
            'edu.rs',
            'edu.ru',
            'edu.sa',
            'edu.sc',
            'edu.sd',
            'edu.sg',
            'edu.sh',
            'edu.sl',
            'edu.sv',
            'edu.sy',
            'edu.tr',
            'edu.tt',
            'edu.tw',
            'edu.ua',
            'edu.uy',
            'edu.ve',
            'edu.vn',
            'edu.ws',
            'edu.ye',
            'edu.zm',
            'es.kr',
            'g12.br',
            'hs.kr',
            'ms.kr',
            'sc.kr',
            'sc.ug',
            'sch.ae',
            'sch.gg',
            'sch.id',
            'sch.ir',
            'sch.je',
            'sch.jo',
            'sch.lk',
            'sch.ly',
            'sch.my',
            'sch.om',
            'sch.ps',
            'sch.sa',
            'sch.uk',
            'school.nz',
            'school.za',
            'vic.edu.au'
        );
    }
}
