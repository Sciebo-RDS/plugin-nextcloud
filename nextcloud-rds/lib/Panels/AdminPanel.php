<?php

namespace OCA\RDS\Panels;

use \OCA\OAuth2\Db\ClientMapper;
use OCP\IURLGenerator;
use OCP\IUserSession;
use OCP\Settings\ISettings;
use OCP\AppFramework\Http\TemplateResponse;
use \OCA\RDS\Service\UrlService;
use \OCA\RDS\Service\RDSService;

class AdminPanel implements ISettings
{
    private $appName;
    /**
     * @var \OCA\OAuth2\Db\ClientMapper
     */
    private $clientMapper;
    /**
     * @var IUserSession
     */
    private $userSession;

    /**
     * @var IURLGenerator
     */
    private $urlGenerator;

    /**
     * @var UrlService
     */
    private $urlService;

    private $rdsService;


    public function __construct(
        ClientMapper $clientMapper,
        IUserSession $userSession,
        RDSService $rdsService
    ) {
        $this->appName = "rds";
        $this->clientMapper = $clientMapper;
        $this->userSession = $userSession;
        $this->urlGenerator = \OC::$server->getURLGenerator();
        $this->rdsService = $rdsService;
        $this->urlService = $rdsService->getUrlService();
    }

    public function getSection()
    {
        return 'additional';
    }

    /**
     * @return TemplateResponse
     */
    public function getForm()
    {
        $userId = $this->userSession->getUser()->getUID();
	$params = [
		'clients' => $this->clientMapper->getClients(),
		'user_id' => $userId,
		'urlGenerator' => $this->urlGenerator,
		"cloudURL" => $this->urlService->getURL(),
		"oauthname" => $this->rdsService->getOauthValue(),
	];
        $t = new TemplateResponse($this->appName, 'settings-admin', $params);
        return $t;
    }

    public function getPriority()
    {
        return 20;
    }
}
