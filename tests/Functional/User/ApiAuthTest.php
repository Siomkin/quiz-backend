<?php

// tests/Controller/PostControllerTest.php

namespace App\Tests\Functional\User;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiAuthTest extends WebTestCase
{
    public function testShowPost(): void
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    /**
     * Create a client with a default Authorization header.
     *
     * @param string $username
     * @param string $password
     *
     * @return Client
     */
    protected function createAuthenticatedClient($username = 'admin1@it.com', $password = 'pass'): Client
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/login_check',
            [], [], ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                    'username' => $username,
                    'password' => $password,
                ]
            )
        );

        $data = json_decode($client->getResponse()->getContent(), true);

        $client = static::createClient();

        $client->setServerParameter('HTTP_AUTHORIZATION', sprintf('Bearer %s', $data['token']));

        return $client;
    }

    public function testPublicPage(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/quizzes?_page=1');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testAccessToAuthenticatedPage(): void
    {
        $client = $this->createAuthenticatedClient();

        /** @var User $user */
        $user = $client->getContainer()->get('doctrine')
            ->getRepository(User::class)->findOneBy([]);

        $client->xmlHttpRequest('GET', '/api/users/'.$user->getId());

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testFailAccessToAuthenticatedPage(): void
    {
        $client = static::createClient();

        /** @var User $user */
        $user = $client->getContainer()->get('doctrine')
            ->getRepository(User::class)->findOneBy([]);

        $client->xmlHttpRequest('GET', '/api/users/'.$user->getId());

        $this->assertSame(401, $client->getResponse()->getStatusCode());
    }
}
