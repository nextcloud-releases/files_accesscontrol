<?php

declare(strict_types=1);
/**
 * @author Joas Schilling <coding@schilljs.com>
 *
 * @copyright Copyright (c) 2023 Joas Schilling <coding@schilljs.com>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
require __DIR__ . '/../../vendor/autoload.php';

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\ClientException;
use PHPUnit\Framework\Assert;
use Psr\Http\Message\ResponseInterface;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context {

	/** @var string */
	protected $currentUser;

	/** @var ResponseInterface */
	private $response = null;

	/** @var CookieJar */
	private $cookieJar;

	/** @var string */
	protected $baseUrl;

	/**
	 * FeatureContext constructor.
	 */
	public function __construct() {
		$this->cookieJar = new CookieJar();
		$this->baseUrl = getenv('TEST_SERVER_URL');
	}

	/**
	 * @BeforeScenario
	 * @AfterScenario
	 */
	public function cleanUpBetweenTests() {
		// TODO: Remove all created tags and workflows?
	}

	/**
	 * User management
	 */

	/**
	 * @Given /^as user "([^"]*)"$/
	 * @param string $user
	 */
	public function setCurrentUser(string $user): void {
		$this->currentUser = $user;
	}

	/**
	 * @Given /^user "([^"]*)" exists$/
	 * @param string $user
	 */
	public function assureUserExists(string $user): void {
		try {
			$this->userExists($user);
		} catch (ClientException $ex) {
			$this->createUser($user);
		}
		$response = $this->userExists($user);
		$this->assertStatusCode($response, 200);
	}

	private function userExists(string $user): ResponseInterface {
		$client = new Client();
		$options = [
			'auth' => ['admin', 'admin'],
			'headers' => [
				'OCS-APIREQUEST' => 'true',
			],
		];
		return $client->get($this->baseUrl . 'ocs/v2.php/cloud/users/' . $user, $options);
	}

	private function createUser(string $user): void {
		$previous_user = $this->currentUser;
		$this->currentUser = 'admin';

		$userProvisioningUrl = $this->baseUrl . 'ocs/v2.php/cloud/users';
		$client = new Client();
		$options = [
			'auth' => ['admin', 'admin'],
			'form_params' => [
				'userid' => $user,
				'password' => '123456'
			],
			'headers' => [
				'OCS-APIREQUEST' => 'true',
			],
		];
		$client->post($userProvisioningUrl, $options);

		// Quick hack to log in once with the current user
		$options2 = [
			'auth' => [$user, '123456'],
			'headers' => [
				'OCS-APIREQUEST' => 'true',
			],
		];
		$client->get($userProvisioningUrl . '/' . $user, $options2);

		$this->currentUser = $previous_user;
	}

	/*
	 * Requests
	 */

	/**
	 * @When /^sending "([^"]*)" to "([^"]*)"$/
	 * @param string $verb
	 * @param string $url
	 */
	public function sendingTo(string $verb, string $url): void {
		$this->sendingToWith($verb, $url, null);
	}

	/**
	 * @When /^sending "([^"]*)" to "([^"]*)" with$/
	 * @param string $verb
	 * @param string $url
	 * @param TableNode|null $body
	 * @param array $headers
	 */
	public function sendingToWith(string $verb, string $url, ?TableNode $body = null, array $headers = []): void {
		$fullUrl = $this->baseUrl . 'ocs/v2.php' . $url;
		$client = new Client();
		$options = [];

		if ($this->currentUser === 'admin') {
			$options['auth'] = [$this->currentUser, 'admin'];
		} else {
			$options['auth'] = [$this->currentUser, '123456'];
		}

		if ($body instanceof TableNode) {
			$fd = $body->getRowsHash();
			$options['form_params'] = $fd;
		}

		$options['headers'] = array_merge($headers, [
			'OCS-APIREQUEST' => 'true',
		]);

		try {
			$this->response = $client->request($verb, $fullUrl, $options);
		} catch (ClientException $ex) {
			$this->response = $ex->getResponse();
		}
	}

	/**
	 * @param ResponseInterface $response
	 * @param int $statusCode
	 */
	protected function assertStatusCode(ResponseInterface $response, int $statusCode): void {
		Assert::assertEquals($statusCode, $response->getStatusCode());
	}
}
